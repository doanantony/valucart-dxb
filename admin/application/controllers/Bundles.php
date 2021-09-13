<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bundles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        if($method == 'add_product' || $method == 'add_alternate' || $method == 'edit_product' || $method == 'edit_alternate'){
            $method = 'create';
        }
        $this->info = get_function($class,$method);
        $this->load->model('Bundles_model');
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
        $this->load->model('Bundles_model');
    }


    /* === VIEW BUNDLES === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/bundles_view';
        $template['page_title'] = "View Bundles";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Bundles_model->get_bundles();
  
        $this->load->view('template',$template);
    }



    /* === CREATE BUNDLES === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/bundles_create';
        $template['page_title'] = "Create Bundles";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['categories'] = $this->db->get('bundles_categories')->result();
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bundles_model->save_bundles($data,$object_id);
           
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundles Already Exist','class' => 'danger'));
            }
            // else if($result == "Error") {
            //     $this->session->set_flashdata('message', array('message' => 'Something went wrong! Please try again','class' => 'danger'));
            // } 
            else {                  
                $this->session->set_flashdata('message', array('message' => 'Bundles Created successfully','class' => 'success'));
                redirect(base_url().'bundles/add_product/'.$result);
            }
            redirect(base_url().'bundles/create/');
           
        }
        $this->load->view('template', $template);
    }

    public function add_product($id=null) {

        if($id == null) {
            $this->session->set_flashdata('message', array('message' => 'Bundles Id missing','class' => 'danger'));
            redirect(base_url().'Bundles/create');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/bundles_prod';
        $template['page_title'] = "Product Assign";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['categories'] = $this->db->get('categories')->result();
        $template['bundle'] = $this->Bundles_model->get_bundle($id);
        $template['products'] = $this->Bundles_model->popular_prod();
        $template['id'] = $id;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bundles_model->save_bundles($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundles Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Bundles Created successfully','class' => 'success'));
            }
           redirect(base_url().'areas');
        }
        $this->load->view('template', $template);
    }

    public function add_alternate_bck($id=null) {

        if($id == null) {
            $this->session->set_flashdata('message', array('message' => 'Bundles Id missing','class' => 'danger'));
            redirect(base_url().'Bundles/create');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/bundles_alternate';
        $template['page_title'] = "Alternate Assign";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['bundle'] = $this->Bundles_model->get_bundle($id);
        $template['products'] = $this->Bundles_model->get_bundle_prod($id);
        $template['id'] = $id;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bundles_model->save_bundles($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundles Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Bundles Created successfully','class' => 'success'));
            }
           redirect(base_url().'areas');
        }
        $this->load->view('template', $template);
    }

    /* === UPDATE AREAS === */

    public function get_prod_list() {
        $data = $_POST;
        $products = $this->Bundles_model->prod_list($data);
        print json_encode($products);
    }

    public function get_sub_list() {
        $data = $_POST;
        $sub_category = $this->Bundles_model->sub_list($data);
        print json_encode($sub_category);
    }


    public function edit($id=null) {
        if($id==''){
            redirect('bundles');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/adminbundles_edit';
        $template['page_title'] = "Edit Bundles";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Bundles_model->get_single_bundles($id);
        if(empty($template['result'])){
            redirect(base_url('bundles'));
        }
        $template['categories'] = $this->db->get('bundles_categories')->result();
        if($_POST) {
            $data = $_POST;

        
            $object_id =  $this->info->object_id;
            $result = $this->Bundles_model->update_bundles($data, $id,$object_id);


            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundle already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Bundles Updated Successfully','class' => 'success'));
            }

            if($_FILES["image"]["error"] != 0) {

                 redirect(base_url().'bundles/edit_product/'.$id);
                } 
                else{

                   $this->Bundles_model->upload_bundle_image($_FILES, $id);
                }
                


             redirect(base_url().'bundles/edit_product/'.$id);
        }
        else {
            $this->load->view('template', $template);
        }
    }

    public function get_products() {
        $id = json_decode($_POST['ids']);
        $products = $this->Bundles_model->get_list_products($id);
        print json_encode($products);
    }

    public function save_new_bundle() {
        $data = $_POST;
        $bundle_products = array();
        $bundle_id = $data['bundle_id'];
        foreach ($data['items'] as $items) {
            $prod = array(
                        'bundle_id'=>$bundle_id,
                        'product_id'=>$items['id'],
                        'quantity'=>$items['qty'],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                    );
            $bundle_products[] = $prod;
        }
        $this->db->insert_batch('bundles_products', $bundle_products);
        $this->db->where('id', $bundle_id)->update('bundles', array('valucart_price'=>$data['bundle_price']));
        $this->session->set_flashdata('message', array('message' => 'Bundle Products added Successfully','class' => 'success'));
        echo 1;
        //print_r(json_decode(file_get_contents('php://input')));
    }

    public function save_alternate_bck(){
        $data = $_POST;
        $bundle_products = array();
        foreach ($data['items'] as $items) {
            if($items['product_id']){
                foreach ($items['product_id'] as $prod) {                
                   $prod = array(
                            'bundles_products_id'=>$items['bundle_id'],
                            'product_id'=>$prod,
                            'quantity'=>$items['qty'],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s'),
                        );
                    $bundle_products[] = $prod; 
                }
            }
            
        }
        //print_r($bundle_products);
        if(count($bundle_products) > 0){
            $this->db->insert_batch('bundles_products_alternatives', $bundle_products);
        $this->session->set_flashdata('message', array('message' => 'Bundle Alternate Products added Successfully','class' => 'success'));
    } else {
        $this->session->set_flashdata('message', array('message' => 'No Alternate Products added','class' => 'success'));
    }
        
        echo 1;
    }




//newly added



        public function edit_product($id=null) {

        if($id == null) {
          //  $this->session->set_flashdata('message', array('message' => 'Bundles Id missing','class' => 'danger'));
            redirect(base_url().'Bundles');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/bundles_edit';
        $template['page_title'] = "Product Re-Assign";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['categories'] = $this->db->get('categories')->result();
        $template['bundle'] = $this->Bundles_model->get_bundle($id);
        $template['products'] = $this->Bundles_model->popular_prod();
        $template['bundle_prod'] = $this->Bundles_model->get_bundle_all_prod($id);
        $template['id'] = $id;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bundles_model->save_bundles($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundles Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Bundles Created successfully','class' => 'success'));
            }
           redirect(base_url().'areas');
        }
        $this->load->view('template', $template);
    }






        public function add_alternate($id=null) {

        if($id == null) {
            $this->session->set_flashdata('message', array('message' => 'Bundles Id missing','class' => 'danger'));
            redirect(base_url().'Bundles/create');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/bundles_alternate_edit';
        $template['page_title'] = "Alternate Assign";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['bundle'] = $this->Bundles_model->get_bundle($id);
        //$template['products'] = $this->Bundles_model->get_bundle_prod($id);
        $template['products'] = $this->Bundles_model->get_bundle_alter_prod($id);
        $template['id'] = $id;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bundles_model->save_bundles($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundles Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Bundles Created successfully','class' => 'success'));
            }
           redirect(base_url().'areas');
        }
        $this->load->view('template', $template);
    }



    public function save_alternate(){
        $data = $_POST;
        $bundle_products = array();
        $bundle_ids = array();


        foreach ($data['items'] as $items) {
           
            if($items['product_id']){
                foreach ($items['product_id'] as $prod) {                
                   $prod = array(
                            'bundles_products_id'=>$items['bundle_id'],
                            'product_id'=>$prod,
                            'quantity'=>$items['qty'],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s'),
                        );
                    $bundle_products[] = $prod; 
                    $bundle_ids[] = $items['bundle_id'];
                }
            }
            
        }
        //print_r($bundle_products);
        if(count($bundle_products) > 0){
            $this->db->where_in('bundles_products_id',$bundle_ids)->delete('bundles_products_alternatives');
            $this->db->insert_batch('bundles_products_alternatives', $bundle_products);
        $this->session->set_flashdata('message', array('message' => 'Bundle Alternate Products added Successfully','class' => 'success'));
    } else {
        $this->session->set_flashdata('message', array('message' => 'No Alternate Products added','class' => 'success'));
    }
        
        echo 1;
    }




    public function save_bundle() {
        $data = $_POST;
        $bundle_products = array();
        $bundle_id = $data['bundle_id'];
        $bundle_ids = $data['bundle_ids'];
        $product_ids = $data['product_ids'];
        $qty = $data['qty'];
        $bundle_price = $data['bundle_price'];

        $updateArray = array();
        $bundle_products = array();
        for($x = 0; $x < sizeof($product_ids); $x++){
            $prod = array(
                        'id'=>$bundle_ids[$x]!=''?$bundle_ids[$x]:NULL,
                        'bundle_id'=>$bundle_id,
                        'product_id'=>$product_ids[$x],
                        'quantity'=>$qty[$x],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                    );
            $bundle_products[] = $prod;
        }      
        $this->db->where('bundle_id', $bundle_id)->delete('bundles_products');
        $this->db->insert_batch('bundles_products', $bundle_products);
        $this->db->where('id', $bundle_id)->update('bundles', array('valucart_price'=>$data['bundle_price']));
        $this->session->set_flashdata('message', array('message' => 'Bundle Products updated Successfully','class' => 'success'));
        echo 1;
        //print_r(json_decode(file_get_contents('php://input')));
    }




    public function edit_alternate($id=null) {

        if($id == null) {
            $this->session->set_flashdata('message', array('message' => 'Bundles Id missing','class' => 'danger'));
            redirect(base_url().'Bundles/create');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bundles/bundles_alternate_edit';
        $template['page_title'] = "Alternate Re-Assign";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        $template['bundle'] = $this->Bundles_model->get_bundle($id);
        $template['products'] = $this->Bundles_model->get_bundle_alter_prod($id);
        $template['id'] = $id;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bundles_model->save_bundles($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bundles Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Bundles Created successfully','class' => 'success'));
            }
           redirect(base_url().'areas');
        }
        $this->load->view('template', $template);
    }






        /* === PUBLISH  BUNDLE=== */
        public function publish(){
            $data1 = array(
                  "status" => '1'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Bundles_model->update_bundle_status($id, $data1);

            $bundle_details = $this->db->where('id', $id)->get('bundles')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Bundle '.$bundle_details->name. ' to Published Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);


            $this->session->set_flashdata('message', array('message' => 'Bundle Published  Successfully ','class' => 'success'));
            redirect(base_url().'Bundles');
        }

        public function unpublish(){
            $data1 = array(
                  "status" => '0'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Bundles_model->update_bundle_status($id, $data1);

            $bundle_details = $this->db->where('id', $id)->get('bundles')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Bundle '.$bundle_details->name. ' to UnPublished Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => ' Bundle Unpublished Successfully ','class' => 'warning'));
            redirect(base_url().'Bundles');
        }


        /* === BANNER BUNDLE=== */
        public function delete(){
            $id = $this->uri->segment(3);

            $banner_details = $this->db->where('id', $id)->get('banners')->row();


            $data = array(
                "status" => '2'
                );

            $name = $banner_details->name;

            
            $result=$this->Bundles_model->update_bundle_status($id, $data);

            $log = array(
                         'id' =>$id,
                         'log' => 'Deleted Bundle '.$name. ''
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => 'Bundle Deleted Successfully','class' => 'success'));
            redirect(base_url().'Bundles');
        }














} 
