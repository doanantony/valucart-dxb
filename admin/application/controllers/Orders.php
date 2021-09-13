<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Orders_model');
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


    /* === VIEW ORDERS === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Orders/orders_view';
        $template['page_title'] = "View Orders";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Orders_model->get_orders();
        $this->load->view('template',$template);
    }


    public function get_all_orders(){
        $data = $_GET;

        $columns = array("id","user_type_id","user_id","log","ip_adress","date_time");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

        $search_box = array('user_type_id','user_id','log','ip_adress');

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

        
        $activity = $this->Orders_model->get_orders($value);
        $all_activity = $this->Orders_model->get_orders();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Orders_model->get_orders($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->customer_id,
                    $r->payment_type,
                    $r->payment_status,
                    $r->sub_total_price,
                //    $r->date_time,
                    //anchor('test/view/' . $r->id, 'View'),
                    /*"<a class='btn btn-sm btn-primary' href='".base_url()."activity/activity_edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit </a> 
                     <a class='btn btn-sm btn-danger' href='".base_url()."activity/activity_delete/".$r->id."'> <i class='fa fa-fw fa-trash'></i> Delete </a>"*/
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    
    public function order_viewpopup() {  

        $id=$_POST['patientdetailsval'];
        $template['data'] = $this->Orders_model->view_popup_order($id);
        $template['order'] = $this->Orders_model->view_popup_order_details($id);
        $this->load->view('Orders/orders-view-popup',$template);

    }





      public function shipped(){
            $data1 = array(
                  "status" => '3'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Orders_model->update_order_status($id, $data1);
            
            $query = $this->db->where('id', $id)->get('orders')->row();
            $order_id = $query->order_reference;
            $order_price = $query->price;
            $customer_id = $query->customer_id;
            $res = $this->db->where('id', $customer_id)->get('customers')->row();
            $customer_name = $res->name;
            
           
            $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER SHIPPED', 'message' => 'Hello ' .$customer_name. '! your order of AED' .$order_price.' was successfuly Shipped and our captain is on his way😊');

            $this->sendpush($customer_id,$fcm_data);

            $this->session->set_flashdata('message', array('message' => ' Order Shipped Successfully ','class' => 'warning'));
            redirect(base_url().'orders');
        }

        public function deliverd(){
            $data1 = array(
                  "status" => '4'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Orders_model->update_order_status($id, $data1);

            $query = $this->db->where('id', $id)->get('orders')->row();
            $order_id = $query->order_reference;
            $order_price = $query->price;
            $customer_id = $query->customer_id;
            $res = $this->db->where('id', $customer_id)->get('customers')->row();
            $customer_name = $res->name;
           
           
            $fcm_data = array('type' => 'orderstatus' ,'id' => $order_id, 'title' => 'ORDER DELIVERED', 'message' => 'Hello ' .$customer_name. '! Thank you for shopping with Valucart . Kindly keep in touch with us on valucart.ae on fb /instagram 😊!');

            $this->sendpush($customer_id,$fcm_data);



            $this->session->set_flashdata('message', array('message' => ' Order Delivered  Successfully ','class' => 'success'));
            redirect(base_url().'orders');
        }


        public function cancel_order(){
            $data1 = array(
                  "status" => '12'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Orders_model->update_order_status($id, $data1);
            $this->session->set_flashdata('message', array('message' => ' Order Cancelled  Successfully ','class' => 'danger'));
            redirect(base_url().'orders');
        }





        public function changestatus(){
            $data = $_POST;
            $data1 = array(
                  "status" => $data['myselect']
                 );
            $id = $this->uri->segment(3);

            $s=$this->Orders_model->update_order_status($id, $data1);
            $this->session->set_flashdata('message', array('message' => ' Order Status Changed  Successfully ','class' => 'danger'));
            redirect(base_url().'orders');
}




      function mypdf(){

    $this->load->library('pdf');


    $this->pdf->load_view('orders/orders_view');
    $this->pdf->render();


    $this->pdf->stream("welcome.pdf");
   }



   //

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










} 
