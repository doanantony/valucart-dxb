<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');
class Allorders extends CI_Controller
{
    public function __construct()
    {   
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        if($method == 'view' || $method == 'deliveryitems' ){
            $method = 'index';
        }

        $this->info = get_function($class,$method);
        $this->load->model('Allorders_model');
        $this->load->model('Orders_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/orders_view.php';
        $template['page_title'] = "View All Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Allorders_model->get_allorders();

        if($_POST) {
            $data = $_POST;

            $delivery_date = date("Y-m-d", strtotime($data['delivery_date']));  
            redirect(base_url().'allorders/deliveryitems/'.$delivery_date);
        }



        $this->load->view('template',$template);
    }

    public function get_all_orders(){
        $data = $_GET;

        $columns = array('order_reference','customer_id','customer_id','customer_id','price','status','created_at');
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

      //  $search_box = array('id','customer_id','brand_id','department_id','category_id');

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

      
        $activity = $this->Allorders_model->get_allorders($value);
        $all_activity = $this->Allorders_model->get_allorders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Allorders_model->get_allorders($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    get_customer_name($r->customer_id),
                    get_customer_email($r->customer_id),
                    $r->price,
                   // <a class='btn btn-xs btn-primary' href='".base_url()."allorders/edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit</a>
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    date("Y-m-d H:i:s", strtotime('+4 hours', strtotime($r->updated_at))),
                    "<a class='btn btn-xs btn-success' href='".base_url()."allorders/view/".$r->id."'> <i class='fa fa-fw fa-eye'></i> View</a>
                    
                    <a class='btn btn-xs btn-danger' href='".base_url()."allorders/shipped/".$r->id."'> <i class='fa fa-check-square-o'></i> Shipped</a>
                    <a class='btn btn-xs btn-info' href='".base_url()."allorders/deliverd/".$r->id."'> <i class='fa fa-check-square-o'></i> Delivered</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }



    // New Orders Created

    public function createdorders() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/createdorders.php';
        $template['page_title'] = "View Created Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Allorders_model->get_createdorders();
        $this->load->view('template',$template);
    }


    public function get_created_orders(){

        $data = $_GET;
        $columns = array('order_reference','customer_id','customer_id','customer_id','price','status','created_at');
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
        $activity = $this->Allorders_model->get_createdorders($value);
        $all_activity = $this->Allorders_model->get_createdorders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Allorders_model->get_createdorders($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    get_customer_name($r->customer_id),
                    get_customer_email($r->customer_id),
                    $r->price,
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    date("Y-m-d H:i:s", strtotime('+4 hours', strtotime($r->updated_at))),
                    "<a class='btn btn-xs btn-success' href='".base_url()."allorders/view/".$r->id."'> <i class='fa fa-fw fa-eye'></i> View</a>
                    
                    <a class='btn btn-xs btn-danger' href='".base_url()."allorders/shipped/".$r->id."'> <i class='fa fa-check-square-o'></i> Shipped</a>
                    <a class='btn btn-xs btn-info' href='".base_url()."allorders/deliverd/".$r->id."'> <i class='fa fa-check-square-o'></i> Delivered</a>
                    <a class='btn btn-xs btn-warning' href='".base_url()."allorders/cancelled/".$r->id."'> <i class='fa fa-check-square-o'></i> Cancel</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }



    // New Orders Placed

    public function neworders() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/neworders.php';
        $template['page_title'] = "View New Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Allorders_model->get_neworders();
        $this->load->view('template',$template);
    }


    public function get_new_orders(){

        $data = $_GET;
        $columns = array('order_reference','customer_id','customer_id','customer_id','price','status','created_at');
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
        $activity = $this->Allorders_model->get_neworders($value);
        $all_activity = $this->Allorders_model->get_neworders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Allorders_model->get_neworders($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    get_customer_name($r->customer_id),
                    get_customer_email($r->customer_id),
                    $r->price,
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    date("Y-m-d H:i:s", strtotime('+4 hours', strtotime($r->updated_at))),
                    "<a class='btn btn-xs btn-success' href='".base_url()."allorders/view/".$r->id."'> <i class='fa fa-fw fa-eye'></i> View</a>
                    
                    <a class='btn btn-xs btn-danger' href='".base_url()."allorders/shipped/".$r->id."'> <i class='fa fa-check-square-o'></i> Shipped</a>
                    <a class='btn btn-xs btn-info' href='".base_url()."allorders/deliverd/".$r->id."'> <i class='fa fa-check-square-o'></i> Delivered</a>
                    <a class='btn btn-xs btn-warning' href='".base_url()."allorders/cancelled/".$r->id."'> <i class='fa fa-check-square-o'></i> Cancel</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    // Shipped Orders

    public function shippedorders() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/shippedorders.php';
        $template['page_title'] = "View Shipped Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Allorders_model->get_shippedorders();
        $this->load->view('template',$template);
    }


    public function get_shipped_orders(){

        $data = $_GET;
        $columns = array('order_reference','customer_id','customer_id','customer_id','price','status','created_at');
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
        $activity = $this->Allorders_model->get_shippedorders($value);
        $all_activity = $this->Allorders_model->get_shippedorders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Allorders_model->get_shippedorders($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    get_customer_name($r->customer_id),
                    get_customer_email($r->customer_id),
                    $r->price,
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    date("Y-m-d H:i:s", strtotime('+4 hours', strtotime($r->updated_at))),
                    "<a class='btn btn-xs btn-success' href='".base_url()."allorders/view/".$r->id."'> <i class='fa fa-fw fa-eye'></i> View</a>
                    <a class='btn btn-xs btn-info' href='".base_url()."allorders/deliverd/".$r->id."'> <i class='fa fa-check-square-o'></i> Delivered</a>
                    <a class='btn btn-xs btn-warning' href='".base_url()."allorders/cancelled/".$r->id."'> <i class='fa fa-check-square-o'></i> Cancel</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    // Delivered Orders

    public function deliveredorders() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/deliveredorders.php';
        $template['page_title'] = "View Delivered Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Allorders_model->get_deliveredorders();
        $this->load->view('template',$template);
    }


    public function get_delivered_orders(){

        $data = $_GET;
        $columns = array('order_reference','customer_id','customer_id','customer_id','price','status','created_at');
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
        $activity = $this->Allorders_model->get_deliveredorders($value);
        $all_activity = $this->Allorders_model->get_deliveredorders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Allorders_model->get_deliveredorders($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    get_customer_name($r->customer_id),
                    get_customer_email($r->customer_id),
                    $r->price,
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    date("Y-m-d H:i:s", strtotime('+4 hours', strtotime($r->updated_at))),
                    "<a class='btn btn-xs btn-success' href='".base_url()."allorders/view/".$r->id."'> <i class='fa fa-fw fa-eye'></i> View</a>
                    <a class='btn btn-xs btn-warning' href='".base_url()."allorders/cancelled/".$r->id."'> <i class='fa fa-check-square-o'></i> Cancel</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    // Cancelled Orders

    public function cancelledorders() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/cancelledorders.php';
        $template['page_title'] = "View Cancelled Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Allorders_model->get_cancelledorders();
        $this->load->view('template',$template);
    }


    public function get_cancelled_orders(){

        $data = $_GET;
        $columns = array('order_reference','customer_id','customer_id','customer_id','price','status','created_at');
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
        $activity = $this->Allorders_model->get_cancelledorders($value);
        $all_activity = $this->Allorders_model->get_cancelledorders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Allorders_model->get_cancelledorders($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    get_customer_name($r->customer_id),
                    get_customer_email($r->customer_id),
                    $r->price,
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    date("Y-m-d H:i:s", strtotime('+4 hours', strtotime($r->updated_at))),
                    "<a class='btn btn-xs btn-success' href='".base_url()."allorders/view/".$r->id."'> <i class='fa fa-fw fa-eye'></i> View</a>
                
                    <a class='btn btn-xs btn-info' href='".base_url()."allorders/deliverd/".$r->id."'> <i class='fa fa-check-square-o'></i> Delivered</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    // View Single Order

    public function view($id=null) {

        if($id==''){
            redirect('allorders');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/singlorder_view.php';
        $template['page_title'] = "View All Orders";
        $template['page_data'] = $this->info;
        $template['order_id'] = $id;
        $template['data'] = $this->Allorders_model->view_popup_order($id);
        $template['order'] = $this->Allorders_model->view_popup_order_details($id);

        $this->load->view('template',$template);
    }


    // Generate Invoice 

    public function invoice_generate($id=null){
        $template['data'] = $this->Allorders_model->view_popup_order($id);
        $template['order'] = $this->Allorders_model->view_popup_order_details($id);
        $this->load->view('Allorders/invoice', $template);
    }

    // Change To Shipped Status

    public function shipped(){
            $data1 = array(
                  "status" => '3'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Allorders_model->update_order_status($id, $data1);
            
            $query = $this->db->where('id', $id)->get('orders')->row();
            $order_id = $query->order_reference;
            $order_price = $query->price;
            $customer_id = $query->customer_id;
            $res = $this->db->where('id', $customer_id)->get('customers')->row();
            $customer_name = $res->name;
            
           
            $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER SHIPPED', 'message' => 'Hello ' .$customer_name. '! your order of AED' .$order_price.' was successfuly Shipped and our captain is on his way😊');

            $this->sendpush($customer_id,$fcm_data);

            $session_data = $this->session->userdata('logged_in');

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Order '.$order_id. ' to Shipped Status'
                      );
           updatelog($log,$session_data);

            $this->session->set_flashdata('message', array('message' => ' Order Shipped Successfully ','class' => 'warning'));
            redirect(base_url().'Allorders/neworders');
        }


         // Change To Delivered Status

        public function deliverd(){

            $id = $this->uri->segment(3);
            $query = $this->db->where('id', $id)->get('orders')->row();
            $vendor_setttings = $this->db->where('vendor_id',$query->vendor_id)->get('system_settings')->row();

            $total_price = $query->price;
 
            if($query->payment_type == 'cod'){


                $vendor_commission_percent = $vendor_setttings->cash_commission;

                $amount_to_vc = ($vendor_commission_percent / 100) * $total_price;
            }

            if($query->payment_type == 'card'){

                $vendor_commission_percent = $vendor_setttings->card_commission;

                $amount_to_vc = ($vendor_commission_percent / 100) * $total_price;
            }

            $amount_to_vendor = $total_price - $amount_to_vc;
            $data1 = array(
                  "status" => '4',
                  'commission' => $amount_to_vc,
                  'vendor_payback' => $amount_to_vendor
                 );

            $s=$this->Allorders_model->update_order_status($id, $data1);

            $order_id = $query->order_reference;
            $order_price = $query->price;
            $customer_id = $query->customer_id;
            $res = $this->db->where('id', $customer_id)->get('customers')->row();
            $customer_name = $res->name;
           
           
            $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER DELIVERED', 'message' => 'Hello ' .$customer_name. '! Thank you for shopping with Valucart . Kindly keep in touch with us on valucart.ae on fb /instagram 😊!');

            $this->sendpush($customer_id,$fcm_data);

                    $log = array(
                         'id' =>$id,
                         'log' => 'Changed Order '.$order_id. ' to Delivered Status'
                      );
            $session_data = $this->session->userdata('logged_in');
            updatelog($log,$session_data);


            $this->session->set_flashdata('message', array('message' => ' Order Delivered  Successfully ','class' => 'success'));
            redirect(base_url().'Allorders/neworders');
        }


        // Change To Cancelled Status

        public function cancelled(){
            $data1 = array(
                  "status" => '19'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Allorders_model->update_order_status($id, $data1);

            $query = $this->db->where('id', $id)->get('orders')->row();
            $order_id = $query->order_reference;
            $order_price = $query->price;
            $customer_id = $query->customer_id;
            $res = $this->db->where('id', $customer_id)->get('customers')->row();
            $customer_name = $res->name;
           
           
            $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER CANCELLED', 'message' => 'Hello ' .$customer_name. '! Your Order Got Cancelled! Shop again and Kindly keep in touch with us on valucart.ae on fb /instagram 😔!');

            $this->sendpush($customer_id,$fcm_data);

                    $log = array(
                         'id' =>$id,
                         'log' => 'Changed Order '.$order_id. ' to Cancelled Status'
                      );
            $session_data = $this->session->userdata('logged_in');
            updatelog($log,$session_data);


            $this->session->set_flashdata('message', array('message' => ' Order Cancelled  Successfully ','class' => 'success'));
            redirect(base_url().'Allorders/neworders');
        }



        public function sendpush($customer_id,$fcm_data){


             $key = 'AAAAzB0NRgw:APA91bE8BFXH7biQ9KBfEZkW1qLMM4liVPPkDwVt9pM8Zva4HG5IVLqi6yC6Wx80ZBZnVN12vH-Un8xHRU0rSjY95uk4hFI58MwgkEoJlO3Fo_d7h_rQcqfOO5Althay_RleII_iuF_o';

            $this->db->select('fcm_token');
            $this->db->where('customer_id',$customer_id);
            $this->db->distinct('fcm_token');
            $query = $this->db->get('fcmtokens');

            foreach ($query->result() as $row){
                
                            $data = "
                                  { 
                                    \"data\" :
                                            {  
                                                 \"order_id\" : \"".$fcm_data['id']."\",
                                                 \"type\" : \"".$fcm_data['type']."\",
                                                 \"title\" : \"".$fcm_data['title']."\",
                                                 \"message\" : \"".$fcm_data['message']."\",
                                                 \"order_status\" : 0
                                                 },
                                    \"to\" : \"".$row->fcm_token."\"
                                  }
                              ";


                            $ch = curl_init("https://fcm.googleapis.com/fcm/send");

                            $header = array('Content-Type: application/json', 'Authorization: key='.$key);

                            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                            curl_setopt($ch, CURLOPT_POST, 1);

                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                            
                            $out = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                           
                            // curl_exec($ch);

                            $r = curl_exec($ch);

            }


    }


    //delivery items

    public function deliveryitems($id) {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/delivery_orders.php';
        $template['page_title'] = "View Items";
        $template['page_data'] = $this->info;
        $template['orderdata'] = $this->Allorders_model->delivery_orders($id);
        $template['no_of_orders'] =  $this->db->where('status',2)->where('delivery_date', $id)->get('orders')->num_rows();   
              //  echo "<pre>";print_r($template['orderdata']);die;
                $departments = array();

                foreach ($template['orderdata'] as $department) {

                        $departments[] = $department['department'];
                }

                $vendors = array();

                foreach ($template['orderdata'] as $vendor) {

                        $vendors[] = $vendor['vendor'];
                }

                
        $template['departments'] = array_unique($departments);
        $unique_vendors = array_unique($vendors);
        $template['vendors'] = implode(', ',  $unique_vendors); 

        $this->load->view('template',$template);
    }

    public function deliveryinvoice_generate($id=null){
                $template['orderdata'] = $this->Allorders_model->delivery_orders($id);
        $template['no_of_orders'] =  $this->db->where('status',2)->where('delivery_date', $id)->get('orders')->num_rows();   
              //  echo "<pre>";print_r($template['orderdata']);die;
                $departments = array();

                foreach ($template['orderdata'] as $department) {

                        $departments[] = $department['department'];
                }

                $vendors = array();

                foreach ($template['orderdata'] as $vendor) {

                        $vendors[] = $vendor['vendor'];
                }

                
        $template['departments'] = array_unique($departments);
        $unique_vendors = array_unique($vendors);
        $template['vendors'] = implode(', ',  $unique_vendors); 
        $this->load->view('Allorders/deliveryinvoice', $template);
    }







    //edit orders

    public function edit($id=null) {

        if($id==''){
            redirect('allorders');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Allorders/orders_edit.php';
        $template['page_title'] = "View All Orders";
        $template['page_data'] = $this->info;
        $template['order_id'] = $id;
        $template['data'] = $this->Allorders_model->view_popup_order($id);
        $template['order'] = $this->Allorders_model->view_popup_order_details($id);
  
        $this->load->view('template',$template);
    }





    //delete order item


    public function remove(){
            
            $order_id = $this->uri->segment(3);

            $product_id = $this->uri->segment(4);


             $postdata = array('order_id' => $order_id, 'product_id' => $product_id);

             $function = 'itemremove_snapshot';
         
             ApiCallPost($function, $postdata);

             redirect(base_url() . 'allorders/edit/'.$order_id);


        }




        public function post() {

        print_r($_POST);
            echo "string";
        if($_POST) {
            $data = $_POST;
            print_r($data);die;

            $delivery_date = date("Y-m-d", strtotime($data['delivery_date']));  
            redirect(base_url().'allorders/deliveryitems/'.$delivery_date);
        }



       
    }


























} 
?>






