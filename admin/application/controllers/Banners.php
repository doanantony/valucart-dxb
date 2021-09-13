<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banners extends CI_Controller
{

    
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Banners_model');
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


    /* === VIEW BANNERS === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Banners/banners_view';
        $template['page_title'] = "View Banners";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Banners_model->get_banners();
        $this->load->view('template',$template);
    }


    public function banner_viewpopup() {  

        $id=$_POST['patientdetailsval'];
        $template['data'] = $this->Banners_model->view_popup_banner($id);
        $this->load->view('Banners/banner-view-popup',$template);

    }


    /* === UNPUBLISH  BANNER=== */

      public function unpublish(){
            $data1 = array(
                  "status" => '0'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Banners_model->update_banner_status($id, $data1);
            $this->session->set_flashdata('message', array('message' => ' Banner Unpublished Successfully ','class' => 'warning'));
            redirect(base_url().'Banners');
        }

        /* === PUBLISH  BANNER=== */
        public function publish(){
            $data1 = array(
                  "status" => '1'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Banners_model->update_banner_status($id, $data1);
            $this->session->set_flashdata('message', array('message' => ' Banner Published  Successfully ','class' => 'success'));
            redirect(base_url().'Banners');
        }
        /* === BANNER DELETE=== */
        public function delete(){
            $id = $this->uri->segment(3);

            $banner_details = $this->db->where('id', $id)->get('banners')->row();

            $data = array(
                "status" => '2'
                );

            $name = $banner_details->name;

            $result=$this->Banners_model->update_banner_status($id, $data);


           

            $log = array(
                         'id' =>$id,
                         'log' => 'Deleted Banner '.$name. ''
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            
            $this->session->set_flashdata('message', array('message' => 'Banner Deleted Successfully','class' => 'success'));
            redirect(base_url().'Banners');
        }




    /* === CREATE BANNERS === */
    public function create() {


        

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Banners/banners_create';
        $template['page_title'] = "Create Banners";
        $template['page_data'] = $this->info;
        $template['bundle'] = $this->db->get('bundles')->result();
        $template['department'] = $this->db->get('departments')->result();
        $template['brand'] = $this->db->get('brands')->result();
        $template['category'] = $this->db->get('categories')->result();
        $template['subcategory'] = $this->db->get('subcategories')->result();
        $template['bundlecategory'] = $this->db->get('bundles_categories')->result();

        
        if($_POST) {
            $data = $_POST;

            unset($data['submit']);
            $result = $this->Banners_model->save_banners($data);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Banner Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Banner Created successfully','class' => 'success'));
            }
            redirect(base_url().'banners');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE BANNERS === */
    public function edit($id=null) {
        if($id==''){
            redirect('banners');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Banners/banners_edit';
        $template['page_title'] = "Edit Banners";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Banners_model->get_single_banner($id);
        if(empty($template['result'])){
            redirect(base_url('banners'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Banners_model->update_banners($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Banner already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Banner Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'banners');
        }
        else {
            $this->load->view('template', $template);
        }
    }




    public function get_prod_list() {
        $data = $_POST;
        $products = $this->Banners_model->prod_list($data);
        print json_encode($products);
    }











} 
