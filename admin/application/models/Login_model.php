<?php 
class Login_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }
     function login($username, $password) {
         $this->db->where('username',$username);                     
         $this->db->where('passwd',$password);                           
         $query=$this->db->get('users');
        //echo $this->db->last_query();die;
         $query_value=$query->row();
        if ($query -> num_rows() == 1) {
            return $query_value;
        }
    }




        function save_activity() {
        
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $user_ip = get_client_ip();
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    //'object_id' => $object_id,
                                    'log' => 'User Logged In', //action
                                   // 'edited_id' => $id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);
            //echo $this->db->last_query();die;
            return true;
            
    }










}