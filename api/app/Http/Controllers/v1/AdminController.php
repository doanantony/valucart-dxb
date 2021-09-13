<?php

namespace App\Http\Controllers\v1;

use Hashids;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerTrait;

class AdminController extends Controller
{   
    public function location(Request $request){

        // select departments.name, vendor_location.name from departments left join vendor_location on departments.id = vendor_location.vendor_id where vendor_location.id in(1,3,5)
       $pattern = DB::table('vendor_location')->select('name', 'id','latitude','longitude','range')->get();
      
       $request = array('latitude' => 25.2447198,'longitude' =>55.2962462 );

       if (!empty($pattern)) {
       
                              foreach($pattern as $res) {

                                    if ($res->latitude == '' || $res->longitude == '') {
                                          continue;
                                    }

                                    $check_in_range = $this->check_range($res, $request);
                                    if ($check_in_range) {
                                         // $test = $res->id;
                                          $new_res[] = $res->id;
                                          
                                    }
                                    
                              }
                              
                              print_r($new_res);


                        } 


    }


    function check_range($rs, $rq) {

            $latitude2 = $rq['latitude'];

            $longitude2 = $rq['longitude'];

            $latitude1 = $rs->latitude;

            $longitude1 = $rs->longitude;

            $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

            if ($range <= $rs->range) {

                  $latitude2 = $rq['latitude'];

                  $longitude2 = $rq['longitude'];

                  $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

                  if ($range <= $rs->range) {

                        return true;

                  } else {

                        return false;

                  }

            } else {

                  return false;

            }

      }


      function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {

            $earth_radius = 6371;

            $dLat = deg2rad($latitude2 - $latitude1);

            $dLon = deg2rad($longitude2 - $longitude1);

            $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * asin(sqrt($a));

            $d = $earth_radius * $c;

            return $d;

      }










    public function login(Request $request)
    {  
        $rules = [
            'email' => [
                'required',
                'min:3',
                'max:50'
            ],
            'password' => [
                'required',
                'min:4',
                'max:500'
            ],
            'fcm_token' => [
                'required',
                'min:4',
                'max:500'
            ],
        ];

        $messages = [
            'email.required' => 'Please provide the email.',
            'email.min' => 'The email should be at least 3 characters long.',
            'email.max' => 'The email should not be longer than 50 characters.',
            'password.required' => 'Please provide the  password.',
            'password.min' => 'The password should be at least 3 characters long.',
            'password.max' => 'The password should not be longer than 500 characters.',
            'fcm_token.required' => 'Please provide the  fcm_token.',
            'fcm_token.min' => 'The fcm_token should be at least 3 characters long.',
            'fcm_token.max' => 'The fcm_token should not be longer than 500 characters.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate(); 

        $email = $request->input('email');

        $password = md5($request->input('password'));

        $fcm_token = $request->input('fcm_token');

        $users = DB::table('users')->where('username',$email)->where('passwd',$password)->count();

        if($users == 1){

            $unqe = md5(uniqid(time().mt_rand(), true));

            DB::table('users')
                        ->where('username',$email)
                        ->where('passwd',$password)
                        ->update(['fcm_token' => $fcm_token]);

            $users = DB::table('users')->where('username',$email)->select('user_id', 'user_type_id')->get();

           
            DB::table('auth_table')->insert(
                ['user_id' => $users[0]->user_id,
                 'user_type_id' => $users[0]->user_type_id,
                 'unique_id' => $unqe
                ]
            );          

            return response()->json([
                'status' => 1,
                'authtoken' => $unqe,
                'message' => 'Logged In Successfully'
            ], 200);


        }else{

            return response()->json([
                'status' => 0,
                'message' => 'Invalid Credentials! Please try again!'
            ], 404);

        }
        
    }



    public function generatehash($id)
    {

        $hashed = Hashids::encode($id);
      
      
        return response()->json([
            'status' => 1,
            'data' => $hashed
        ], 200);


    }








    protected function uploadnotificationimage(Request $request)
    {   
     

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
                   
                    $image = $request->file('image');
                     
                   // $image_name = 'thumb.' . $image->extension();

                    $image_filename = $image->getClientOriginalName();
                   
              
                    $image->storeAs('notification_images', '/' . $image_filename, 's3');
                
                    

        }

        
        $prepared_response = [
            'status' => 1,
            'data' => [
                'id' => $image_filename,
            ]
        ];

        return response()->json($prepared_response, 200);



    }

    //delete item from snapshot

    public function itemremove_snapshot(Request $request)
            {  

               
                $order_id = $request->input('order_id');

                $item_id = $request->input('product_id');

                $order = DB::table("orders")->where("id",$order_id)->select("snapshots")->get();

                $order =$order[0]->snapshots;

                $unserialize_data = unserialize($order);

                foreach ( $unserialize_data['products'] as $key => $data) {
                       
                            if ($data['id'] == $item_id) {

                                  $quantity = $data['quantity'];

                                  $price = $quantity * $data['price'];

                                  unset($unserialize_data['products'][$key]);

                                  break; 
                                }
                }
                
              $unserialize_data['sub_total'] = $unserialize_data['sub_total'] - $price;
            
              $unserialize_data['total'] = $unserialize_data['total'] - $price;

              $serialize_data = serialize($unserialize_data);

              DB::table('orders')
                    ->where('id', $order_id)
                    ->update(['snapshots' => $serialize_data]);


                // return response()->json([
                //     'status' => 1,
                //     'data' => $unserialize_data
                // ], 200);


        
                
            }



    public function charity_address(Request $request)
    {
        
                return response()->json([
                    'status' => 1,
                    'data' => [
                        'id' => 1,
                        'name' => 'Dar Al Ber',
                        'address' => 'Shahikh Zahid Road',
                        'img' => 'https://v2api.valucart.com/img/bundles/Bvx565LbngoN/thumb.jpeg'
                    ]
                ], 200);

    }

    
    public function alterorder(Request $request)
    {
        
        $rules = [
            
            'order_id' => [
                'required',
                'exists:orders,order_reference',
            ],

            'item' => [
                'required',
                Rule::in(['product', 'bundle']),
            ],

            'item_id' => [
                'required',
            ],

            'quantity' => [
                'required',
            ],


            'action' => [
                'required',
                Rule::in(['add', 'remove']),
            ],

        ];

        $messages = [
            'order_id.required' => 'Pleaser provide the order id.',
            'order_id.exists' => 'The order id seems to be invalid.',
            'item_id.required' => 'Pleaser provide the item id.',
            'quantity.required' => 'Pleaser provide the quantity.',
            'item.required' => 'Please provide item.',
            'item.in' => 'item must product or bundle ',
            'action.required' => 'Please provide action.',
            'action.in' => 'action must add or remove '
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $order_id = $request->input('order_id');
        
        $action = $request->input('action');

        $item = $request->input('item');

        $quantity = $request->input('quantity');

        $item_id = $request->input('item_id');

        $order_exists = DB::table('order_fullfillment')->where('order_reference', $order_id)->count();

        if($order_exists == 0){

                $order = DB::table("orders")->where("order_reference",$order_id)->select("snapshots")->get();



        }else{

                $order = DB::table("order_fullfillment")->where("order_reference",$order_id)->select("snapshots")->get();


        }

                $order =$order[0]->snapshots;

                $unserialize_data = unserialize($order);

               // print_r($unserialize_data);die;

            if($item == 'product'){

                if($action == 'remove'){

                    foreach ( $unserialize_data['products'] as $key => $data) {
                       
                            if ($data['id'] == $item_id) {

                                  $order_quantity = $data['quantity'];

                                  if($order_quantity == 1){

                                     $quantity = $data['quantity'];
                                   
                                     $price = $quantity * $data['price'];

                                     unset($unserialize_data['products'][$key]);


                                  }else{
                                        
                                     $new_qty = $order_quantity - $quantity;
                                        
                                     $price = $new_qty * $data['price'];

                                     $unserialize_data['products'][$key]['quantity'] = $new_qty;

                                  }

                                }
                               
                     }

                $unserialize_data['sub_total'] = $unserialize_data['sub_total'] - $price;
              
                $unserialize_data['total'] = $unserialize_data['total'] - $price;
                $snapshots = serialize($unserialize_data);

                $order_exists = DB::table('order_fullfillment')->where('order_reference', $order_id)->count();
            
            if($order_exists == 0){


                             DB::table('order_fullfillment')->insert(
                                  ['order_reference' => $order_id,
                                   'status' => 0,
                                   'snapshots' => $snapshots,
                                   'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                   'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                  ]
                             );

            }else{


                            DB::table('order_fullfillment')
                            ->where('order_reference', $order_id)
                            ->update(
                                [
                                    'snapshots' => $snapshots
                         ]);
            }

            return response()->json($unserialize_data, 201);

            }else if($action == 'add'){

                foreach ( $unserialize_data['products'] as $key => $data) {
                       
                            if ($data['id'] == $item_id) {

                                $order_quantity = $data['quantity'];

                                $qty = $quantity + $order_quantity;
                                   
                                $price = $qty * $data['price'];

                                $unserialize_data['sub_total'] = $unserialize_data['sub_total'] + $price;
                              
                                $unserialize_data['total'] = $unserialize_data['total'] + $price;

                                $snapshots = serialize($unserialize_data);

                $order_exists = DB::table('order_fullfillment')->where('order_reference', $order_id)->count();
            
                if($order_exists == 0){


                                 DB::table('order_fullfillment')->insert(
                                      ['order_reference' => $order_id,
                                       'status' => 0,
                                       'snapshots' => $snapshots,
                                       'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                       'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                      ]
                                 );

                }else{


                                DB::table('order_fullfillment')
                                ->where('order_reference', $order_id)
                                ->update(
                                    [
                                        'snapshots' => $snapshots
                             ]);
                }

                return response()->json($unserialize_data, 201);


                            }

                    }

                $product = Product::with('packaging_quantity_unit','brand', 'images', 'category', 'subcategory')->find($item_id);  

                $price = $quantity * $product['valucart_price'];

                array_push( $unserialize_data['products'], $product->toArray());
                
                $unserialize_data['sub_total'] = $unserialize_data['sub_total'] + $price;
                
                $unserialize_data['total'] = $unserialize_data['total'] + $price;
                
                foreach ( $unserialize_data['products'] as $key => $data){

                    // $hashed = Hashids::encode($item_id);
                    // $decoded_id = array_shift($unhased);
                   // print_r($item_id);die;
                    if ($data['id'] == $item_id){

                        $data['quantity'] = $quantity;

                        $data['id'] = $item_id;
                    }

                }
                $snapshots = serialize($unserialize_data);

                $order_exists = DB::table('order_fullfillment')->where('order_reference', $order_id)->count();
                
                if($order_exists == 0){


                                 DB::table('order_fullfillment')->insert(
                                      ['order_reference' => $order_id,
                                       'status' => 0,
                                       'snapshots' => $snapshots,
                                       'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                       'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                      ]
                                 );

                }else{


                                DB::table('order_fullfillment')
                                ->where('order_reference', $order_id)
                                ->update(
                                    [
                                        'snapshots' => $snapshots
                             ]);
                }

                return response()->json($unserialize_data, 201);





                }

            }





    }













}
