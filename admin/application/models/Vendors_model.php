<?php 
class Vendors_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
        
    }

    function get_vendors(){
        $session_data = $this->session->userdata('logged_in');
       
        if($session_data['user_type_id'] == 5){
            $query =$this->db->where('id', $session_data['user_id'])->get('departments');
        }else{
            $query =$this->db->get('departments');
        }
        $result = $query->result();
        return $result;
    }

    function get_products(){
        $session_data = $this->session->userdata('logged_in');
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
        }
        $query = $this->db->limit(4); 
        $query = $this->db->where('department_id', $user_id);
        $query = $this->db->order_by("id","desc")->get('products');
        $result = $query->result();
        return $result;
    }

    function get_orders(){
        $session_data = $this->session->userdata('logged_in');
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
        }
        $query = $this->db->limit(5); 
        $this->db->where('vendor_id', $user_id);
        $query = $this->db->order_by("id","desc")->get('orders');
        $result = $query->result();
        return $result;
    }

    function save_vendors($data,$object_id) {

        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('departments');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
         //  echo "<pre>";print_r($data);die;
        if($count > 0) {
            return "Exist";
        }
        else {
            $vendor_data = array(
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'latitude' => $data['lat'],
                                'longitude' => $data['lng'],
                           //     'category_id' => $data['category_id'],
                                'image' => $data['image'],
                                'user_type_id' => 5,
                                'created_at' => $data['created_at'],
                                'updated_at' => $data['updated_at']

                            );
            $result = $this->db->insert('departments', $vendor_data); 
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();

                $departments_category = array(
                        'category_id' =>$data['category_id'],
                        'department_id' => $insert_id,);
                $this->db->insert('department_category_department', $departments_category); 

                $settings = array(
                        'vendor_id' =>$insert_id,
                        'minimum_order' => 100,
                        'freedelivery_minimum_order' =>100000000,
                        'delivery_charge' =>7.14,
                        'vat' => 5,
                        'max_delivery_time_deliveries' => 15
                    );
                $this->db->insert('system_settings', $settings); 


                $rs = array(
                        'company_id' => '1',
                        'user_id' => $insert_id,
                        'user_type_id' => '5',
                        'username' => $data['username'],
                        'passwd' => md5($data['password']));
            $this->db->insert('users', $rs); 
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Vendor '.$data['name']. ''
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);
            if($res) {
                return $insert_id;
            }
            else {
                return "Error";
            }
        }
    }



    function get_single_vendors($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('vendors');
        $result = $query->row();
        return $result;
    } 

    function view_popup_vendor($id){
        
        $query = $this->db->where('id', $id);
        $query = $this->db->get('departments');
        $result = $query->row();
        return $result;     

     }


     function get_vendors_settings($id){
        $query = $this->db->where('vendor_id', $id);
        $query = $this->db->get('system_settings');
        $result = $query->row();
        return $result; 
    }








    
}
