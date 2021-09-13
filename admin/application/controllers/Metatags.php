<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Metatags extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        if($method == 'addtags'){
            $method = 'index';
        }
        $this->info = get_function($class,$method);
        
        $this->load->model('Metatags_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        //$role_id = 1; // change it to id from session -usertype - merchant,superadmin,technician
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
        //print_r($this->perm);die;
    }


    /* === VIEW Metatags === */
    public function index() {



        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Metatags/products_viewall';
        $template['page_title'] = "View All Products";
        $template['page_data'] = $this->info;
        // $template['usertype'] = $this->Products_model->get_usertype();
        $template['brands'] = $this->Metatags_model->get_brands();
        $template['departments'] = $this->Metatags_model->get_departments();
        $template['category'] = $this->Metatags_model->get_category();
        $template['data'] = $this->Metatags_model->get_allproducts();

        $this->load->view('template',$template);
    }

    public function get_all_products(){
        $data = $_GET;

        $columns = array("id","name","sku","department_id","category_id","valucart_price");
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

      
        $activity = $this->Metatags_model->get_allproducts($value);
        $all_activity = $this->Metatags_model->get_allproducts();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Metatags_model->get_allproducts($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    // $r->id,
                    $r->id,
                    $r->name,
                    $r->sku,
                    get_department_name($r->department_id),
                    get_category_name($r->category_id),
                    $r->valucart_price,

                    //anchor('test/view/' . $r->id, 'View'),
                    "<a class='btn btn-md btn-primary' href='".base_url()."metatags/addtags/".$r->id."'> <i class='fa fa-fw fa-tags'></i> Add MetaTags </a>
                    "



                   // anchor('test/view/' . $r->id, 'View'),
                   //  "<a class='btn btn-xs bg-olive show-allproducts'  href='javascript:void(0);' data-id = $r->id > <i class='fa fa-fw fa-eye'></i> View </a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }





    //add tags

    public function addtags($id=null) {
        if($id==''){
            redirect('metatags');
        }

        $is_exists = $this->db->where('id', $id)->get('products')->num_rows();

        if ($is_exists > 0) {
            
            $template['main'] = $this->info->module_menu;
            $template['perm'] = $this->perm;
            $template['sub'] = $this->info->function_menu;
            $template['page'] = 'Metatags/edit_metatags';
            $template['page_title'] = "Add Metatags";
            $template['page_data'] = $this->info;
            $id = $this->uri->segment(3);
            $template['product_data'] = $this->Metatags_model->get_product($id);
            $template['meta_data'] = $this->Metatags_model->get_single_product($id);
           
                if($_POST) {
                    $data = $_POST;
                    $object_id =  $this->info->object_id;
                    $result = $this->Metatags_model->update_metatags($data, $id,$object_id);
                    if($result == "Exist") {
                        $this->session->set_flashdata('message', array('message' => 'Metatags already exist','class' => 'danger'));
                    }
                    else {
                        $this->session->set_flashdata('message', array('message' => 'Metatags Added Successfully','class' => 'success'));
                    }
                    redirect(base_url().'products');
                }
                else {
                    $this->load->view('template', $template);
                }

        }else{


            $template['main'] = $this->info->module_menu;
            $template['perm'] = $this->perm;
            $template['sub'] = $this->info->function_menu;
            $template['page'] = 'Metatags/add_metatags';
            $template['page_title'] = "Add Metatags";
            $template['page_data'] = $this->info;
            $id = $this->uri->segment(3);
            $template['product_data'] = $this->Metatags_model->get_product($id);
                if($_POST) {
                    $data = $_POST;
                    $object_id =  $this->info->object_id;
                    $result = $this->Metatags_model->save_metatags($data, $id,$object_id);
                    if($result == "Exist") {
                        $this->session->set_flashdata('message', array('message' => 'Metatags already exist','class' => 'danger'));
                    }
                    else {
                        $this->session->set_flashdata('message', array('message' => 'Metatags Added Successfully','class' => 'success'));
                    }
                    redirect(base_url().'products');
                }
                else {
                    $this->load->view('template', $template);
                }


        }

    }








} 
?>






