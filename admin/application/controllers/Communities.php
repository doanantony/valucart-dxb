<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Communities extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Communities_model');
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


    /* === VIEW COMMUNITIES === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Communities/communities_view';
        $template['page_title'] = "View Communities";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Communities_model->get_communities();
        $this->load->view('template',$template);
    }


    /* === CREATE COMMUNITIES === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Communities/communities_create';
        $template['page_title'] = "Create Communities";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Communities_model->save_communities($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Community Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Community Created successfully','class' => 'success'));
            }
            redirect(base_url().'communities');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE COMMUNITIES === */
    public function edit($id=null) {
        if($id==''){
            redirect('communities');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Communities/communities_edit';
        $template['page_title'] = "Edit States";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Communities_model->get_single_communities($id);
        if(empty($template['result'])){
            redirect(base_url('communities'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Communities_model->update_communities($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Community already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Community  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'communities');
        }
        else {
            $this->load->view('template', $template);
        }
    }








} 
