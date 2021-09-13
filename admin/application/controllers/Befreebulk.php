<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Befreebulk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
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
        $template['page'] = 'Befreebulk/befreeemail';
        $template['page_title'] = "View Mailbox";
        $template['page_data'] = $this->info;
        $template['customers'] = $this->db->get('customers')->result();
        if($_POST) {
            $data = $_POST;
            
            if(!empty($_FILES['file']['name'])){ 


               $file_name = $_FILES['file']['name'];  
               $array = explode(".", $file_name);  
               $name = $array[0];  
               $ext = $array[1];  

               if($ext == 'zip')  
               {  
                    $path = 'assets/uploads/email/';  
                    $location = $path . $file_name;  
                   
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $location))  
                    {  

                         $zip = new ZipArchive;  
                         if($zip->open($location))  
                         {  
                              $zip->extractTo($path);  
                              $zip->close();  
                         }  
                    
                    }  

               } else{

                $this->session->set_flashdata('message', array('message' => 'Please upload a zip file','class' => 'danger'));

               }



            }else{

                $this->session->set_flashdata('message', array('message' => 'File Not Found','class' => 'danger'));
            }


            $files = array();

                foreach (glob("assets/uploads/email/*.html") as $file) {

                      $files[] = $file;
                }
            
            $htmlfile_name = $files[0];




            if($data['recipients'] == 'N'){

                $result = $this->SendmailToAll($data,$htmlfile_name);

                if($result == "Sent") {

                $this->session->set_flashdata('message', array('message' => 'Email Sent Successfully','class' => 'success'));
                }
                else {  
                    
                    $this->session->set_flashdata('message', array('message' => 'Email Not Sent','class' => 'danger'));
                }

            redirect(base_url().'Befreebulk');

            }else{
              
                 $result = $this->SendmailToSpecific($data,$htmlfile_name);

                if($result == "Sent") {

                $this->session->set_flashdata('message', array('message' => 'Email Sent Successfully','class' => 'success'));
                }
                else {  
                    
                    $this->session->set_flashdata('message', array('message' => 'Email Not Sent','class' => 'danger'));
                }

            redirect(base_url().'Befreebulk');


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

    public function SendmailToAll($data,$htmlfile_name)
        {  

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
           
            $subject = $data['title'];

            $html_content =  file_get_contents($htmlfile_name);
            
            $html_content = str_replace("images","http://testing.v2.admin.valucart.com/assets/uploads/email/images",$html_content);

            $html_content = str_replace('"', "'", $html_content);
           
            $message = ".$html_content.";

            $myArray = explode(',', $emails);


                foreach ($myArray as $email){
                           
                            $this->emailConfig();

                            $this->email->from($sender_email, $username);

                            $this->email->to($email);

                            $this->email->subject($subject);

                            $this->email->message($message);


                        $mail = ($this->email->send()) ? "Sent" : "Failed" ;

                        $rs =$this->email->print_debugger();

                }

            $files = glob('assets/uploads/email/*');

                foreach($files as $file){ 

                  if(is_file($file))

                    unlink($file); 

                }    
            $files = glob('assets/uploads/email/images/*');

                foreach($files as $file){ 

                  if(is_file($file))

                    unlink($file); 

                }  

            

            return $mail;


        }


//Send mail to specific customers

    public function SendmailToSpecific($data,$htmlfile_name)
        {  
            
             foreach($data['customers'] as $id){

                    $sender_email = "support@valucart.com";
                    $user_password = "password";
                    $username = "Valucart";
                   
                    $subject = $data['title'];

                    $html_content =  file_get_contents($htmlfile_name);
            
                    $html_content = str_replace("images","http://testing.v2.admin.valucart.com/assets/uploads/email/images",$html_content);

                    $html_content = str_replace('"', "'", $html_content);
                   
                    $message = ".$html_content.";

                    $db_data = "SELECT email FROM customers WHERE id = $id ";

                    $db_query = $this->db->query($db_data);
                    
                    $db_query =  $db_query->row();

                    $email = $db_query->email;

                    $this->emailConfig();

                    $this->email->from($sender_email, $username);

                    $this->email->to($email);

                    $this->email->subject($subject);

                    $this->email->message($message);

                    $mail = ($this->email->send()) ? "Sent" : "Failed" ;

                    $rs =$this->email->print_debugger();

             }

            $files = glob('assets/uploads/email/*');

                foreach($files as $file){ 

                  if(is_file($file))

                    unlink($file); 

                }    
            
            // $files = glob('assets/uploads/email/images/*');

            //     foreach($files as $file){ 

            //       if(is_file($file))

            //         unlink($file); 

            //     }  


    


            return $mail;


        }































    


} 
