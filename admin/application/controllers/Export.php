<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Export extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Export_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    public function index_soldproducts() {

        $type = 'ON-APRIL';
        $this->load->helper('csv');
        $export_arr = array();
        $emp_details = $this->Export_model->get_products();
       
        $title = array(  "SKU",
                         "NAME",
                         "DEPARTMENT",
                         "CATEGORY",
                         "SUBCATEGORY",
                         "BRAND",
                         "VENDOR",
                         "PACKAGING",
                         "MSP",
                         "VALUCART PRICE",
                        // "SOLD_QUANTITY"
                        // "ORDER REFERENCE",
                        // "PLACED DATE",
                         
                    );

        // $title = array( 
        //                  "NAME",
        //                  "MSP",
        //                  "VALUCART PRICE",
        //                  "QTY",
        //                  "ORDER REFERENCE",
        //                  "PLACED DATE",
                         
        //             );

        array_push($export_arr, $title);
        if (!empty($emp_details)) {
            foreach ($emp_details as $emp) {
                
                array_push($export_arr, array( $emp->sku,
                                               $emp->name,
                                               $emp->department,
                                               $emp->category,
                                               $emp->subcategory,
                                               $emp->brand,
                                               $emp->vendor,
                                               $emp->packaging,
                                               $emp->maximum_selling_price,
                                               $emp->valucart_price,
                                             //  $emp->quantity,
                                              // $emp->order_reference,
                                              // $emp->order_placed,

                                             
                                             
                                    ));

                 // array_push($export_arr, array( 
                 //                               $emp->name,
                 //                               $emp->maximum_selling_price,
                 //                               $emp->valucart_price,
                 //                               $emp->quantity,
                 //                               $emp->order_reference,
                 //                               $emp->order_placed,

                                             
                                             
                 //                    ));

            }
        }
        convert_to_csv($export_arr, 'PRODUCTS_DELIVERED-'.$type.'-' .date('F d Y') . '.csv', ',');
    }



    public function index_fcm(){


                $data = "SELECT DISTINCT(fcm_token) AS tokens ,customer_id,device_type  FROM fcmtokens WHERE device_type = 'ios'";

        $result = $this->db->query($data)->result();

        foreach ($result as $customer) {

            $customer_id = $customer->customer_id;
            $device_type = $customer->device_type;
            $fcm_token = $customer->tokens;

        $key = 'AAAAzB0NRgw:APA91bE8BFXH7biQ9KBfEZkW1qLMM4liVPPkDwVt9pM8Zva4HG5IVLqi6yC6Wx80ZBZnVN12vH-Un8xHRU0rSjY95uk4hFI58MwgkEoJlO3Fo_d7h_rQcqfOO5Althay_RleII_iuF_o';



            $title = 'Quick offers';
            $user_message = 'Home delivery for 
                Red onions only 2.95/ kg
                Cabbage 2.95/per kg 😱
                Facial tissues 5pcs only 12.5/- 💃
                Al Rawdah fresh sausages 3x400g -13.5/-only 📣
                Hurry offer till tuesday 5pm  and many more ....';

            $fcm_data = array('id' => 1, 'title' => $title, 'message' => $user_message);

            $data = "{ \"notification\": { \"title\": \"".$fcm_data['title']."\", \"text\": \"".$fcm_data['message']."\", \"sound\": \"default\" }, \"time_to_live\": 60, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"order_id\" : \"".$fcm_data['id']."\", \"order_status\" : 0}}}, \"collapse_key\" : \"order\", \"priority\":\"high\", \"to\" : \"".$fcm_token."\"}";


            $ch = curl_init("https://fcm.googleapis.com/fcm/send");

            $header = array('Content-Type: application/json', 'Authorization: key='.$key);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $out = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
            $result = curl_exec($ch);


            $json_data = json_decode($result, true);

        if($json_data['success'] == '1'){
            
                    $rs = array(
                       
                        'customer_id' =>$customer_id,
                        'fcm_token' =>$fcm_token,
                        'device_type' =>$device_type,
                        
                        );
            $this->db->insert('fcm', $rs); 

        }else{
            echo $fcm_token;echo ",";
        }





    }



    }


    // export YGAG

    public function index_wallet() {
        $type = 'WalletDetails';
        $this->load->helper('csv');
        $export_arr = array();
        $emp_details = $this->Export_model->get_wallettransaction();
        $title = array(  "CUSTOMER",
                         "CUSTOMER EMAIL",
                         "TRANSACTION AMOUNT",
                         "REDEEMED DATE",
                         
                    );
        array_push($export_arr, $title);
        if (!empty($emp_details)) {
            foreach ($emp_details as $emp) {
                array_push($export_arr, array( get_customer_name($emp->customer_id),
                                               get_customer_email($emp->customer_id),
                                               $emp->transact_amt,
                                               $emp->created_at,

                                    ));
            }
        }
        convert_to_csv($export_arr, 'Exported-'.$type.'-' .date('F d Y') . '.csv', ',');
    }



   // public function export_csv() {
    public function index() {
        $this->load->helper('csv');
        $export_arr = array();
        $type = 'Products';
        $emp_details = $this->Export_model->get_products();
        $title = array("coupon",
                        // "NAME",
                        // "DEPARTMENT",
                        //  "CATEGORY",
                        //  "SUBCATEGORY",
                        //  "BRAND",
                        //  "PACKAGING QUANTITY",
                        //  "PACKAGING QUANTITY UNIT",
                        //  "VALUCART PRICE",
                        //  "MSP",
                        //  "MINIMUM INVENTORY",
                        //  "VENDOR"
                    );
        array_push($export_arr, $title);
        if (!empty($emp_details)) {
            foreach ($emp_details as $emp) {
                array_push($export_arr, array($emp->coupon,
                                             //  $emp->name,
                                             //  get_department_name($emp->department_id),
                                             //  get_category_name($emp->category_id),
                                             //  get_subcategory_name($emp->subcategory_id),
                                             //  get_brand_name($emp->brand_id),
                                             //   $emp->packaging_quantity,
                                             //  get_package_unit($emp->packaging_quantity_unit_id),
                                             // $emp->valucart_price,
                                             // $emp->maximum_selling_price,
                                             // $emp->minimum_inventory,
                                             // get_vendor_name($emp->id),
                                             
                                    ));
            }
        }
        convert_to_csv($export_arr, 'UnPublished-'.$type.'-' .date('F d Y') . '.csv', ',');
    }












} 
