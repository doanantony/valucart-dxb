<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Customers_model');
        if($method == 'view_profile'){
            $method = 'index';
        }
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW CUSTOMERS === */
    public function index() {

       // $this->apicall();

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Customers/customers_view';
        $template['page_title'] = "View Customers";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Customers_model->get_allcustomers();
        $this->load->view('template',$template);

    }


        public function get_all_customers(){
        $data = $_GET;

        $columns = array("id","name","email","contact_email","phone_number","created_at");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

        $search_box = array('name','email','contact_email','phone_number','created_at');

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

      
        $activity = $this->Customers_model->get_allcustomers($value);
        $all_activity = $this->Customers_model->get_allcustomers();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Customers_model->get_allcustomers($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(

                    $r->id,
                    $r->name,
                    $r->email,
                    $r->contact_email,
                    $r->phone_number,
                    $r->created_at,
                    "<a class='btn btn-xs btn-success' href='".base_url()."customers/view_profile/".$r->id."'> <i class='fa fa-fw fa-edit'></i> View Profile </a>"

                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }



    /* === VIEW CUSTOMERS PROFILE=== */
    public function view_profile ($id=null) {
        if($id==''){
            redirect('customers');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Customers/customers_profile';
        $template['page_title'] = "View Profile";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Customers_model->get_single_customer($id);
        $template['wallet_info'] = $this->Customers_model->get_wallet_info($id);
        $template['cart'] = $this->Customers_model->get_cart($id);
        if($_POST) {
           
            $data = $_POST;
            $result = $this->Customers_model->topupwallet($data,$id);

            if($result == "Success") {
                $this->session->set_flashdata('message', array('message' => 'Wallet has been recharged Successfully','class' => 'success'));
                redirect(base_url().'customers/'.'view_profile/'.$id);
            }


        }
        $this->load->view('template',$template);

    }

















} 
