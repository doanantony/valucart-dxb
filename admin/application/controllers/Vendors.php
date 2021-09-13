<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendors extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        if($method == 'settlement'){
            $method = 'create';
        }
        $this->info = get_function($class,$method);
        $this->load->model('Vendors_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');

        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW Vendors === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Vendors/vendors_view';
        $template['page_title'] = "View Vendors";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Vendors_model->get_vendors();
        $this->load->view('template',$template);
    }


    public function vendors_viewpopup() {  

        $id=$_POST['patientdetailsval'];
        $function = 'generatehash/' . $id;
        $response = ApiCallGet($function);
        $resource_id = $response['data'];

        $function = 'vendors/' . $resource_id;
        $response = ApiCallGet($function);
        $template['image'] = $response['data']['image'];
      //  $template['name'] = $response['data']['name'];
        $template['data'] = $this->Vendors_model->view_popup_vendor($id);
        $this->load->view('Vendors/vendor-view-popup',$template);

    }


        /* === CREATE VENDORS === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Vendors/vendors_create';
        $template['page_title'] = "Create Vendors";
        $template['page_data'] = $this->info;
        $template['category'] = $this->db->get('departments_categories')->result();
        if($_POST) {
            $data = $_POST;
          //  echo "<pre>";print_r($data);die;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            if(isset($_FILES["image"]['name']) && !empty($_FILES["image"]['name'])){
                $config = $this->set_upload_options();
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('image')){
                    $this->session->set_flashdata('message', array('message' => 'Error Ocured While Uploading Files','class' => 'danger'));
                }else{
                    $upload_data = $this->upload->data();
                    $data['image'] = base_url()."valuassets/vendors"."/".$upload_data['file_name'];
                }
            }
            $result = $this->Vendors_model->save_vendors($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Vendor Already Exist','class' => 'danger'));
            }else {                  
                $this->session->set_flashdata('message', array('message' => 'Vendor Created successfully','class' => 'success'));
               
            }
            redirect(base_url().'Vendors');
           
        }
        $this->load->view('template', $template);
    }


    private function set_upload_options() {   
    //upload an image options
        $config = array();
        $config['upload_path'] = './valuassets/vendors';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '5000';
        $config['overwrite']     = FALSE;
        return $config;
    } 


    public function settlement($id=null) {

        if($id==null){
            redirect('vendors');
        }
        $vendor_id = $this->uri->segment(3);
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['vendor_id'] = $vendor_id;
        $template['vendor_profile'] = $this->Vendors_model->view_popup_vendor($vendor_id);
        $template['data'] = $this->Vendors_model->get_vendors_settings($vendor_id);
        $template['page'] = 'Settlement/settlement';
        $template['page_title'] = "View Cards";
        $template['page_data'] = $this->info;
        $this->load->view('template',$template);  

    }





} 
