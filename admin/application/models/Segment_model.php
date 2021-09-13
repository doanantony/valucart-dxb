<?php 
class Segment_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_segment(){
        $query = $this->db->get('object');
        $result = $query->result();
        return $result;
    }


    function save_segment($data,$object_id) {
        $type_name = $data['type'];
        $this->db->where('type', $type_name);
        $this->db->from('object');
        $count = $this->db->count_all_results();
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('object', $data); 
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $session_data = $this->session->userdata('logged_in');
            $date_time = date('Y-m-d H:i:s');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'New Segment Created', //action
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


    function get_single_segment($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('object');
        $result = $query->row();
        return $result;
    }   


    function update_segment($data, $id,$object_id) {
        $type_name = $data['type'];
        $this->db->where('type', $type_name);
        $this->db->where("id !=",$id);
        $this->db->from('object');
        $count = $this->db->count_all_results();
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('object', $data); 
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'Existing Segment Updated', //action
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
