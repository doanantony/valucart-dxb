<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admincart extends CI_Controller {




    public function __construct()
    {
        parent::__construct();
        $this->load->library('cart');
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        if($method == 'add' || $method == 'remove' || $method == 'update_cart' || $method == 'billing_view' || $method == 'save_order' || $method == 'opencart' || $method == 'view' || $method == 'orderdetails'){
            $method = 'index';
        }
        $this->info = get_function($class,$method);
        $this->load->model('Placeorder_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }


        $role_id = $this->session->userdata('user_type_id');

        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
        $this->load->library('cart');
        $this->load->model('cart_model');
    }





    public function index()
    {   
        

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page_data'] = $this->info;
        $template['page'] = 'Admincart/placeorder_view';
        $template['page_title'] = "View Admincarts";

        //Get all data from database
        $template['products'] = $this->cart_model->get_all();
        $template['data'] = $this->cart_model->get_bundles();
        $this->load->view('template',$template);
    }



    public function get_all_products(){
        $data = $_GET;

        $columns = array("id","name","sku","brand_id","department_id","category_id");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

        $search_box = array('name','sku','brand_id','department_id','category_id');

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

      
        $activity = $this->cart_model->get_allproducts($value);
        $all_activity = $this->cart_model->get_allproducts();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->cart_model->get_allproducts($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    // $r->id,
                    $r->id,
                    $r->name,
                  
                    "<div class='price-label price $r->id' 
              rel='$r->price'>AED $r->price</>",
                    "

                  <p hidden class='name$r->id'' 
                  rel='$r->id'>$r->name</p>
                  <p class='price$r->id'' 
                  rel='$r->price'></p>

                  <p class='pro$r->id'' 
                  rel=pro></p>

                  <a class='btn btn-primary'  href='javascript:addtocart( $r->id );' data-id = $r->id > <i class='fa fa-fw fa-check'></i> Add to Cart </a>

                    "


                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }








    function add()
    {

      $this->cart->product_name_rules = '[:print:]';

    // Set array for send data.
    $insert_data = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'price' => $this->input->post('price'),
                'image' => $this->input->post('image'),
                'type' => $this->input->post('type'),
                'qty' => 1
                );

    // This function add items into cart.
    $this->cart->insert($insert_data);

   // print_r($insert_data);
    echo $fefe = count($this->cart->contents());
    // This will show insert data in cart.
    }

    


    function remove() {
    $rowid = $this->input->post('rowid');
    // Check rowid value.
    if ($rowid==="all"){
    // Destroy data which store in session.
        $this->cart->destroy();
    }else{
    // Destroy selected rowid in session.
    $data = array(
            'rowid' => $rowid,
            'qty' => 0
            );
    // Update cart data, after cancel.
    $this->cart->update($data);
    }
    echo $fefe = count($this->cart->contents());
    // This will show cancel data in cart.
    }




    function update_cart(){
    // Recieve post values,calcute them and update
    $rowid = $_POST['rowid'];
    $price = $_POST['price'];
    $amount = $price * $_POST['qty'];
    $qty = $_POST['qty'];

    $data = array(
        'rowid' => $rowid,
        'price' => $price,
        'amount' => $amount,
        'qty' => $qty
        );
    $this->cart->update($data);
    echo $data['amount'];
    }





    public function opencart() {  

        $id=$_POST['patientdetailsval'];

        $template['cart']  = $this->cart->contents();
        $this->load->view('Admincart/cart_modal',$template);

    }


    //billing

    public function billing_view() {

        $template['cart']  = $this->cart->contents();
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['timeslots'] = $this->db->get('delivery_time_slots')->result();
        $template['page'] = 'Admincart/billing.php';
        $template['page_title'] = "View Billing";
       
        if($_POST) {

            $customerdata = $_POST;

            if ($cart = $this->cart->contents()){

            $products = array();

            $bundles = array();

            foreach ($cart as $item){   
                
                $product_details = $this->cart_model->products_detais($item['id']);

                    if($item['type'] == 'pro'){

                        $item['vendor'] = $product_details->vendor;

                        $item['product_details'] = $product_details;

                        $products[] = $item;

                    }

                    if($item['type'] == 'bundle'){

                        $bundles[] = $item;

                    }
                }
                   
            }

            $code = "ValuCart";            
            $rand = rand(1111, 99999);
            $order_reference = "$code"."$rand";


                $grand_total = 0;
                        // Calculate grand total.
                    if ($cart = $this->cart->contents()):
                      foreach ($cart as $data):
                        $grand_total = $grand_total + $data['subtotal'];
                        $delivery_charge = 7.5;
                        $total = $grand_total + $delivery_charge;
                      endforeach;
                endif;


            $sub_total = $total - 7.5;
                
            $order_snapshot = [

                "created_at" => date("Y-m-d H:i:s"),
                "reference" => $order_reference,
                "sub_total" => round($sub_total, 2),
                "delivery_charge" => round($delivery_charge, 2),
                "delivery_date" => $customerdata['delivery_date'],
                "delivery_time" =>$customerdata['delivery_time'],
                "delivery_adress" =>$customerdata['adress'],
                "discount" => 0,
                "vat" => "5%",
                "total" => round($total, 2),
                "customer" => [
                    "name" => $customerdata['name'],
                    "telephone" =>$customerdata['phone_no'],
                    "email" => $customerdata['email'],
                ],
                'products' => $products,
                'bundles' => $bundles

            ];


            // echo "<pre>";
            // print_r($order_snapshot);die;
            $result = $this->cart_model->placeorder($order_snapshot);
            $this->customer_mail($result);
            $this->sales_mail($result);

            if($result) {
               
                $this->cart->destroy();

                $this->session->set_flashdata('message', array('message' => 'Order has been placed successfully','class' => 'success'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Oops! SOmething Went Wrong','class' => 'danger'));
            }

            redirect(base_url().'Admincart/view');


            
        }
        $this->load->view('template', $template);
    }



    //send email for customer
            public function customer_mail($id)
        {   

            $query = $this->db->where('id', $id);
            $query = $this->db->get('adminorders');
            $result = $query->row();
            
            $order_snapshot = unserialize($result->snapshots);
            $template['page'] = 'email_templates/order_success';
            $template['email_data'] = $order_snapshot;
            $this->load->view('template',$template);
          
            $this->emailConfig();
            $subject        = ' Order Placed';
            $name           = 'Valucart customer service';
            $to             = 'doanantony@valucart.com';
            $mailTemplate   = $this->load->view('email_templates/order_success',$template, true);

            $this->email->from($sender_email, $name);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($mailTemplate);
            $var = $this->email->send();
           


        }


        //send email to sales
            public function sales_mail($id)
        {   

            $query = $this->db->where('id', $id);
            $query = $this->db->get('adminorders');
            $result = $query->row();
            
            $order_snapshot = unserialize($result->snapshots);
            $template['page'] = 'email_templates/sales_order_success';
            $template['email_data'] = $order_snapshot;
            $this->load->view('template',$template);
          

            
            $this->emailConfig();
            $subject        = 'Sales Order Placed';
            $name           = 'Valucart customer service';
            $to             = 'mailtomedonantony@gmail.com';
            $mailTemplate   = $this->load->view('email_templates/sales_order_success',$template, true);

            $this->email->from($sender_email, $name);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($mailTemplate);
            $var = $this->email->send();
           


        }




    private function emailConfig()
        {
            $config = array(
                'protocol'  => 'smtp' , 
                'smtp_crypto' => 'tls',
                'smtp_host' => 'smtp.gmail.com' , 
                'smtp_port' => 587, 
                'smtp_user' => 'noreply@valucart.com' ,
                'smtp_pass' => 'noreply@VC',
                'mailtype'  => 'html', 
                'charset'   => 'utf-8', 
                'newline'   => "\r\n",  
                'wordwrap'  => TRUE 
                );
            
            // Load email library and passing configured values to email library
            $this->load->library('email',$config);
        }


    //view orders

    public function view() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Admincart/orders_view.php';
        $template['page_title'] = "View Admin Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->cart_model->get_allorders();

        $this->load->view('template',$template);
    }



    public function get_all_orders(){
        $data = $_GET;

        $columns = array('order_reference','admin_type_id','admin_type_id','admin_type_id','total_price','status','created_at');
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

      
        $activity = $this->cart_model->get_allorders($value);
        $all_activity = $this->cart_model->get_allorders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->cart_model->get_allorders($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    $r->admin_name,
                    $r->total_price,
                    // $r->delivery_date,
                   
                   // <a class='btn btn-xs btn-primary' href='".base_url()."allorders/edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit</a>
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    $r->created_at,
                    "<a class='btn btn-xs btn-success' href='".base_url()."admincart/orderdetails/".$r->id."'> <i class='fa fa-fw fa-eye'></i> View</a>
                    
                    <a class='btn btn-xs btn-danger' href='".base_url()."admincart/shipped/".$r->id."'> <i class='fa fa-check-square-o'></i> Shipped</a>
                    <a class='btn btn-xs btn-info' href='".base_url()."admincart/deliverd/".$r->id."'> <i class='fa fa-check-square-o'></i> Delivered</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }




    public function shipped(){
            $data1 = array(
                  "status" => '2'
                 );
            $id = $this->uri->segment(3);
            $s=$this->cart_model->update_order_status($id, $data1);
            
            $query = $this->db->where('id', $id)->get('adminorders')->row();
            $order_id = $query->order_reference;


            $session_data = $this->session->userdata('logged_in');

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Admin Order '.$order_id. ' to Shipped Status'
                      );
           updatelog($log,$session_data);

            $this->session->set_flashdata('message', array('message' => ' Order Shipped Successfully ','class' => 'warning'));
            redirect(base_url().'admincart/view');
        }



        public function deliverd(){
            $data1 = array(
                  "status" => '3'
                 );
            $id = $this->uri->segment(3);
            $s=$this->cart_model->update_order_status($id, $data1);

            $query = $this->db->where('id', $id)->get('adminorders')->row();
            $order_id = $query->order_reference;

        

                    $log = array(
                         'id' =>$id,
                         'log' => 'Changed Admin Order '.$order_id. ' to Delivered Status'
                      );
            $session_data = $this->session->userdata('logged_in');
            updatelog($log,$session_data);


            $this->session->set_flashdata('message', array('message' => ' Order Delivered  Successfully ','class' => 'success'));
            redirect(base_url().'admincart/view');
        }





    public function orderdetails($id=null) {

        if($id==''){
            redirect('admincart/view');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Admincart/singlorder_view.php';
        $template['page_title'] = "View All Orders";
        $template['page_data'] = $this->info;
        $template['order_id'] = $id;
        $template['data'] = $this->cart_model->view_popup_order($id);

       // $template['order'] = $this->cart_model->view_popup_order_details($id);
     
     // echo "<pre>"; print_r($template['data']);die;
        $this->load->view('template',$template);
    }













    }