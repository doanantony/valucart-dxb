<?php 
class Allorders_model extends CI_Model {
    public function _consruct(){
        parent::_construct();


    }


    function get_allorders($filter=null){
        $session_data = $this->session->userdata('logged_in');
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("orders.id","desc");
            $this->db->order_by("orders.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
        if($session_data['user_type_id'] == 5){
            $user_id =  $this->session->userdata['logged_in']['user_id'];
            $this->db->where('vendor_id',$user_id);
        }

         $this->db->select("orders.id,orders.order_reference,orders.customer_id,orders.price,(CASE orders.status WHEN 1 THEN 'Order Created' WHEN 2 THEN 'Order Placed' WHEN 3 THEN 'Order Shipped' ELSE 'Order Delivered' END) AS status,(CASE orders.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,orders.created_at,orders.updated_at");
        $this->db->order_by("orders.id","desc");
        $this->db->from("orders");
       
        $result = $this->db->get()->result();
      
        return $result;
    }



    function get_createdorders($filter=null){
        $session_data = $this->session->userdata('logged_in');
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("orders.id","desc");
            $this->db->order_by("orders.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
            $this->db->where('vendor_id',$user_id);
        }
        $this->db->where('status',1); 
        //$this->db->or_where('status',2); 
         $this->db->select("orders.id,orders.order_reference,orders.customer_id,orders.price,(CASE orders.status WHEN 1 THEN 'Order Created' WHEN 2 THEN 'Order Placed' WHEN 3 THEN 'Order Shipped' ELSE 'Order Delivered' END) AS status,(CASE orders.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,orders.created_at,orders.updated_at");
        $this->db->order_by("orders.id","desc");
        $this->db->from("orders");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_neworders($filter=null){
        $session_data = $this->session->userdata('logged_in');
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("orders.id","desc");
            $this->db->order_by("orders.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
      if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
            $this->db->where('vendor_id',$user_id);
        }
        $this->db->where('status',2); 
      //  $this->db->or_where('status',2); 
         $this->db->select("orders.id,orders.order_reference,orders.customer_id,orders.price,(CASE orders.status WHEN 1 THEN 'Order Created' WHEN 2 THEN 'Order Placed' WHEN 3 THEN 'Order Shipped' ELSE 'Order Delivered' END) AS status,(CASE orders.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,orders.created_at,orders.updated_at");
        $this->db->order_by("orders.id","desc");
        $this->db->from("orders");
        $result = $this->db->get()->result();
        return $result;
    }


    function get_shippedorders($filter=null){
        $session_data = $this->session->userdata('logged_in');
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("orders.id","desc");
            $this->db->order_by("orders.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
            $this->db->where('vendor_id',$user_id);
        }
        $this->db->where('status',3); 
         $this->db->select("orders.id,orders.order_reference,orders.customer_id,orders.price,(CASE orders.status WHEN 1 THEN 'Order Created' WHEN 2 THEN 'Order Placed' WHEN 3 THEN 'Order Shipped' ELSE 'Order Delivered' END) AS status,(CASE orders.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,orders.created_at,orders.updated_at");
        $this->db->order_by("orders.id","desc");
        $this->db->from("orders");
        $result = $this->db->get()->result();
        return $result;
    }


    function get_deliveredorders($filter=null){
        $session_data = $this->session->userdata('logged_in');
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("orders.id","desc");
            $this->db->order_by("orders.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
            $this->db->where('vendor_id',$user_id);
        }
        $this->db->where('status',4); 
         $this->db->select("orders.id,orders.order_reference,orders.customer_id,orders.price,(CASE orders.status WHEN 1 THEN 'Order Created' WHEN 2 THEN 'Order Placed' WHEN 3 THEN 'Order Shipped' ELSE 'Order Delivered' END) AS status,(CASE orders.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,orders.created_at,orders.updated_at");
        $this->db->order_by("orders.id","desc");
        $this->db->from("orders");
        $result = $this->db->get()->result();
        return $result;
    }



    function get_cancelledorders($filter=null){
        $session_data = $this->session->userdata('logged_in');
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("orders.id","desc");
            $this->db->order_by("orders.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
            $this->db->where('vendor_id',$user_id);
        }
        $this->db->where('status',19); 
         $this->db->select("orders.id,orders.order_reference,orders.customer_id,orders.price,(CASE orders.status WHEN 1 THEN 'Order Created' WHEN 2 THEN 'Order Placed' WHEN 3 THEN 'Order Shipped' WHEN 4 THEN 'Order Delivered' ELSE 'Order Cancelled' END) AS status,(CASE orders.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,orders.created_at,orders.updated_at");
        $this->db->order_by("orders.id","desc");
        $this->db->from("orders");
        $result = $this->db->get()->result();
        return $result;
    }



    function view_popup_order($id){

        $function = 'show_single_order/' . $id;
         
        $res = ApiCallGet($function);
        
        return $res;

     }

    function view_popup_order_details($id){
        
        $query = $this->db->where('id', $id);
        $query = $this->db->get('orders');
        $result = $query->row();
        return $result;     

     }




    function update_order_status($id,$data){

        $this->db->where('id',$id);
        $result = $this->db->update('orders',$data);
        return $result;
    }



    function delivery_orders($delivery_date){

        $query = $this->db->where('delivery_date', $delivery_date);
        $query = $this->db->where('status', 2);
        $query = $this->db->select('snapshots');
        $query = $this->db->get('orders');
        $results = $query->result_array();
        $items = array();
        foreach ($results as $snap) {
            
             $snap = $snap['snapshots'];
             $data = unserialize($snap);
             $products = $data['products'];
             foreach ($products as $pro ) {
                $pro["order_reference"] = $data['reference'];
                $items[] = $pro;
             }
         
        }
        return $items;     

     }







    
}
