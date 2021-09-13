<?php 
class States_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_states(){
        
       // $query = $this->db->order_by("id", "desc");
        $query =$this->db->get('states');
        $result = $query->result();
       // echo $this->db->last_query();die;
        return $result;
    }


    function save_states($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('states');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('states', $data); 

            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $session_data = $this->session->userdata('logged_in');
            $date_time = date('Y-m-d H:i:s');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'New  State Created', //action
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


    function get_single_states($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('states');
        $result = $query->row();
        return $result;
    }   


    function update_states($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('states');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('states', $data); 
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'Existing State Updated', //action
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

    
}
