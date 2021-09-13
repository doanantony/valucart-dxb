<?php 
class Orders_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_orders(){
     //   $query =$this->db->get('orders');
        //$query = $this->db->where("status!=",1 );
        $query = $this->db->get('orders');
        $result = $query->result();
        return $result;
    }


    function view_popup_order($id){
        $res = $this->fetchorder($id);
        return $res;

     }

    function view_popup_order_details($id){
        
        $query = $this->db->where('id', $id);
        $query = $this->db->get('orders');
        $result = $query->row();
        return $result;     

     }






function fetchorder($id)
{       

    $url = "http://v2api.valucart.com/show_single_order/$id";
   // $url = "http://testing.v2.api.valucart.com/show_single_order/$id";
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($ch);
 
    curl_close($ch);


    $json_data = json_decode($output, true);
    // echo "<pre>";
    // print_r($json_data);die;
    return $json_data;

}





    function update_order_status($id,$data){
        $this->db->where('id',$id);
        $result = $this->db->update('orders',$data);
     //   echo $this->db->last_query();die;
        return $result;
    }
















    function save_areas($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('areas');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('areas', $data); 

            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $session_data = $this->session->userdata('logged_in');
            $date_time = date('Y-m-d H:i:s');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'New  area Created', //action
                                    'edited_id' => $insert_id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);
            if($res) {
                return "Success";
            }
            else {
                return "Error";
            }
        }
    }


    function get_single_areas($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('areas');
        $result = $query->row();
        return $result;
    }   


    function update_areas($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('areas');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('areas', $data); 
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'Existing Area Updated', //action
                                    'edited_id' => $id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);
            if($res) {
                return "Success";
            }
            else {
                return "Error";
            }
        }
    }




    function get_orderslist() {


      //  $query = $this->db->where('published','0');
        $query = $this->db->get('orders');
        $result = $query->result();

        return $result;

}





    
}
