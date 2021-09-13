<?php defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Areas extends CI_Controller {
        /**
         * @author Praneeth Nidarshan
         * @see git@gist.github.com:8d54499e903d35155af6.git
         */







        public function index()
        {
           
            $sender_email = "someone@gmail.com";
            $user_password = "password";
            $username = "Howdy";
            $receiver_email = "hody@gmail.com";
            $subject = "Sample";

            $html =  file_get_contents("assets/uploads/email/null.html");
            $text = str_replace('"', "'", $html);



            $message = ".$text.";
          
            $this->emailConfig();
            // Sender email address
            $this->email->from($sender_email, $username);
            // Receiver email address.for single email
            $this->email->to($receiver_email);
            //send multiple email
            $this->email->to("mailtomedonantony@gmail.com");
            // Subject of email
            $this->email->subject($subject);
            // Message in email
            $this->email->message($message);
            
            $this->email->attach('https://www.antennahouse.com/XSLsample/pdf/sample-link_1.pdf');
            // It returns boolean TRUE or FALSE based on success or failure
            
            $mail = ($this->email->send()) ? "Sent" : "Failed" ;
            $rs =$this->email->print_debugger();
            print_r($rs);
          echo $mail;
        }
        
        /**
         * Email Configurations
         * ** Please deactivate Second-step verification for the smtp_user **
         */
        private function emailConfig()
        {
            $config = array(
                'protocol'  => 'smtp' , 
                'smtp_crypto' => 'tls',
                'smtp_host' => 'smtp.gmail.com' , 
                'smtp_port' => 587, 
                'smtp_user' => 'noreply@valucart.com' ,
                'smtp_pass' => 'noreply@VC',
                'mailtype'  => 'html', 
                'charset'   => 'utf-8', 
                'newline'   => "\r\n",  
                'wordwrap'  => TRUE 
                );
            
            // Load email library and passing configured values to email library
            $this->load->library('email',$config);
        }





        //

        function sendpush(){
            $data = [
                            "to" => 'dA6Fgj7d_3Y:APA91bHe5zby4kYoKyKdqNZ8RqlpjLoJ6YKuJJCHfI2ZRnw_kGyFI_UAEoNhhbEZ9Bn-6iBRS_kUEHQFq8mIvImg2wjMxVf-UTWwjQLBOmT_73GWVuIQChOFXmr6P0K4MNF_tDskWTGY',
                            "notification" => [
                                "body" => "SOMETHING",
                                "title" => "SOMETHING",
                                "icon" => "ic_launcher"
                            ],
                            "data" => [
                                "ANYTHING EXTRA HERE"
                            ]
                        ];
          //  $data = json_encode($json_data);

            //FCM API end-point
            $url = 'https://fcm.googleapis.com/fcm/send';
            //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            $server_key = 'AAAAzB0NRgw:APA91bE8BFXH7biQ9KBfEZkW1qLMM4liVPPkDwVt9pM8Zva4HG5IVLqi6yC6Wx80ZBZnVN12vH-Un8xHRU0rSjY95uk4hFI58MwgkEoJlO3Fo_d7h_rQcqfOO5Althay_RleII_iuF_o';
            //header with content_type api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$server_key
            );
            //CURL request to route notification to FCM connection server (provided by Google)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            print_r($result);die;
            if ($result === FALSE) {
                die('Oops! FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);


        }

        //
    }
?>