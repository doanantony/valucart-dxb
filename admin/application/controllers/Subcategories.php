<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Subcategories extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        date_default_timezone_set("Asia/Dubai");
        $this->load->model('Subcategories_model');
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


    /* === VIEW SUBCATEGORIES === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Subcategories/subcategories_view';
        $template['page_title'] = "View Categories";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Subcategories_model->get_subcategories();
        $this->load->view('template',$template);
    }


    /* === CREATE SUBCATEGORIES === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Subcategories/subcategories_create';
        $template['page_title'] = "Create Subcategories";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['category'] = $this->db->get('categories')->result();
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Subcategories_model->save_subcategories($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'SubCategory Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'SubCategory Created successfully','class' => 'success'));
            }
            redirect(base_url().'subcategories');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE SUBCATEGORIES === */
    public function edit($id=null) {
        if($id==''){
            redirect('subcategories');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Subcategories/subcategories_edit';
        $template['page_title'] = "Edit Subategories";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Subcategories_model->get_single_subcategories($id);
        if(empty($template['result'])){
            redirect(base_url('subcategories'));
        }
        $template['category'] = $this->db->get('categories')->result();
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Subcategories_model->update_subcategories($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Subcategory already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Subcategory  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'subcategories');
        }
        else {
            $this->load->view('template', $template);
        }
    }




                /* === PUBLISH  SUBCATEGORIES=== */
        public function publish(){
            $data1 = array(
                  "status" => '1'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Subcategories_model->update_subcategories_status($id, $data1);

            $subcat_details = $this->db->where('id', $id)->get('subcategories')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed SubCategory '.$subcat_details->name. ' to Published Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => 'Subcategory Published  Successfully ','class' => 'success'));
            redirect(base_url().'subcategories');
        }

        public function unpublish(){
            $data1 = array(
                  "status" => '0'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Subcategories_model->update_subcategories_status($id, $data1);

            $subcat_details = $this->db->where('id', $id)->get('subcategories')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed SubCategory '.$subcat_details->name. ' to UnPublished Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => ' Subcategory Unpublished Successfully ','class' => 'warning'));
            redirect(base_url().'subcategories');
        }











} 
