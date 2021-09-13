<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Profile_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');          
        $this->perm = get_permit($role_id); 
    }


   


   



   
    //upload settings
    private function set_upload_options() {   
    //upload an image options
        $config = array();
        $config['upload_path'] = './assets/uploads/portalusers';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '5000';
        $config['overwrite']     = FALSE;
        return $config;
    } 

    


    public function index() {
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Profile/profile_edit';
        $template['page_title'] = "Edit Profile";
        $template['page_data'] = $this->info;
        $session_data = $this->session->userdata('logged_in');
        $arr = array('user_id' => $session_data['user_id'],
                     'user_type_id' => $session_data['user_type_id']);
        $template['user_data'] = $this->db->where($arr)->get('users')->row();
        $template['profile_pic'] = $this->db->where('id' , $session_data['user_id'])->get('super_admin')->row();
        //echo $this->db->last_query();die;
         if($_POST){
            //print_r($_FILES);die;
            if(isset($_POST['picturechecker']) && !empty($_POST['picturechecker'])){                                        
                if(isset($_FILES['profile_picture'])) {                 
                            $this->load->library('upload');
                            $config = set_upload_options();
                            $this->upload->initialize($config);
                            if ( ! $this->upload->do_upload('profile_picture')) {       
                                $this->session->set_flashdata('message', array('message' => 'Error Occured While Uploading Files','class' => 'danger'));
                                }else {                                                     
                                    $upload_data = $this->upload->data();                                   
                                    $data['profile_picture'] = $config['upload_path']."/".$upload_data['file_name'];                                                                    
                                    $result = $this->Profile_model->update_admin($data, $arr);
                                    if($result){
                                        if($id == $this->session->userdata('logged_in')['id']) {
                                        $this->session->userdata('profile_picture',$data['profile_picture']);                                   
                                        }
                                    }   
                            }                                                                           
                    }
                }elseif(isset($_POST['formchecker']) && !empty($_POST['formchecker'])){
                    $data = $_POST;
                    unset($data['formchecker']);
                    if($data['n_password'] == $data['c_password']){
                        unset($data['c_password']);
                        $result = $this->Profile_model->update_admin_password($data,$arr);
                        if($result){
                        $this->session->set_flashdata('message', array('message' => 'Password Updated Successfully.','class' => 'success'));
                        }else{
                        $this->session->set_flashdata('message', array('message' => 'Error Occured.Entered Old Password Is Wrong.','class' => 'danger'));                           
                    }
                    }else{
                        $this->session->set_flashdata('message', array('message' => 'Password Mismatch.Entered new password does not match with confirm new password.','class' => 'danger'));
                        }
                    }                   
                }
        $this->load->view('template', $template);
    }



} 
