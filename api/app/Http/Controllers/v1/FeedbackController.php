<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Validator;
use App\Models\Orders;
use App\Models\Feedback;
use App\Http\Resources\FeedbackCollection;

use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $order = $request->query('order');
        $customer = $request->query('customer');

        $query = Feedback::query();

        $query = $query->when(!is_null($order), function($query) use ($order) {

            return $query->where('order_id', $order);

        });

        $query = $query->when(!is_null($customer), function($query) use ($customer) {

            return $query->where('customer_id', $customer);

        });

        $per_page = (int) $request->query('per_page', 15);

        $collection = new FeedbackCollection($query->paginate($per_page));

        return $collection->additional([
            'status' => 1
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [

            'order_id' => [
                'required',
            ],

            'message' => [
                'required',
                'max:2026',
            ]
        ];

        $messages = [
            'order_id.required' => 'Please provider the order id.',
            'message.required' => 'Please enter the message',
            'message.max' => 'The message id too long.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $customer = $request->user();

        $order_id = $request->input('order_id');
        $message = $request->input('message');

        $order = Orders::where('id', $order_id)->where('customer_id', $customer->id);

        if (!$order->exists()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'order_id' => 'Invalid order or order - customer missmatch.',
                ]
            ], 422);

        }

        Feedback::create([
            'customer_id' => $customer->id,
            'order_id' => $order_id,
            'feedback' => $message
        ]);
        
        return response()->json([
            'status' => 1,
            'message' => 'Your feedback is greatly appreciated, and will be reviewed promptly.'
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $feedback_id)
    {
        
        $message = Feedback::find($feedback_id);

        if (is_null($message)) {
            return response('', 404);
        }

        return response()->json($message, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function sendpush(){

            
             $fcm_token = 'dJXEb2jMZLU:APA91bGlHmy_-3kx4KFFWS0_ZluPFYvmVBVKOwkvQgqwcHTNnMarTrICZITga2ru66vQG_KKaneIaJADFzL2vjYnpg1rYj1dpFIDBONzzEMm04F6oMBGxiT2SrOMGtN35bKSW5wUp9VI';

             $fcm_data = array('type' =>'orderstatus','id' => 'ValuCart77927', 'title' => 'Valucart', 'message' => 'Valucart Test Push');

             $key = 'AAAAzB0NRgw:APA91bE8BFXH7biQ9KBfEZkW1qLMM4liVPPkDwVt9pM8Zva4HG5IVLqi6yC6Wx80ZBZnVN12vH-Un8xHRU0rSjY95uk4hFI58MwgkEoJlO3Fo_d7h_rQcqfOO5Althay_RleII_iuF_o';




     
             

$fcm_title = 'Valucart';
$fcm_message =  'Your order have been shipped .our captain is on his way';

                          $data = "
                          { 
                            \"data\" :
                                    {  
                                         \"order_id\" : \"".$fcm_data['id']."\",
                                         \"type\" : \"".$fcm_data['type']."\",
                                         \"title\" : \"".$fcm_data['title']."\",
                                         \"message\" : \"".$fcm_data['message']."\",
                                        
                                         },
                            \"to\" : \"".$fcm_token."\"
                          }
                      ";




            $ch = curl_init("https://fcm.googleapis.com/fcm/send");

            $header = array('Content-Type: application/json', 'Authorization: key='.$key);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // curl_close($ch);

            $out = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
           // curl_exec($ch);

            $r = curl_exec($ch);
           print_r($r);die;
           

    }



}
