<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_type extends CI_Controller
{
    public function __construct()
{
    parent::__construct();
    $class = $this->router->fetch_class();
    $method = $this->router->fetch_method(); 
    $this->info = get_function($class,$method);
    
    date_default_timezone_set("Asia/Kolkata");
    $this->load->model('User_type_model');
    if (!$this->session->userdata('logged_in')) {
        redirect(base_url());
    }
    $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id);
}





    /* === CREATE USERTYPE === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Usertypes/usertype_create';
        $template['page_title'] = "Create User Type";
        $template['page_data'] = $this->info;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->User_type_model->save_usertype($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Usertype Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Usertype Created successfully','class' => 'success'));
            }
            redirect(base_url().'User_type');
        }
        $this->load->view('template', $template);
    }


   


    /* === VIEW USERTYPE === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Usertypes/usertype_view';
        $template['page_title'] = "View UserTypes";
        $template['page_data'] = $this->info;
        $template['data'] = $this->User_type_model->get_usertypes();
        $this->load->view('template',$template);
    }





    /* === UPDATE CARDS === */
    public function edit($id=null) {
        if($id==''){
        redirect('user_type');
    }
        //print_r($this->info->module_menu);die;
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Usertypes/usertype_edit';
        $template['page_title'] = "Edit UserTypes";
        $id = $this->uri->segment(3);
        $template['data'] = $this->User_type_model->get_single_usertype($id);
        $template['page_data'] = $this->info;
        if(empty($template['data'])){
            redirect(base_url('User_type'));
        }
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->User_type_model->update_usertypes($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'UserType already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'UserType  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'user_type');
        }
        else {
            $this->load->view('template', $template);
        }
    }


    




} 
