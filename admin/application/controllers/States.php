<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class States extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('States_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
       // $active_userid = $this->session->userdata('user_id');
        //$role_id = 1; // change it to id from session -usertype - merchant,superadmin,technician
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW STATES === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'States/states_view';
        $template['page_title'] = "View States";
        $template['page_data'] = $this->info;
        $template['data'] = $this->States_model->get_states();
        $this->load->view('template',$template);
    }


    /* === CREATE STATES === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'States/states_create';
        $template['page_title'] = "Create States";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->States_model->save_states($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'State Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'State Created successfully','class' => 'success'));
            }
            redirect(base_url().'states');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE STATES === */
    public function edit($id=null) {
        if($id==''){
            redirect('states');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'States/states_edit';
        $template['page_title'] = "Edit States";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->States_model->get_single_states($id);
        if(empty($template['result'])){
            redirect(base_url('states'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->States_model->update_states($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'State already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'State  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'states');
        }
        else {
            $this->load->view('template', $template);
        }
    }








} 
