<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Coupons extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        if($method == 'adduser' || $method == 'additems'){
            $method = 'create';
        }

        $this->info = get_function($class,$method);
        $this->load->model('Coupons_model');
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


    /* === VIEW COUPONS === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Coupons/coupons_view';
        $template['page_title'] = "View Coupons";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Coupons_model->get_allcoupons();
        $this->load->view('template',$template);
    }


    public function get_all_coupons(){
        $data = $_GET;

        $columns = array('coupon','minimum_order_value','minimum_order_value','for_payment_method','discount','usage_limit','for_payment_method','starts_at','expires_at');

        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

        // $search_box = array('id','minimum_order_value','minimum_order_value','applicable_order','department_id','category_id');

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

      
        $activity = $this->Coupons_model->get_allcoupons($value);
        $all_activity = $this->Coupons_model->get_allcoupons();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Coupons_model->get_allcoupons($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->coupon,
                    $r->minimum_order_value,
                    "<span class='center label label label-".$r->classname."'>" .$r->applicable_order. "</span>",
                    $r->discount,
                    $r->usage_limit,
                     $r->applicable_payents,
                     $r->starts_at,
                     $r->expires_at,
                    // "<span class='center label label label-".$r->classnames."'>" .$r->applicable_payents. "</span>",
                    "<a class='btn btn-xs btn-primary' href='".base_url()."Coupons/adduser/".$r->id."'> <i class='glyphicon glyphicon-check'></i>  Assign User</a>
                    <a class='btn btn-xs btn-success' href='".base_url()."Coupons/additems/".$r->id."'> <i class='glyphicon glyphicon-check'></i>  Assign Items</a>
                    <a class='btn btn-xs btn-danger' href='".base_url()."Coupons/delete/".$r->id."'> <i class='fa fa-fw fa-trash'></i> Delete</a>"

                                   ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }




               
                
           



    /* === CREATE COUPONS === */
    public function create() {


        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Coupons/coupons_create';
        $template['page_title'] = "Create Coupons";
        $template['page_data'] = $this->info;
        if($_POST) {
            $data = $_POST;
           
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Coupons_model->save_coupons($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Coupon Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Coupon Created successfully','class' => 'success'));
            }
            redirect(base_url().'coupons');
        }
        $this->load->view('template', $template);
    }






    /* === DELETE COUPON=== */
        public function delete(){
            $id = $this->uri->segment(3);
            $coupon = urldecode($id);

            $result=$this->Coupons_model->delete_coupon($coupon);

            $log = array(
                         'id' =>$id,
                         'log' => 'Deleted Coupon '.$coupon. ''
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => 'Coupon Deleted Successfully','class' => 'success'));
            redirect(base_url().'coupons');
        }





    public function adduser($id=null) {

        if($id == null) {
        $this->session->set_flashdata('message', array('message' => 'Coupon Id missing','class' => 'danger'));
            redirect(base_url().'Coupons');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Coupons/coupons_create_users';
        $template['page_title'] = "Create Coupons Users";
        if ($_POST) {
            $data = $_POST;
            $id = urldecode($id);

            unset($data['submit']);
            $result = $this->Coupons_model->is_cust_exists($data,$id);
            
            if ($result == "Exist") {
                $this->session->set_flashdata('message', array(
                    'message' => 'Customer Already Added to this coupon',
                    'class' => 'danger'
                ));
            } else {

            if($data['customer_id']){
                
                $coupondata = array('customer_identifier' => $data['customer_id'],'coupon' => $id);

                $this->AddCouponUsers($coupondata);

            }

            if($data['domainuser']){
                
                $coupondata = array('customer_identifier' => $data['domainuser'],'coupon' => $id);

                $this->AddCouponUsers($coupondata);

            }




                $this->session->set_flashdata('message', array(
                    'message' => 'Customer added to the coupon successfully',
                    'class' => 'success'
                ));
                
            }
            redirect(base_url() . 'coupons/adduser/'. $id);
        } else {

            $id = $this->uri->segment(3);
            $template['data'] = $this->Coupons_model->get_all_coupon_users($id);
            $template['domaindata'] = $this->Coupons_model->get_all_coupon_domainusers($id);
            $template['customer'] = $this->db->get('customers')->result();
            $template['couponname'] = $id;
            $this->load->view('template', $template);

        }
    }







    function AddCouponUsers($data){
       

       if(isset($data)){

        $ch =curl_init();

          $url = "http://v2api.valucart.com/coupons/". $data['coupon']."/users";

          $postdata = array('customer_identifier' => $data['customer_identifier']);

       
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

           $response = curl_exec($ch);


       }

    
    }




        /* === DELETE COUPON USER === */
        public function remove_couponuser(){
           
            $id = $this->uri->segment(4);
            $result=$this->Coupons_model->delete_coupon_users($id);
            $this->session->set_flashdata('message', array('message' => 'Customer Removed Successfully','class' => 'success'));
            $coupon = $this->uri->segment(3);
             redirect(base_url() . 'coupons/adduser/'. $coupon);
        }


                /* === DELETE COUPON USER === */
        public function remove_couponitems(){
           
            $id = $this->uri->segment(4);
            $result=$this->Coupons_model->delete_coupon_items($id);
            $this->session->set_flashdata('message', array('message' => 'Item Removed Successfully','class' => 'success'));
            $coupon = $this->uri->segment(3);
             redirect(base_url() . 'coupons/additems/'. $coupon);
        }






//

    public function additems($id=null) {

        if($id == null) {
        $this->session->set_flashdata('message', array('message' => 'Coupon Id missing','class' => 'danger'));
            redirect(base_url().'Coupons');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Coupons/coupons_create_items';
        $template['page_title'] = "Create Coupons Items";
        $template['bundle'] = $this->db->get('bundles')->result();
        $template['department'] = $this->db->get('departments')->result();
        $template['brand'] = $this->db->get('brands')->result();
        $template['category'] = $this->db->get('categories')->result();
        $template['subcategory'] = $this->db->get('subcategories')->result();
        $template['bundlecategory'] = $this->db->get('bundles_categories')->result();
        if ($_POST) {
            $data = $_POST;
            $coupon = urldecode($id);
            unset($data['submit']);
            $result = $this->Coupons_model->add_coupon_items($data,$coupon);
            
            if ($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Item Already added to this coupon','class' => 'danger'));
            } else {

                $this->session->set_flashdata('message', array('message' => 'Item Added successfully','class' => 'success'));
                
            }
            redirect(base_url() . 'coupons/additems/'. $id);
        } else {

            $id = $this->uri->segment(3);
            $template['data'] = $this->Coupons_model->get_all_coupon_items($id);
            $template['customer'] = $this->db->get('customers')->result();
            $template['couponname'] = $id;
            $this->load->view('template', $template);

        }
    }



















} 
