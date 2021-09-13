<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Categories extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Categories_model');
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


    /* === VIEW CATEGORIES === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Categories/categories_view';
        $template['page_title'] = "View Categories";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Categories_model->get_categories();

        $this->load->view('template',$template);
    }


    /* === CREATE CATEGORIES === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Categories/categories_create';
        $template['page_title'] = "Create Categories";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['department'] = $this->db->get('departments')->result();
       
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Categories_model->save_categories($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Category Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Category Created successfully','class' => 'success'));
            }
            redirect(base_url().'categories');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE SEGMENT === */
    public function edit($id=null) {
        if($id==''){
            redirect('categories');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Categories/categories_edit';
        $template['page_title'] = "Edit Categories";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Categories_model->get_single_categories($id);
        if(empty($template['result'])){
            redirect(base_url('categories'));
        }
        $template['department'] = $this->db->get('departments')->result();
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Categories_model->update_categories($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Category already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Categories  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'categories');
        }
        else {
            $this->load->view('template', $template);
        }
    }



            /* === PUBLISH  CATEGORIES=== */
        public function publish(){
            $data1 = array(
                  "status" => '1'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Categories_model->update_categories_status($id, $data1);

            $cat_details = $this->db->where('id', $id)->get('categories')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Category '.$cat_details->name. ' to Published Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);


            $this->session->set_flashdata('message', array('message' => 'Categories Published  Successfully ','class' => 'success'));
            redirect(base_url().'categories');
        }

        public function unpublish(){
            $data1 = array(
                  "status" => '0'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Categories_model->update_categories_status($id, $data1);

            $cat_details = $this->db->where('id', $id)->get('categories')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Category '.$cat_details->name. ' to UnPublished Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => ' Categories Unpublished Successfully ','class' => 'warning'));
            redirect(base_url().'categories');
        }










} 
