<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mailbox extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        //$this->load->model('Settlement_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }



    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Mailbox/mailbox_view';
        $template['page_title'] = "View Mailbox";
        $template['page_data'] = $this->info;
        $template['customers'] = $this->db->get('customers')->result();
        if($_POST) {
            $data = $_POST;
           
            if($data['recipients'] == 'N'){

                $result = $this->SendmailToAll($data);

                if($result == "Sent") {

                $this->session->set_flashdata('message', array('message' => 'Email Sent Successfully','class' => 'success'));
                }
                else {  
                    
                    $this->session->set_flashdata('message', array('message' => 'Email Not Sent','class' => 'danger'));
                }

            redirect(base_url().'Mailbox');

            }else{
               
                 $result = $this->SendmailToSpecific($data);

                if($result == "Sent") {

                $this->session->set_flashdata('message', array('message' => 'Email Sent Successfully','class' => 'success'));
                }
                else {  
                    
                    $this->session->set_flashdata('message', array('message' => 'Email Not Sent','class' => 'danger'));
                }

            redirect(base_url().'Mailbox');


            }

        }

        $this->load->view('template',$template);  

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





//Send mail to all

    public function SendmailToAll($data)
        {  

            if(file_exists($_FILES['user_files']['tmp_name'][0]) || is_uploaded_file($_FILES['user_files']['tmp_name'][0])) {

            ini_set('post_max_size', '64M');
            ini_set('upload_max_filesize', '64M');
        //    $imgdata = array();

            if (count($_FILES["user_files"]) > 0) {
                $folderName = "assets/uploads/customers/";
                $counter = 0;

                for ($i = 0; $i < count($_FILES["user_files"]["name"]); $i++) {

                    if ($_FILES["user_files"]["name"][$i] <> "") {

                        $ext = strtolower(end(explode(".", $_FILES["user_files"]["name"][$i])));
                        $filePath = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;
                        $data['filenames'][] = 'http://testing.v2.admin.valucart.com/'.$filePath;
                        if (!move_uploaded_file($_FILES["user_files"]["tmp_name"][$i], $filePath)) {
                            $msg .= "Failed to upload" . $_FILES["user_files"]["name"][$i] . ". <br>";
                            $counter++;
                        }
                    }
                }

                //$msg = ($counter == 0) ? successMessage("Files uploaded Successfully") : errorMessage($msg);
            }else{
                echo "error";die;
            }

        }


            $db_data = "SELECT GROUP_CONCAT(email) AS emails FROM customers";
            $db_query = $this->db->query($db_data);
            $db_query =  $db_query->row();
            $emails = $db_query->emails;

            $olddb_data = "SELECT GROUP_CONCAT(email) AS emails FROM valucartoldusers";
            $olddb_query = $this->db->query($olddb_data);
            $olddb_query =  $olddb_query->row();
            $oldemails = $olddb_query->emails;
           
            $emails = $oldemails.','.$emails;

            $sender_email = "support@valucart.com";
            $user_password = "password";
            $username = "Valucart";

            $subject = $data['subject'];
            $message = $data['message'];

            $myArray = explode(',', $emails);


                foreach ($myArray as $email){
                           
                            $this->emailConfig();

                            $this->email->from($sender_email, $username);

                            $this->email->to($email);

                            $this->email->subject($subject);

                            $this->email->message($message);

                                if(isset($data['filenames'])){

                                    $list = $data['filenames'];

                                    foreach ($list as $attachment){

                                        $this->email->attach($attachment);

                                    }
                                    
                                }

                       // $this->email->attach($_FILES['user_files']['name'][0]);

                        $mail = ($this->email->send()) ? "Sent" : "Failed" ;

                        $rs =$this->email->print_debugger();

                }


            return $mail;


        }


//Send mail to specific customers

    public function SendmailToSpecific($data)
        {  

            if(file_exists($_FILES['user_files']['tmp_name'][0]) || is_uploaded_file($_FILES['user_files']['tmp_name'][0])) {

            ini_set('post_max_size', '64M');
            ini_set('upload_max_filesize', '64M');
        //    $imgdata = array();

            if (count($_FILES["user_files"]) > 0) {
                $folderName = "assets/uploads/customers/";
                $counter = 0;

                for ($i = 0; $i < count($_FILES["user_files"]["name"]); $i++) {

                    if ($_FILES["user_files"]["name"][$i] <> "") {

                        $ext = strtolower(end(explode(".", $_FILES["user_files"]["name"][$i])));
                        $filePath = $folderName . rand(10000, 990000) . '_' . time() . '.' . $ext;
                        $data['filenames'][] = 'http://testing.v2.admin.valucart.com/'.$filePath;
                        if (!move_uploaded_file($_FILES["user_files"]["tmp_name"][$i], $filePath)) {
                            $msg .= "Failed to upload" . $_FILES["user_files"]["name"][$i] . ". <br>";
                            $counter++;
                        }
                    }
                }

         //     $msg = ($counter == 0) ? successMessage("Files uploaded Successfully") : errorMessage($msg);
         //            print_r($msg);
         // die;
            }else{
                echo "error";die;
            }

        }


            $db_data = "SELECT GROUP_CONCAT(email) AS emails FROM customers";
            $db_query = $this->db->query($db_data);
            $db_query =  $db_query->row();
            $emails = $db_query->emails;


            $sender_email = "support@valucart.com";
            $user_password = "password";
            $username = "Valucart";

            $subject = $data['subject'];

           // $message = "'".$data['message']."'";

            $message = str_replace('"', "'", $data['message']);
            print_r($message);die;
            $emails = $data['customers'];

            foreach ($emails as $email){

                    $this->emailConfig();

                    $this->email->from($sender_email, $username);

                    $this->email->subject($subject);

                    $this->email->message($message);

                    $this->email->to($email);

                                    if(isset($data['filenames'])){

                                        $list = $data['filenames'];

                                        foreach ($list as $attachment){

                                            $this->email->attach($attachment);

                                        }
                                        
                                    }

                    $mail = ($this->email->send()) ? "Sent" : "Failed" ;

                    $rs =$this->email->print_debugger();
                    //print_r($mail);die;
                }
                
        
            return $mail;


        }



//test email


    public function Sendmail($data)
        {   
            $sender_email = "support@valucart.com";
            $user_password = "password";
            $username = "Valucart";
            $receiver_email = "mailtomedonantony@gmail.com";


            $subject = $data['subject'];
            $message = $data['message'];
            
            $this->emailConfig();
            // Sender email address
            $this->email->from($sender_email, $username);
            // Receiver email address.for single email
            $this->email->to($receiver_email);
            //send multiple email
            $this->email->to($receiver_email);
            // Subject of email
            $this->email->subject($subject);
            // Message in email
            $this->email->message($message);
            
            $this->email->attach('https://www.antennahouse.com/XSLsample/pdf/sample-link_1.pdf');
            // It returns boolean TRUE or FALSE based on success or failure
            
            $mail = ($this->email->send()) ? "Sent" : "Failed" ;
            $rs =$this->email->print_debugger();
          //  print_r($rs);
         // echo $mail;die;
        }




//






























    


} 
