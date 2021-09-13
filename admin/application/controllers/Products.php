<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
       
        $this->load->model('Products_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW PRODUCTS === */
    public function index() {
     
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Products/products_viewall';
        $template['page_title'] = "View All Products";
        $template['page_data'] = $this->info;
        $template['brands'] = $this->Products_model->get_brands();
        $template['departments'] = $this->Products_model->get_departments();
        $template['category'] = $this->Products_model->get_category();
        $template['data'] = $this->Products_model->get_allproducts();
        $this->load->view('template',$template);
    }

    public function get_all_products(){
        $data = $_GET;
        $columns = array("id","name","sku","department_id","category_id");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];
        $search_box = array('name','sku','department_id','category_id');
        $limit = count($data['columns']);
        $value['where'] = '';
        $where_data = array();
        if(!empty($value['search'])) {
            $where = array();
            foreach($columns as $c) {
                $where_data[] = $c." like '%".$value['search']."%' ";
            }
            $where = implode(" OR ", $where_data);
            $where = "(".$where.")";
            $value['where'] = $where;
        }
        $custom_where = array();
        for($i=0;$i<$limit;$i++){
            if($data['columns'][$i]['search']['value']!=''){
                $search_val = $data['columns'][$i]['search']['value'];
                $custom_where[] = $search_box[$i]." like '%".$search_val."%' ";
            }
        }
        if(count($custom_where)>0){
            $where = implode(" AND ", $custom_where);
            $where = "(".$where.")";
            if($value['where']!=''){
                $value['where'] = $value['where']." AND ".$where;
            } else {
                $value['where'] = $where;
            }            
        }
        $order = $data['order'][0]['column'];
        $value['order'] = $columns[$order];
        $value['order_type'] = $data['order'][0]['dir'];
        $activity = $this->Products_model->get_allproducts($value);
        $all_activity = $this->Products_model->get_allproducts();
        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Products_model->get_allproducts($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->name,
                    $r->sku,
                    //get_brand_name($r->brand_id),
                    //get_department_name($r->department_id),
                    get_category_name($r->category_id),
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    //get_subcategory_name($r->subcategory_id),
                    $r->maximum_selling_price,
                    $r->valucart_price,
                    "<a class='btn btn-xs btn-primary' href='".base_url()."products/edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit </a>
                    <a class='btn btn-xs btn-success' href='".base_url()."products/editimages/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Images</a>
                    <a class='btn btn-xs btn-warning' onClick='return productstatus_confirm(".$r->published.")' href='".base_url()."products/$r->publish_status/".$r->id."'> <i class='fa fa-fw fa-check'></i>$r->publish_status</a>"
                ));
            }
        }
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }



    /* === VIEW FEATURED PRODUCTS === */
    public function featured() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Products/products_viewfeatured';
        $template['page_title'] = "View Featured Products";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Products_model->get_featuredproducts();
        $this->load->view('template',$template);
    }

    public function get_featured_products(){
        $data = $_GET;

        $columns = array("id","name","sku");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];
        $limit = count($data['columns']);
        $value['where'] = '';
        $where_data = array();
        if(!empty($value['search'])) {
            $where = array();
            foreach($columns as $c) {
                $where_data[] = $c." like '%".$value['search']."%' ";
            }
            $where = implode(" OR ", $where_data);
            $where = "(".$where.")";
            $value['where'] = $where;
        }
        $custom_where = array();
        for($i=0;$i<$limit;$i++){
            if($data['columns'][$i]['search']['value']!=''){
                $search_val = $data['columns'][$i]['search']['value'];
                $custom_where[] = $search_box[$i]." like '%".$search_val."%' ";
            }
        }
        if(count($custom_where)>0){
            $where = implode(" AND ", $custom_where);
            $where = "(".$where.")";
            if($value['where']!=''){
                $value['where'] = $value['where']." AND ".$where;
            } else {
                $value['where'] = $where;
            }            
        }
        $order = $data['order'][0]['column'];
        $value['order'] = $columns[$order];
        $value['order_type'] = $data['order'][0]['dir'];
        $activity = $this->Products_model->get_featuredproducts($value);
        $all_activity = $this->Products_model->get_featuredproducts();
        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Products_model->get_featuredproducts($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->name,
                    $r->sku,
                    get_brand_name($r->brand_id),
                    get_department_name($r->department_id),
                    get_category_name($r->category_id),
                    get_subcategory_name($r->subcategory_id),
                    $r->maximum_selling_price,
                    $r->valucart_price,
                    "<a class='btn btn-xs btn-primary' href='".base_url()."products/edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit </a>"
                ));
            }
        }
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    /* === CREATE PRODUCTS === */
    public function create() {
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Products/products_create';
        $template['page_title'] = "Create Product";
        $template['page_data'] = $this->info;
        $template['department'] = $this->db->get('departments')->result();
        $template['category'] = $this->db->get('categories')->result();
        $template['unit'] = $this->db->get('matric_units')->result();
        $template['community'] = $this->db->get('communities')->result();
        $template['brand'] = $this->db->get('brands')->result();
        $template['vendor'] = $this->db->get('vendors')->result();
        $template['state'] = $this->db->get('states')->result();
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Products_model->save_products($data,$object_id);
            $insert_id = $this->db->insert_id();
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Product Already Exist','class' => 'danger'));
            }
            else {  
                $this->session->set_flashdata('message', array('message' => 'New Product Created successfully','class' => 'success'));
            }
            redirect(base_url().'products/editimages/'.$result);
        }
        $this->load->view('template', $template);
    }


    public function get_sub_list() {
        $data = $_POST;
        $sub_category = $this->Products_model->sub_list($data);
        print json_encode($sub_category);
    }


    /* === UPDATE PRODUCTS === */
    public function edit($id=null) {
        if($id==''){
            redirect('products');
        }
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Products/products_edit';
        $template['page_title'] = "Edit Products";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Products_model->get_single_products($id);
        if(empty($template['result'])){
            redirect(base_url('products'));
        }
        $template['pro_id'] = $id;
        $template['department'] = $this->db->get('departments')->result();
        $template['brand'] = $this->db->get('brands')->result();
        $template['category'] = $this->db->get('categories')->result();
        $template['subcategory'] = $this->db->get('subcategories')->result();
        $template['vendors'] = $this->db->get('vendors')->result();
        $template['provendor'] = $this->Products_model->get_single_product_vendor($id);
        $template['matricunits'] = $this->db->get('matric_units')->result();
        $template['community'] = $this->db->get('communities')->result();
        $template['procommunity'] = $this->Products_model->get_single_product_community($id);
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Products_model->update_products($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Product already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Product Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'products/editimages/'.$id);
        }
        else {
            $this->load->view('template', $template);
        }
    }



    public function editimages($id=null) {
        if($id==''){
            redirect('products');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page']       = 'Products/productimages-edit';
        $template['page_title'] = "Edit Images Products";
        if ($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $result = $this->Products_model->is_thump_exists($data,$id);
            if ($result == "Exist") {
                $this->session->set_flashdata('message', array(
                    'message' => 'Thumb Image already Exist! Please delete Thumb and try again!',
                    'class' => 'danger'
                ));
            } else {

            if(isset($_FILES["image"]['name']) && !empty($_FILES["image"]['name'])){
                $config = $this->set_upload_options();
                $this->load->library('upload');
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('image')){
                    $this->session->set_flashdata('message', array('message' => 'Error Ocured While Uploading Files','class' => 'danger'));
                }else{
                    $upload_data = $this->upload->data();
                    $session_data = $this->session->userdata('logged_in');
                    $data['image'] = base_url()."valuassets/products"."/".$session_data['user_id']."/".$upload_data['file_name'];
                }
                $image_data = array('product_id' => $id,'is_thumb' => $data['is_thumb'] , 'path' => $data['image']);
                $result = $this->db->insert('products_images', $image_data); 
                $this->session->set_flashdata('message', array(
                    'message' => 'Image added successfully',
                    'class' => 'success'
                ));
            }
            }
            redirect(base_url() . 'products/editimages/'. $id);
        } else {
            $id = $this->uri->segment(3);
            $template['data'] = $this->Products_model->get_allproductsimages($id);
            $template['pro_data'] = $this->Products_model->get_single_products($id);
            $this->load->view('template', $template);
        }
    }



    public function delete_product_image()
    {
        $id     = $this->uri->segment(3);
        $query = $this->db->where('id', $id);
        $query = $this->db->get('products_images');
        $res = $query->row();
        $product_id = $res->product_id;
        $product_details = $this->db->where('id', $product_id)->get('products')->row();
        $name = $product_details->name;
        $result = $this->Products_model->product_image_delete($id);
            $log = array(
                         'id' =>$id,
                         'log' => 'Deleted Product Image'.$name. ''
            );
            $session_data = $this->session->userdata('logged_in');
            updatelog($log,$session_data);
        $this->session->set_flashdata('message', array(
            'message' => 'Requested Image Deleted Successfully',
            'class' => 'success'
        ));
       redirect(base_url() . 'products/editimages/'.$product_id);
    }


    public function publish(){
            $data1 = array(
                  "published" => '1'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Products_model->update_product_status($id, $data1);
            $pro_details = $this->db->where('id', $id)->get('products')->row();
            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Product '.$pro_details->name. ' to Published Status'
                      );
            $session_data = $this->session->userdata('logged_in');
            updatelog($log,$session_data);
            $this->session->set_flashdata('message', array('message' => 'Product Published  Successfully ','class' => 'success'));
            redirect(base_url().'products');
        }

    public function unpublish(){
            $data1 = array(
                  "published" => '0'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Products_model->update_product_status($id, $data1);
            $pro_details = $this->db->where('id', $id)->get('products')->row();
            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Product '.$pro_details->name. ' to UnPublished Status'
                      );

            $session_data = $this->session->userdata('logged_in');
            updatelog($log,$session_data);
            $this->session->set_flashdata('message', array('message' => ' Product Unpublished Successfully ','class' => 'warning'));
            redirect(base_url().'products');
        }


   private function set_upload_options() {   
    //upload an image options
        $session_data = $this->session->userdata('logged_in');
        $config = array();
        $config['upload_path'] = './valuassets/products/'.$session_data['user_id'];
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '5000';
        $config['overwrite']     = FALSE;
        return $config;
    } 






} 







