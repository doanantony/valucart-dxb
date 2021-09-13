<?php

namespace App\Http\Controllers\v1;

use Throwable;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

use Carbon\Carbon;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\CustomerAddress;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{

    private $guzzle_client = null;
    
    public function request_payment(Request $request)
    {

        $rules = [
            
            'order' => [
                'required',
                'exists:orders,id',
            ],

            'payment_method' => [
                'required',
                Rule::in(['cod', 'card','wallet','wallet,cod','wallet,card']),
            ],

        ];

        $messages = [
            'order.required' => 'Pleaser provide the order id.',
            'order.exists' => 'The order id seems to be invalid.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Payment method must be cod ,cash or wallet'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

        }

        $order = Orders::find($request->input('order'));


   //    $meatmonday = unserialize($order->snapshots)['meat_monday'];

        $osp = unserialize($order->snapshots);
        $meatmonday = isset($osp['meat_monday']) ? true : false ;


        if (is_null($order)) {

            return response()->json([
                'status' => 0,
                'message' => 'Order not found.'
            ], 404);

        }
        
        $payment_type = $request->input('payment_method');

        // Get cart information
        $cart_info = \DB::table('carts')->where('id', $order->cart_id)->first();
        if (is_null($cart_info) && !$meatmonday) {

            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid.',
                'errors' => [
                    'order' => 'There seems to be a problem with the cart associated with this order.',
                ],
            ], 422);

        }

        if ($cart_info && !is_null($cart_info->coupon)) {

            $coupon = Coupon::find($cart_info->coupon);

            if (is_null($coupon)) {

                return response()->json([
                    'status' => 0,
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'order' => 'There seems to be a problem with the cart associated with this order.',
                    ],
                ], 422);

            }

            if (($coupon->for_payment_method == Coupon::CASH_PAYMENT && $payment_type != 'cod')
                || ($coupon->for_payment_method == Coupon::CARD_PAYMENT && $payment_type != 'card')) {

                return response()->json([
                    'status' => 0,
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'payment_method' => 'Selected payment method is not applicable to this order.',
                    ],
                ], 422);

            }

        }

        if ($payment_type == 'cod') {

            $customer = Customer::find($order->customer_id);

            $order->status = Orders::PLACED;
            $order->payment_type = 'cod';
            $order->payment_status = Orders::COD_INITIATED;
            $order->save();
            
            // Clear cart
            Cart::clear_cart($order->cart_id);

            \DB::table('carts')
                ->where('id', $order->cart_id)
                ->update([
                    'coupon' => null,
                    'discount' => 0,
                ]);
            
            \DB::table('customer_bundles')
                ->where('id', $order->cart_id)
                ->update([
                    'status' => 'checkedout',
                ]);
                
                
                
            if ($cart_info && $cart_info->coupon && $cart_info->discount) {
                Coupon::record_usage($cart_info->coupon, $customer->id);
            }
            
           // $sales_email = new \App\Mail\SalesOrderPlaced($order);
            //$custmer_email = new \App\Mail\CustomerOrderConfirmation($order);

            $sales_emails = env('SALES_EMAILS');
            $sales_emails = explode('|', $sales_emails);

           /* foreach ($sales_emails as $email) {
                Mail::to($email)->send($sales_email);
            }*/

           // Mail::to($customer)->send($custmer_email);

             /* === Code edited on august 26 starts here === */
            
            $customer_id = $customer->id;

            $customer_name = $customer->name;

            $order_price = $order->price;
            
            $order_id = $order->order_reference;

            $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER PLACED', 'message' => 'Hello ' .$customer_name. '! your order of AED' .$order_price.' was successfuly Placed. We will get in touch with you shortly 😊');

           $this->TriggerPushOrderPlaced($customer_id,$fcm_data);

             /* === Code edited on august 26 starts here === */

            return response()->json([
                'status' => 1,
                'message' => 'Your order was successfuly placed'
            ]);

        }else if($payment_type == 'wallet'){
            //
                $customer = Customer::find($order->customer_id);
                $customer_id = $order->customer_id;
                $customer_wallet = $customer->wallet;
                $delivery_charge = getenv("DELIVERY_CHARGE");
                $total = $delivery_charge + $order['price'];
                $to_pay = $total - $customer_wallet;
                
                    if($customer_wallet == 0 ){

                         return response()->json([
                            'status' => 0,
                            'message' => 'Oops! Its look a like that you do not have money in your wallet. Please choose “Pay by Cash” or “Pay by Card” to place your order.',
                        ], 422);


                    } else if($total > $customer_wallet){

                          return response()->json([
                            'status' => 0,
                            'message' => 'Ahh! You have only '.$customer_wallet.' AED in your wallet.To pay remaining '.$to_pay.' AED please choose “Pay by Cash” or “Pay by Card” to place your order.',
                        ], 422);

                    }else{

                        $order->status = Orders::PLACED;
                        $order->payment_type = 'wallet';
                        $order->payment_status = Orders::COD_INITIATED;
                        $order->save();

                        $snaphot = unserialize($order['snapshots']);
                        $snaphot['valucredits'] = $total;
                        $snaphot = serialize($snaphot);
                        $order->snapshots = $snaphot;
                        $order->save();


                        //update wallet

                        $amt_left = $customer_wallet - $total;

                        DB::table('customers')
                            ->where('id', $customer_id)
                            ->update(['wallet' => $amt_left]);

                        DB::table('wallet_transactions')->insert(
                                [
                                 'customer_id' => $customer_id,
                                 'description' => 'Purchased Items',
                                 'transact_amt' => $total,
                                 'type' => 'debit',
                                 'amt_left' => $amt_left,
                                 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                             ]
                    );

                        //update snapshot



        

                        // Clear cart
                        Cart::clear_cart($order->cart_id);

                        \DB::table('carts')
                            ->where('id', $order->cart_id)
                            ->update([
                                'coupon' => null,
                                'discount' => 0,
                            ]);
                        
                        \DB::table('customer_bundles')
                            ->where('id', $order->cart_id)
                            ->update([
                                'status' => 'checkedout',
                            ]);
                
                
                        if ($cart_info && $cart_info->coupon && $cart_info->discount) {
                            Coupon::record_usage($cart_info->coupon, $customer->id);
                        }
                        
                        $sales_email = new \App\Mail\SalesOrderPlaced($order);
                        $custmer_email = new \App\Mail\CustomerOrderConfirmation($order);

                        $sales_emails = env('SALES_EMAILS');
                        $sales_emails = explode('|', $sales_emails);

                        foreach ($sales_emails as $email) {
                            Mail::to($email)->send($sales_email);
                        }

                        Mail::to($customer)->send($custmer_email);
                        
                        $customer_id = $customer->id;

                        $customer_name = $customer->name;

                        $order_price = $order->price;
                        
                        $order_id = $order->order_reference;

                        $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER PLACED', 'message' => 'Hello ' .$customer_name. '! your order of AED' .$order_price.' was successfuly Placed. We will get in touch with you shortly 😊');

                       $this->TriggerPushOrderPlaced($customer_id,$fcm_data);

                        return response()->json([
                            'status' => 1,
                            'message' => 'Your order was successfuly placed'
                        ]);


                    }
         

        }else if($payment_type == 'wallet,cod'){
           
                $customer = Customer::find($order->customer_id);
                $customer_id = $order->customer_id;
                $customer_wallet = $customer->wallet;
                $delivery_charge = getenv("DELIVERY_CHARGE");
                $total = $delivery_charge + $order['price'];
                $to_pay = $total - $customer_wallet;
               // print_r($total);die;

                if($customer_wallet == 0){

                         return response()->json([
                            'status' => 0,
                            'message' => 'Oops! Its look a like that you do not have money in your wallet. Please choose “Pay by Cash” or “Pay by Card” to place your order.',
                        ], 422);


                } else if($customer_wallet > $total){

                        return response()->json([
                            'status' => 0,
                            'message' => 'Ahh! You have only '.$customer_wallet.' AED in your wallet.To pay remaining '.$to_pay.' AED please choose “Pay by Cash” or “Pay by Card” to place your order.',
                        ], 422);

                }else{

                        //update wallet
                        $order->status = Orders::PLACED;
                        $order->payment_type = 'wallet,cod';
                        $order->payment_status = Orders::COD_INITIATED;
                        $order->save();

                        DB::table('customers')
                            ->where('id', $customer_id)
                            ->update(['wallet' => 0]);

                        DB::table('wallet_transactions')->insert(
                                [
                                 'customer_id' => $customer_id,
                                 'description' => 'Purchased Items',
                                 'transact_amt' => $customer_wallet,
                                 'type' => 'debit',
                                 'amt_left' => 0,
                                 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                             ]
                        );

                        //update snapshot
                        $snaphot = unserialize($order['snapshots']);
                        $snaphot['valucredits'] = $customer_wallet;
                        $snaphot = serialize($snaphot);
                        $order->snapshots = $snaphot;
                        $order->save();

                        // Clear cart
                        Cart::clear_cart($order->cart_id);

                        \DB::table('carts')
                            ->where('id', $order->cart_id)
                            ->update([
                                'coupon' => null,
                                'discount' => 0,
                            ]);
                        
                       \DB::table('customer_bundles')
                            ->where('id', $order->cart_id)
                            ->update([
                                'status' => 'checkedout',
                            ]);
                
                        if ($cart_info && $cart_info->coupon && $cart_info->discount) {
                            Coupon::record_usage($cart_info->coupon, $customer->id);
                        }
                        
                        $sales_email = new \App\Mail\SalesOrderPlaced($order);
                        $custmer_email = new \App\Mail\CustomerOrderConfirmation($order);

                        $sales_emails = env('SALES_EMAILS');
                        $sales_emails = explode('|', $sales_emails);

                        foreach ($sales_emails as $email) {
                            Mail::to($email)->send($sales_email);
                        }

                        Mail::to($customer)->send($custmer_email);
                        
                        $customer_id = $customer->id;

                        $customer_name = $customer->name;

                        $order_price = $order->price;
                        
                        $order_id = $order->order_reference;

                        $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER PLACED', 'message' => 'Hello ' .$customer_name. '! your order of AED' .$order_price.' was successfuly Placed. We will get in touch with you shortly 😊');

                       $this->TriggerPushOrderPlaced($customer_id,$fcm_data);

                        return response()->json([
                            'status' => 1,
                            'message' => 'Your order was successfuly placed'
                        ]);


                }


        } else if($payment_type == 'wallet,card'){

                $customer = Customer::find($order->customer_id);
                $customer_id = $order->customer_id;
                $customer_wallet = $customer->wallet;
                $delivery_charge = getenv("DELIVERY_CHARGE");
                $total = $delivery_charge + $order['price'];
                $to_pay = $total - $customer_wallet;
               // print_r($total);die;

                if($customer_wallet == 0){

                         return response()->json([
                            'status' => 0,
                            'message' => 'Oops! Its look a like that you do not have money in your wallet. Please choose “Pay by Cash” or “Pay by Card” to place your order.',
                        ], 422);


                } else if($customer_wallet > $total){

                        return response()->json([
                            'status' => 0,
                            'message' => 'Ahh! You have only '.$customer_wallet.' AED in your wallet.To pay remaining '.$to_pay.' AED please choose “Pay by Cash” or “Pay by Card” to place your order.',
                        ], 422);

                }else{


                    $payment_request = $this->start_card_payment($order, $request,$customer_wallet);
                   
                    if (!is_null($payment_request)) {

                        DB::beginTransaction();

                        try {

                            Transaction::create([
                                'valucart_order_id' => $order->id,
                                'network_reference' => $payment_request['reference'],
                                'amount' => $payment_request['_embedded']['payment'][0]['amount']['value'] / 100.00,
                                'currency' => $payment_request['_embedded']['payment'][0]['amount']['currencyCode'],
                                'status' => $payment_request['_embedded']['payment'][0]['state'],
                                'network_payment_url' => $payment_request['_links']['payment']['href'],
                                'network_created_at' => Carbon::parse($payment_request['createDateTime'])->format('Y-m-d H:i:s'),
                            ]);

                            Orders::where('id', $order->id)->update([
                                'payment_type' => 'wallet,card',
                                'payment_status' => Orders::CARD_PAYMENT_INITIATED,
                            ]);

                            

                        } catch(Throwable $e) {

                            DB::rollback();
                            throw $e;

                        }

                        DB::commit();

                        return response()->json([
                            'status' => 1,
                            'data' => [
                                'payment_url' => $payment_request['_links']['payment']['href'],
                                'poll_url' => url('/checkout/payment/poll/' . $order->id),
                            ]
                        ]);

                    }

                  }

        }

        else if($payment_type == 'card') {

            $payment_request = $this->start_card_payment($order, $request,$valucredits=null);
            
                
            if (!is_null($payment_request)) {

                DB::beginTransaction();

                try {

                    Transaction::create([
                        'valucart_order_id' => $order->id,
                        'network_reference' => $payment_request['reference'],
                        'amount' => $payment_request['_embedded']['payment'][0]['amount']['value'] / 100.00,
                        'currency' => $payment_request['_embedded']['payment'][0]['amount']['currencyCode'],
                        'status' => $payment_request['_embedded']['payment'][0]['state'],
                        'network_payment_url' => $payment_request['_links']['payment']['href'],
                        'network_created_at' => Carbon::parse($payment_request['createDateTime'])->format('Y-m-d H:i:s'),
                    ]);

                    Orders::where('id', $order->id)->update([
                        'payment_type' => 'card',
                        'payment_status' => Orders::CARD_PAYMENT_INITIATED,
                    ]);

                } catch(Throwable $e) {

                    DB::rollback();
                    throw $e;

                }

                DB::commit();

                return response()->json([
                    'status' => 1,
                    'data' => [
                        'payment_url' => $payment_request['_links']['payment']['href'],
                        'poll_url' => url('/checkout/payment/poll/' . $order->id),
                    ]
                ]);

            }

        }

        return response()->json([
            'status' => 0,
            'message' => 'Something went wrong.',
        ], 422);

    }

    public function verify_payment(Request $request, $order_id)
    {

        $network_refrence = $request->query('ref');
        
        // Verify that the order id is valid
        $order = Orders::find($order_id);
        
        if (!is_null($order)) {
            
            try {

                $access_token = $this->get_network_access_token();
                
                $http_client = $this->get_guzzle_client();
                
                $url = env('NETWORK_TRANSACTIONS_URL') . '/' . env('NETWORK_OUTLET_ID') . '/orders/' . $network_refrence;
                
                $response = $http_client->request('GET', $url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $access_token
                    ],
                ]);

                $customer = Customer::find($order->customer_id);
                $cart_info = \DB::table('carts')->where('id', $order->cart_id)->first();
                
                if ($response->getStatusCode() == 200) {

                    $body = $response->getBody();
                    $body = json_decode($body->getContents(), true);
                    
                    $trasaction_status = $body['_embedded']['payment'][0]['state'];
    
                    if ($trasaction_status == 'FAILED') {
    
                        DB::beginTransaction();
    
                        try {
    
                            Transaction::where('valucart_order_id', $order->id)->update([
                                'status' => $trasaction_status
                            ]);
                            
                            $order->payment_status = Orders::CARD_PAYMENT_FAILED;
                            $order->save();
    
                        } catch(Throwable $e) {
    
                            DB::rollback();
                            throw $e;
    
                        }
                        
                        DB::commit();

                        return 'Payment failed';
    
                    } else if ($trasaction_status == 'CAPTURED') {
        
                        DB::beginTransaction();
    
                        try {
    
                            Transaction::where('valucart_order_id', $order_id)->update([
                                'status' => $trasaction_status
                            ]);
    
                            Orders::where('id', $order_id)->update([
                                'status' => Orders::PLACED,
                                'payment_status' => Orders::CARD_PAYMENT_SUCCESS,
                            ]);
                            
                            // Clear cart
                            Cart::clear_cart($order->cart_id);

                            \DB::table('carts')
                                ->where('id', $order->cart_id)
                                ->update([
                                    'coupon' => null,
                                    'discount' => 0,
                                ]);
                            
                        \DB::table('customer_bundles')
                            ->where('id', $order->cart_id)
                            ->update([
                                'status' => 'checkedout',
                            ]);
                            
                            
                            if($order->payment_type == 'wallet,card'){

                                $customer_id = $order->customer_id;
                                $customer = Customer::find($customer_id);
                                $customer_wallet = $customer->wallet;
                                
                                            DB::table('customers')
                                                ->where('id', $customer_id)
                                                ->update(['wallet' => 0]);

                                            DB::table('wallet_transactions')->insert(
                                                    [
                                                     'customer_id' => $customer_id,
                                                     'description' => 'Purchased Items',
                                                     'transact_amt' => $customer_wallet,
                                                     'type' => 'debit',
                                                     'amt_left' => 0,
                                                     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                                     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                                                 ]
                                            );


                            }

                            if ($cart_info && $cart_info->coupon && $cart_info->discount) {
                                Coupon::record_usage($cart_info->coupon, $customer->id);
                            }

                            $sales_email = new \App\Mail\SalesOrderPlaced($order);
                            $custmer_email = new \App\Mail\CustomerOrderConfirmation($order);

                            $sales_emails = env('SALES_EMAILS');
                            $sales_emails = explode('|', $sales_emails);

                            foreach ($sales_emails as $email) {
                                Mail::to($email)->send($sales_email);
                            }

                        Mail::to($customer)->send($custmer_email);

                        /* === Code edited on august 26 starts here === */

                        $customer_id = $customer->id;

                        $customer_name = $customer->name;

                        $order_price = $order->price;
                        
                        $order_id = $order->order_reference;

                        $fcm_data = array('type' => 'orderstatus','id' => $order_id, 'title' => 'ORDER PLACED', 'message' => 'Hello ' .$customer_name. '! your order of AED' .$order_price.' was successfuly Placed. We will get in touch with you shortly 😊');

                        $this->TriggerPushOrderPlaced($customer_id,$fcm_data);

                        /* === Code edited on august 26 ends here === */


                        } catch(Throwable $e) {
    
                            DB::rollback();
                            throw $e;
    
                        }
                        
                        DB::commit();

                        return 'Payment successful';
                        
                    }
    
                }
    
            } catch (RequestException $e) {
    
                echo Psr7\str($e->getRequest());
    
                if ($e->hasResponse()) {
                    echo Psr7\str($e->getResponse());
                }
            }

        } else {

            if ($action == 'verifygateway') {

                return $message;

            } else if($action == 'pollstatus') {

                return response()->json([
                    'status' => 0,
                    'message' => 'The given order id seems to be invalid, this could be because the url was termpered.',
                ]);

            }

        }

        return response()->json([
            'status' => 0,
            'message' => 'Something went wrong.',
        ], 422);

    }

    public function poll_payment(Request $request, $order_id)
    {

        $transaction = Transaction::where('valucart_order_id', $order_id)->first('status');

        if (is_null($transaction)) {

            return response()->json([
                'status' => 0,
                'message' => 'Request could not be processed, this could be because the url was termpered.',
            ], 422);

        }
        
        $trasaction_status = $transaction->status;

        return response()->json([
            'status' => ($trasaction_status == 'CAPTURED' || $trasaction_status == 'FAILED') ? 1 : 0,
            'message' => $trasaction_status,
        ]);

    }

    protected function start_card_payment($order, $request,$valucredits)
    {  
        $order_snapshoot = unserialize($order->snapshots);

        $access_token = $this->get_network_access_token();

        $http_client = $this->get_guzzle_client();

        $url = env('NETWORK_TRANSACTIONS_URL') . '/' . env('NETWORK_OUTLET_ID') . '/orders';

        $origin_server = $request->header('Origin');

        $redirect_host = env('APP_URL');

        if ($origin_server) {
                $redirect_host = $origin_server;
        }

        try {
            
            if($valucredits) {

               $amount = ($order_snapshoot["total"] - $valucredits) * 100;

            }else{

                $amount = $order_snapshoot["total"] * 100;

            }
            
           // $amount = $order_snapshoot["total"] * 100;

            $response = $http_client->request('POST', $url, [
                
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                    'Accept' => 'application/vnd.ni-payment.v2+json',
                    'Content-Type' => 'application/vnd.ni-payment.v2+json',
                ],
    
                'json' => [
                    'action' => 'SALE',
                    'amount' => [
                        'currencyCode' => 'AED',
                        'value' => (int) $amount,
                    ],
                    'language' => 'en',
                    'description' => $order_snapshoot['reference'],
                    'merchantAttributes' => [
                        'redirectUrl' => $redirect_host . '/checkout/payment/verify/' . $order->id,
                    ],
                    'emailAddress' => $order_snapshoot['customer']['email']
                ],
    
            ]);
    
            if ($response->getStatusCode() == 201) {

                $body = $response->getBody();
                return json_decode($body->getContents(), true);

            }

        } catch (RequestException $e) {

            echo Psr7\str($e->getRequest());

            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }

        }

        return null;

    }

    private function get_network_access_token()
    {
        
        $http_client = $this->get_guzzle_client();
            
        try {
            
            $url = env('NETWORK_OAUTH_URL');
            $api_key = env('NETWORK_API_KEY');

            $response = $http_client->request('POST', $url, [
                
                'headers' => [
                    'Authorization' => 'Basic ' . $api_key,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],

                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],

            ]);

            if ($response->getStatusCode() == 200) {

                $body = $response->getBody();

                return json_decode($body->getContents(), true)['access_token'];
    
            }

        } catch (RequestException $e) {

            echo Psr7\str($e->getRequest());

            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }

        }

        return null;

    }

    private function get_guzzle_client()
    {

        if ($this->guzzle_client instanceof Guzzle) {
            return $this->guzzle_client;
        }

        return $this->guzzle_client = new Guzzle;

    }





    public function TriggerPushOrderPlaced($customer_id,$fcm_data){
            
        $key = 'AAAAzB0NRgw:APA91bE8BFXH7biQ9KBfEZkW1qLMM4liVPPkDwVt9pM8Zva4HG5IVLqi6yC6Wx80ZBZnVN12vH-Un8xHRU0rSjY95uk4hFI58MwgkEoJlO3Fo_d7h_rQcqfOO5Althay_RleII_iuF_o';

        $users = DB::table('fcmtokens')->where('customer_id',$customer_id)->distinct()->get('fcm_token');

               
        foreach ($users as $row){


                    $data = "
                          { 
                            \"data\" :
                                    {  
                                         \"order_id\" : \"".$fcm_data['id']."\",
                                         \"type\" : \"".$fcm_data['type']."\",
                                         \"title\" : \"".$fcm_data['title']."\",
                                         \"message\" : \"".$fcm_data['message']."\",
                                        
                                         },
                            \"to\" : \"".$row->fcm_token."\"
                          }
                      ";

                    $ch = curl_init("https://fcm.googleapis.com/fcm/send");

                    $header = array('Content-Type: application/json', 'Authorization: key='.$key);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    curl_setopt($ch, CURLOPT_POST, 1);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                    $out = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $res = curl_exec($ch);
               
             }
            

    }







}
