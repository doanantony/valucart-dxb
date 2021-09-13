<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bundlecategories extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Bundlecategories_model');
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


    /* === VIEW BUNDLECATEGORIES === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundlecategories/bundlecategories_view';
        $template['page_title'] = "View Bundle Categories";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Bundlecategories_model->get_bundlecategories();
        $this->load->view('template',$template);
    }


    /* === CREATE BUNDLECATEGORIES === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundlecategories/bundlecategories_create';
        $template['page_title'] = "Create Bundle Categories";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bundlecategories_model->save_bundlecategories($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundle Category Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Bundle Category Created successfully','class' => 'success'));
            }
            redirect(base_url().'bundlecategories');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE BUNDLECATEGORIES === */
    public function edit($id=null) {
        
        if($id==''){
            redirect('bundlecategories');
        }
        
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundlecategories/bundlecategories_edit';
        $template['page_title'] = "Edit Bundle Categories";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Bundlecategories_model->get_single_bundlecategories($id);
        if(empty($template['result'])){
            redirect(base_url('bundlecategories'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Bundlecategories_model->update_bundlecategories($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundle Category already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Bundle Category  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'bundlecategories');
        }
        else {

            $this->load->view('template', $template);
        }
    }


        public function delete(){
            $id = $this->uri->segment(3);
            

            $bundlecat_details = $this->db->where('id', $id)->get('bundles_categories')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Deleted Bundle Category '.$bundlecat_details->name. ''
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);

            $result=$this->Bundlecategories_model->delete_bundlecat($id);


            $this->session->set_flashdata('message', array('message' => 'Bundle Category Deleted Successfully','class' => 'danger'));
            redirect(base_url().'bundlecategories');
        }











} 
