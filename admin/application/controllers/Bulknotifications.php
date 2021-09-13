<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bulknotifications extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Bulknotifications_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }





    /* === Send Bulknotifications === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['customers'] = $this->db->get('customers')->result();
        $template['page'] = 'Bulknotifications/notification_create';
        $template['page_title'] = "Create Bulknotifications";
        $template['page_data'] = $this->info;
        if($_POST) {
            $data = $_POST;

            $log = array(
                         'id' =>1,
                         'log' => 'Send Push Notification '.$data['title']. ''
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);

            $android = $this->db->distinct('fcm_token')->select('fcm_token')->where('device_type', 'android')->get('fcmtokens')->num_rows();

            $ios = $this->db->distinct('fcm_token')->select('fcm_token')->where('device_type', 'ios')->get('fcmtokens')->num_rows();
            
            $this->session->set_flashdata('message', array('message' => 'Notification Sent successfully to Android Devices: '.$android.' and Ios Devices: '.$ios.' ','class' => 'success'));

            $this->TriggerPush($data);
            
            redirect(base_url().'Bulknotifications');
            
        }
        $this->load->view('template', $template);
    }



function TriggerPush($data){

           
            if($data['recipients'] == 'N'){
               
                if(file_exists($_FILES['image']['tmp_name']) || is_uploaded_file($_FILES['image']['tmp_name'])) {
                
                 
                 $image = $this->upload_image($_FILES);
                    
                    $img_url = 'http://v2api.valucart.com/img/notification_images/'.$_FILES['image']['name'];
                   
                    $pushdata = array(
                                    'type' => 'orderstatus' ,
                                    'id' => '1',
                                    'title' => $data['title'],
                                    'message' =>$data['message'],
                                    'image' => $img_url,
                                );


                }else{

                    $pushdata = array(
                                    'type' => 'bulk' ,
                                    'id' => '1',
                                    'title' => $data['title'],
                                    'message' =>$data['message']
                                );
                }

                $this->sendpushall($pushdata);

               
            } else{

                if(file_exists($_FILES['image']['tmp_name']) || is_uploaded_file($_FILES['image']['tmp_name'])) {
                   
                    $image = $this->upload_image($_FILES);
                    
                    $img_url = 'http://v2api.valucart.com/img/notification_images/'.$_FILES['image']['name'];
                  
                    $pushdata = array(
                                    'type' => 'orderstatus' ,
                                    'id' => '1',
                                    'title' => $data['title'],
                                    'message' =>$data['message'],
                                    'image' => $img_url,
                                );


                }else{

                    $pushdata = array(
                                    'type' => 'orderstatus' ,
                                    'id' => '1',
                                    'title' => $data['title'],
                                    'message' =>$data['message']
                                );
                }

                $this->sendpush_specific($pushdata,$data);



    }


}


//send push to all customers

function sendpushall($data){
        
        $query = $this->db->where('id', '1');
        $query = $this->db->get('system_settings');
        $result = $query->row();

        $key = $result->firebase_key;

        if($data['image']){
            

       
            $android_users = "SELECT DISTINCT(fcm_token) AS tokens FROM fcmtokens WHERE device_type = 'android'  LIMIT 1000;";

            $query = $this->db->query($android_users);

            $result = $query->result();
            $registration_ids = array_column($result, 'tokens');


            $fcmMsg = array(
                'message' => $data['message'],
                'title' => $data['title'],
                'type' => $data['type'],
                'image' => $data['image'],
                'sound' => "default",
                   // 'color' => "#203E78" 
            );

            $fcmFields = array(
                'registration_ids' => $registration_ids,
                'priority' => 'high',
                'data' => $fcmMsg
            );



            $jsondata = json_encode($fcmFields);

            $headers = array(
                'Authorization: key=' . $key,
                'Content-Type: application/json'
            );


             
            $ch = curl_init();

            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );

            curl_setopt( $ch,CURLOPT_POST, true );

            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );

            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

            curl_setopt( $ch,CURLOPT_POSTFIELDS, $jsondata );

            curl_exec($ch );

            curl_close( $ch );


            $ios_users = "SELECT DISTINCT(fcm_token) AS tokens FROM fcmtokens WHERE device_type = 'ios'  LIMIT 1000;";

            $query = $this->db->query($ios_users);

            $result = $query->result();

            $registration_ids = array_column($result, 'tokens');

            $url = "https://fcm.googleapis.com/fcm/send";

            $title = $data['title'];

            $body = $data['message'];

            $image = $data['image'];
            // $image = 'http://v2api.valucart.com/img/banners/home_banners/0570299023c8-landscape.jpeg';

            $notification = array(
                                'title' =>$title ,
                                'text' => $body,
                                'sound' => 'default', 
                                'badge' => '1',
                                'image' => $image
                            );

            $arrayToSend = array(
                                'registration_ids' => $registration_ids,
                                 'notification' => $notification,
                                 'priority'=>'high');

            $json = json_encode($arrayToSend);

            $headers = array();

            $headers[] = 'Content-Type: application/json';

            $headers[] = 'Authorization: key='. $key;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");

            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

            curl_exec($ch);
   
            curl_close($ch);
           

            //update db

            $triggered_users = "SELECT GROUP_CONCAT(DISTINCT(customer_id), '') AS id FROM fcmtokens;";

            $query = $this->db->query($triggered_users);

            $result = $query->row();

            $ids = $result->id;

            $created_at = date("Y-m-d H:i:s");

            $data = array( 'type' => 'bulk',
                           'title' => $title,
                           'message' => $body,
                           'customers' => $ids,
                           'created_at' => $created_at,
                           'updated_at' => $created_at

                         );
            
            $this->db->insert('notifications', $data);


                
        }else{


            $android_users = "SELECT DISTINCT(fcm_token) AS tokens FROM fcmtokens WHERE device_type = 'android' LIMIT 1000;";

            $query = $this->db->query($android_users);

            $result = $query->result();
            
            $registration_ids = array_column($result, 'tokens');
            

            $fcmMsg = array(
                'message' => $data['message'],
                'title' => $data['title'],
                'type' => $data['type'],
                'sound' => "default",
                   // 'color' => "#203E78" 
            );

            $fcmFields = array(
                'registration_ids' => $registration_ids,
                'priority' => 'high',
                'data' => $fcmMsg
            );
            $jsondata = json_encode($fcmFields);

            $headers = array(
                'Authorization: key=' . $key,
                'Content-Type: application/json'
            );
             
            $ch = curl_init();

            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );

            curl_setopt( $ch,CURLOPT_POST, true );

            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );

            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

            curl_setopt( $ch,CURLOPT_POSTFIELDS, $jsondata );

            $res = curl_exec($ch );
            //echo "<pre>";
            print_r($res);die;
         
            curl_close( $ch );

            $ios_users = "SELECT DISTINCT(fcm_token) AS tokens FROM fcmtokens WHERE device_type = 'ios'  LIMIT 1000;";

            $query = $this->db->query($ios_users);

            $result = $query->result();

            $registration_ids = array_column($result, 'tokens');

           

            $url = "https://fcm.googleapis.com/fcm/send";

            $title = $data['title'];

            $body = $data['message'];

            $image = 'http://v2api.valucart.com/img/banners/home_banners/0570299023c8-landscape.jpeg';

            $notification = array(
                                'title' =>$title ,
                                'text' => $body,
                                'sound' => 'default', 
                                'badge' => '1',
                                'image' => $image
                            );

            $arrayToSend = array(
                                'registration_ids' => $registration_ids,
                                 'notification' => $notification,
                                 'priority'=>'high');

            $json = json_encode($arrayToSend);

            $headers = array();

            $headers[] = 'Content-Type: application/json';

            $headers[] = 'Authorization: key='. $key;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");

            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

            curl_exec($ch);
   
            curl_close($ch);

            //update db

            $triggered_users = "SELECT GROUP_CONCAT(DISTINCT(customer_id), '') AS id FROM fcmtokens;";

            $query = $this->db->query($triggered_users);

            $result = $query->row();

            $ids = $result->id;

            $created_at = date("Y-m-d H:i:s");

            $data = array( 'type' => 'bulk',
                           'title' => $title,
                           'message' => $body,
                           'customers' => $ids,
                           'created_at' => $created_at,
                           'updated_at' => $created_at

                         );

            $this->db->insert('notifications', $data);
           

        }

}







    //send push to specific customers

    function sendpush_specific($data,$cust){
        
        $query = $this->db->where('id', '1');
        $query = $this->db->get('system_settings');
        $result = $query->row();

        $key = $result->firebase_key;

        if(isset($data['image'])){

            foreach ($cust['customers'] as $select){

                $variable = "," .$select;

                $var=explode(',',$variable);

                 foreach($var as $id){

                        $this->db->where('customer_id',$id);
                        $this->db->select('fcm_token');
                        $this->db->distinct('fcm_token');
                        $query = $this->db->get('fcmtokens');


                        foreach ($query->result() as $row)
                        {

                        $registrationIDs = explode (",", $row->fcm_token);  
                       
                        $image = 'http://v2api.valucart.com/img/banners/home_banners/0570299023c8-landscape.jpeg';

                        $fcmMsg = array(
                            'message' => $data['message'],
                            'title' => $data['title'],
                            'type' => $data['type'],
                            'image' => $data['image'],
                            'sound' => "default",
                                'color' => "#6610f2" 
                        );

                        $fcmFields = array(
                            'registration_ids' => $registrationIDs,
                            'priority' => 'high',
                            'data' => $fcmMsg
                        );

                        $jsondata = json_encode($fcmFields);

                        $headers = array(
                            'Authorization: key=' . $key,
                            'Content-Type: application/json'
                        );
                         
                        $ch = curl_init();

                        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );

                        curl_setopt( $ch,CURLOPT_POST, true );

                        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );

                        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

                        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

                        curl_setopt( $ch,CURLOPT_POSTFIELDS, $jsondata );

                        $result = curl_exec($ch );

                        curl_close( $ch );
                       
                     }


                 }

            }


        }

        else{
           
                foreach ($cust['customers'] as $select){

                        $variable = "," .$select;

                        $var=explode(',',$variable);

                        foreach($var as $id){

                            $this->db->where('customer_id',$id);
                            $this->db->where('device_type','android');
                            $this->db->select('fcm_token');
                            $this->db->distinct('fcm_token');
                            $query = $this->db->get('fcmtokens');

                            foreach ($query->result() as $row){

                                $registrationIDs = explode (",", $row->fcm_token); 

                                $fcmMsg = array(
                                    'message' => $data['message'],
                                    'title' => $data['title'],
                                    'type' => $data['type'],
                                    'sound' => "default",
                                       // 'color' => "#203E78" 
                                );

                                $fcmFields = array(
                                    'registration_ids' => $registrationIDs,
                                    'priority' => 'high',
                                    'data' => $fcmMsg
                                );
                                $jsondata = json_encode($fcmFields);

                                $headers = array(
                                    'Authorization: key=' . $key,
                                    'Content-Type: application/json'
                                );
                                 
                                $ch = curl_init();

                                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );

                                curl_setopt( $ch,CURLOPT_POST, true );

                                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );

                                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

                                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

                                curl_setopt( $ch,CURLOPT_POSTFIELDS, $jsondata );

                                $result = curl_exec($ch );

                                curl_close( $ch );
                        
                            }

                        }


                        foreach($var as $id){

                            $this->db->where('customer_id',$id);
                            $this->db->where('device_type','ios');
                            $this->db->select('fcm_token');
                            $this->db->distinct('fcm_token');
                            $query = $this->db->get('fcmtokens');

                            foreach ($query->result() as $row){

                                $registrationIDs = explode (",", $row->fcm_token); 

                                $url = "https://fcm.googleapis.com/fcm/send";

                                $title = $data['title'];

                                $body = $data['message'];

                                $image = 'http://v2api.valucart.com/img/banners/home_banners/0570299023c8-landscape.jpeg';

                                $notification = array(
                                                    'title' =>$title ,
                                                    'text' => $body,
                                                    'sound' => 'default', 
                                                    'badge' => '1',
                                                    'image' => $image
                                                );

                                $arrayToSend = array(
                                                    'registration_ids' => $registrationIDs,
                                                     'notification' => $notification,
                                                     'priority'=>'high');

                                $json = json_encode($arrayToSend);

                                $headers = array();

                                $headers[] = 'Content-Type: application/json';

                                $headers[] = 'Authorization: key='. $key;

                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL, $url);

                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");

                                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

                                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

                                $response = curl_exec($ch);
                       
                                curl_close($ch);

                            }

                        }



                }

         }

}       





//upload images


    function upload_image($FILES){

        

       if(isset($_FILES['image']['tmp_name'])){

        $ch =curl_init();

        $cfile = new CURLfile($_FILES['image']['tmp_name'],$_FILES['image']['type'],$_FILES['image']['name']);

         $data = array("image"=>$cfile);

           $url = "http://v2api.valucart.com/notificationimage";


          // print_r($url);die;
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

           $response = curl_exec($ch);

       
       }


    
}


function sendpush(){

            $triggered_users = "SELECT GROUP_CONCAT(DISTINCT(fcm_token), '') AS id FROM fcmtokens;";

            $query = $this->db->query($triggered_users);

            $result = $query->row();

            $device_id = $result->id;

            $registrationIDs = explode (",", $device_id);  


    $singleID = 'dA6Fgj7d_3Y:APA91bHe5zby4kYoKyKdqNZ8RqlpjLoJ6YKuJJCHfI2ZRnw_kGyFI_UAEoNhhbEZ9Bn-6iBRS_kUEHQFq8mIvImg2wjMxVf-UTWwjQLBOmT_73GWVuIQChOFXmr6P0K4MNF_tDskWTGY' ; 

//     $registrationIDs = array(
//          'dA6Fgj7d_3Y:APA91bHe5zby4kYoKyKdqNZ8RqlpjLoJ6YKuJJCHfI2ZRnw_kGyFI_UAEoNhhbEZ9Bn-6iBRS_kUEHQFq8mIvImg2wjMxVf-UTWwjQLBOmT_73GWVuIQChOFXmr6P0K4MNF_tDskWTGY', 
//          'eA7ouZkvQgQ:APA91bHpuJDdM5tnIwaIJpWW22jJ2_yBenAUPHUbbDxXYOrF9n5MHtaFMy9JH5_2mXb00W7eoKBo7L6zqSd5-T-LepTJ59o2grzNeUJjQfNSkEov_PIYHOFwBPzS5rjFnCYAPT-yW_d5',
         
//     ) ;
//     echo "<pre>";
// print_r($registrationIDs);die;
    // prep the bundle
    // to see all the options for FCM to/notification payload: 
    // https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support 

    // 'vibrate' available in GCM, but not in FCM
    $fcmMsg = array(
        'body' => 'here is a message. message',
        'title' => 'This is title #1',
        'sound' => "default",
            'color' => "#203E78" 
    );
    // I haven't figured 'color' out yet.  
    // On one phone 'color' was the background color behind the actual app icon.  (ie Samsung Galaxy S5)
    // On another phone, it was the color of the app icon. (ie: LG K20 Plush)

    // 'to' => $singleID ;  // expecting a single ID
    // 'registration_ids' => $registrationIDs ;  // expects an array of ids
    // 'priority' => 'high' ; // options are normal and high, if not set, defaults to high.
    $fcmFields = array(
        'registration_ids' => $registrationIDs,
            'priority' => 'high',
        'notification' => $fcmMsg
    );

    $key = 'AAAAzB0NRgw:APA91bE8BFXH7biQ9KBfEZkW1qLMM4liVPPkDwVt9pM8Zva4HG5IVLqi6yC6Wx80ZBZnVN12vH-Un8xHRU0rSjY95uk4hFI58MwgkEoJlO3Fo_d7h_rQcqfOO5Althay_RleII_iuF_o';

    $headers = array(
        'Authorization: key=' . $key,
        'Content-Type: application/json'
    );
     
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    echo $result . "\n\n";die;



}











} 
