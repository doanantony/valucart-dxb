<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendorcategories extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Vendorcategories_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW Vendorcategories === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Vendorcategories/view';
        $template['page_title'] = "View Vendor Categories";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Vendorcategories_model->get_vendorcategories();
        $this->load->view('template',$template);
    }


    /* === CREATE Vendorcategories === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Vendorcategories/create';
        $template['page_title'] = "Create Vendor Categories";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        
        if($_POST) {
            $data = $_POST;
            $config = $this->set_upload_options();
            $this->load->library('upload');
            $this->upload->initialize($config);
            // echo "<pre>";
            // print_r($_FILES);die;
            if ( ! $this->upload->do_upload('image')){
                echo $this->upload->display_errors();die;
                $this->session->set_flashdata('message', array('message' => 'Error Ocured While Uploading Files','class' => 'danger'));
            }else{
                $upload_data = $this->upload->data();
                $data['icon'] = base_url().$config['upload_path']."/".$upload_data['file_name'];
                $object_id =  $this->info->object_id;
                $result = $this->Vendorcategories_model->save($data,$object_id);
                if($result == "Exist") {
                    $this->session->set_flashdata('message', array('message' => 'Vendor category Already Exist','class' => 'danger'));
                }
                else {  
                    $this->session->set_flashdata('message', array('message' => 'Vendor category created successfully','class' => 'success'));
                }
                redirect(base_url().'vendorcategories');
            }
        }


        $this->load->view('template', $template);
    }


    /* === UPDATE Vendorcategories === */
    public function edit($id=null) {
        
        if($id==''){
            redirect('vendorcategories');
        }
        
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Vendorcategories/edit';
        $template['page_title'] = "Edit Vendor Categories";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Vendorcategories_model->get_single_vendorcategories($id);
        if(empty($template['result'])){
            redirect(base_url('vendorcategories'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            if(isset($_FILES["image"]['name']) && !empty($_FILES["image"]['name'])){
                $config = $this->set_upload_options();
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('image')){
                    $this->session->set_flashdata('message', array('message' => 'Error Ocured While Uploading Files','class' => 'danger'));
                }else{
                    $upload_data = $this->upload->data();
                    $data['icon'] = base_url().$config['upload_path']."/".$upload_data['file_name'];
                }
            }
            $result = $this->Vendorcategories_model->update($data,$id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Vendor category Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Vendor category Updated successfully','class' => 'success'));
            }
            redirect(base_url().'vendorcategories');
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

         //upload settings
    private function set_upload_options() {   
    //upload an image options
        $config = array();
        $config['upload_path'] = './valuassets/vendorcategories';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '5000';
        $config['overwrite']     = FALSE;
        return $config;
    } 











} 
