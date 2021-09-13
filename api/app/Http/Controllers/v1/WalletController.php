<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\WalletCollection;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $user = $request->user();

        $per_page = (int) $request->query("per_page", 15);

        $wallet = Wallet::where("customer_id", $user->id);
        
        $collection = new WalletCollection($wallet->paginate($per_page));

        return $collection->additional([
            "status" => 1,
            "wallet" => $user->wallet
        ]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            'code' => [
                'required',
            ],

        ];

        $messages = [
            'code.required' => 'Please provider the YGAG Code to redeem.'
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

        $code = $request->input('code');


         //api call to third party 

        $url = 'https://merchant.yougotagift.com/api/v1/redeem/';

        $username = 'orders@valucart.com';

        $password = '&Wj1fqNp%D&X';

        $now = now();

        $reference_number = $now->year + 
                      $now->month + 
                      $now->day + 
                      $now->hour + 
                      $now->minute + 
                      $now->second + 
                      mt_rand(100, 1100) + 
                      $customer->id;

        $reference = "VC". $reference_number;
           
        $postdata = array(

                            'store_staff_name' => 'Doan',
                            'store_staff_position' => 'Developer',
                            'store_location' => 'Garhoud',
                            'reference_id' => $reference,
                            'code' => $code
                        );
        //Initiate cURL.
        $ch = curl_init($url);
         
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $response = curl_exec($ch);
         
        if(curl_errno($ch)){

            throw new Exception(curl_error($ch));
        }



        $json_data = json_decode($response, true);
 
        if(array_key_exists('errors',$json_data)){
            
            return response()->json([
                'status' => 0,
                'message' => $json_data["errors"]['message'],
            ], 422);

        }
        
        $customer_id = $customer->id;

        $transact_amt = $json_data['amount'];

        $customer_wallet_amt = $customer->wallet;

        $total_amt = $customer->wallet + $transact_amt;

        DB::table('customers')
            ->where('id', $customer_id)
            ->update(['wallet' => $total_amt]);


        $id = DB::table('wallet_transactions')->insertGetId(
                    [
                     'customer_id' => $customer_id,
                     'description' => 'Credited Wallet',
                     'transact_amt' => $transact_amt,
                     'reference' => $reference,
                     'type' => 'credit',
                     'amt_left' => $total_amt,
                     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                 ]
        );

        $customer = $request->user()->refresh();

        $transaction_data = Wallet::find($id);

        return response()->json([
            'status' => 1,
            'data' => $customer,
            'transaction_data' => $transaction_data,
            'message' => 'Redeemed the card Succesfully'
        ], 201);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $customer = $request->user();

        return response()->json([
            'status' => 1,
            'data' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function redeem($code)
    {

        $url = 'https://sandbox.yougotagift.com/merchant/api/redeem/';

        $username = 'hamza@valucart.com';

        $password = '6UfpWrRz2NFF';

        $postdata = array(

                            'store_staff_name' => 'Doan',
                            'store_staff_position' => 'Developer',
                            'store_location' => 'Garhoud',
                            'code' => $code
                        );
        //Initiate cURL.
        $ch = curl_init($url);
         
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $response = curl_exec($ch);
         
        if(curl_errno($ch)){

            throw new Exception(curl_error($ch));
        }

        $json_data = json_decode($response, true);
      //  print_r($json_data);die;
       /// print_r($json_data["errors"][0]["errors"][0]);die;
        //Print out the response.
    //   echo $response;die;

        if($json_data['errors']){

            print_r($json_data["errors"][0]["errors"][0]);die;
        }

    }



}
