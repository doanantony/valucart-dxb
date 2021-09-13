<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Segment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Segment_model');
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


    /* === VIEW SEGMENT === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Segment/segment_view';
        $template['page_title'] = "View Segment";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Segment_model->get_segment();
        $this->load->view('template',$template);
    }


    /* === CREATE SEGMENT === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Segment/segment_create';
        $template['page_title'] = "Create Segment";
        $template['page_data'] = $this->info;
        $result = array(
            'type' =>'',
        );
        $template['result'] = (object) $result;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Segment_model->save_segment($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Segment Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Segment Created successfully','class' => 'success'));
            }
            redirect(base_url().'segment');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE SEGMENT === */
    public function edit($id=null) {
        if($id==''){
            redirect('segment');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Segment/segment_create';
        $template['page_title'] = "Edit Segment";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Segment_model->get_single_segment($id);
        if(empty($template['result'])){
            redirect(base_url('segment'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Segment_model->update_segment($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Segment already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Segment  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'segment');
        }
        else {
            $this->load->view('template', $template);
        }
    }








} 
