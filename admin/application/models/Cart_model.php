<?php
class Cart_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all(){
        $query=$this->db->query("SELECT products.id,products.name,products.valucart_price AS price FROM products WHERE products.published = '1'
                                 ORDER BY products.id ASC");
    //  echo $this->db->last_query();
        return $query->result_array();
    }

    // Insert customer details in "customer" table in database.
    public function insert_customer($data)
    {
        $this->db->insert('customers', $data);
        $id = $this->db->insert_id();
        return (isset($id)) ? $id : FALSE;
    }

    // Insert order date with customer id in "orders" table in database.
    public function insert_order($data)
    {
        $this->db->insert('orders', $data);
        $id = $this->db->insert_id();
        return (isset($id)) ? $id : FALSE;
    }

    // Insert ordered product detail in "order_detail" table in database.
    public function insert_order_detail($data)
    {
        $this->db->insert('order_detail', $data);
    }



    function get_allproducts($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("products.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

        $this->db->select("products.id,products.name,products.sku,products.brand_id,products.department_id,products.category_id,products.subcategory_id,products.maximum_selling_price,products.valucart_price AS price");
        $this->db->where('published', '1');
        $this->db->from("products");
        
        $result = $this->db->get()->result();
    
        return $result;
    }


    function get_bundles(){
        
        $this->db->select("bundles.id,bundles.name,bundles.category_id,bundles.status,bundles.valucart_price AS price");
        $this->db->from("bundles");
        $result = $this->db->get()->result();

        return $result;
    }



    function products_detais($id) {

        $query = $this->db->where('id', $id);

        $query = $this->db->select("products.id,products.name,products.sku,products.brand_id,products.department_id,products.category_id,products.subcategory_id,products.maximum_selling_price,products.valucart_price");

        $query = $this->db->get('products');

        $result = $query->row();

        $vendor_name =get_vendor_name($id);

        $result->vendor = $vendor_name;

        return $result;

    }  



    function placeorder($order_snapshot) {

        $session_data = $this->session->userdata('logged_in');

        $admin_name = $this->master($session_data['user_type_id'],$session_data['user_id']);

        $created_at = date("Y-m-d H:i:s");

        $snapshot = serialize($order_snapshot);

        $data = array(
                        'admin_type_id' => $session_data['user_type_id'],
                        'admin_name' => $admin_name,
                        'order_reference' => $order_snapshot['reference'],
                        'status' => 1,
                        'discount' => 0,
                        'subtotal_price' => $order_snapshot['sub_total'],
                        'total_price' => $order_snapshot['total'],
                        'delivery_date' => $order_snapshot['delivery_date'],
                        'time_slot_id' => 1,
                        'created_at' => $created_at,
                        'updated_at' => $created_at,
                        'snapshots' => $snapshot,
                        
                    );
        $result = $this->db->insert('adminorders', $data); 
        
        $insert_id = $this->db->insert_id();
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Placed New Order For Customer: '.$order_snapshot['customer']['name']. ''
                      );
        
        $res = updatelog($log,$session_data);



        if($result) {

                return $insert_id;
            }

            else {
                return "Error";
            }

    }   




    function get_allorders($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("adminorders.id","desc");
            $this->db->order_by("adminorders.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }



         $this->db->select("adminorders.id,adminorders.order_reference,adminorders.admin_name,adminorders.total_price,adminorders.delivery_date,(CASE adminorders.status WHEN 1 THEN 'Order Placed' WHEN 2 THEN 'Order Shipped' WHEN 3 THEN 'Order Delivered' ELSE 'Order Delivered' END) AS status,(CASE adminorders.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,adminorders.created_at");
        $this->db->order_by("adminorders.id","desc");
        $this->db->from("adminorders");
       
        
        $result = $this->db->get()->result();
       //echo $this->db->last_query();
      
        return $result;
    }




    public function master($user_type,$id) {
        $table = $user_type;
        switch ($table) {
            case "1":
                $result = GetMasterInfo($table = 'super_admin',$id);
                return $result;
            case "2":
                $result = GetMasterInfo($table = 'managementteam',$id);
                return $result;
            case "3":
                $result = GetMasterInfo($table = 'marketingteam',$id);
                return $result;
            case "6":
                $result = GetMasterInfo($table = 'techteam',$id);
                return $result;
            default:
                $result = array('status' => 0, 'message' => 'Invalid Type');
                response($result);
        }
    }


    function update_order_status($id,$data){

        $this->db->where('id',$id);
        $result = $this->db->update('adminorders',$data);
        return $result;
    }


    function view_popup_order($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('adminorders');
        $result = $query->row();
        $snapshot = unserialize($result->snapshots);

        return $snapshot;
    }  









}