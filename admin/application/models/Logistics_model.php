<?php 
class Logistics_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_logistics(){
        $query = $this->db->get('logisticsteam');
        $result = $query->result();
        return $result;
    }


    function save_logistics($data,$object_id) {
        
        $this->db->where('email', $data['email']);
        $this->db->or_where('phone_no',$data['phone_no']);
        $this->db->from('logisticsteam');
        $count = $this->db->count_all_results();

        $this->db->where('username', $data['username']);
        $this->db->from('users');
        $count_user = $this->db->count_all_results();


        if($count > 0 || $count_user >  0) {
            return "Exist";
        }
        else {
            $user_ip = get_client_ip();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $log_data = array(
                                    'first_name' => $data['first_name'], 
                                    'last_name' => $data['last_name'], 
                                    'email' => $data['email'], 
                                    'phone_no' => $data['phone_no'], 
                                    'status' => $data['status']!=''?$data['status']:1, 
                                    'user_act_id' => $session_data['id'],
                                    'created_at' => $date_time,
                                    'updated_at' => $date_time
                                    );
          
            $this->db->insert('logisticsteam', $log_data); 

            $insert_id = $this->db->insert_id();
            $session_data = $this->session->userdata('logged_in');
            
            // $where = array('company_id' => '1',
            //                 'user_id' => $insert_id,
            //                 'user_type_id' => '2');

            $rs = array(
                        'company_id' => '1',
                        'user_id' => $insert_id,
                        'user_type_id' => '4',
                        'username' => $data['username'],
                        'passwd' => md5($data['password']));
            $this->db->insert('users', $rs); 


            
                    $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Logistics Member '.$data['first_name']. ''
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);



            if($res) {
                return "Success";
            }
            else {
                return "Error";
            }
        }
    }


    function get_single_logistics($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('logisticsteam');
        $result = $query->row();
        return $result;
    }   


    function update_logistics($data, $id,$object_id) {
        $this->db->where("id !=",$id);
        $this->db->where(" (`email` = '".$data['email']."' OR `phone_no` = '".$data['phone_no']."')" );
        //$this->db->or_where('phone_no',$data['phone_no']);
        $this->db->from('logisticsteam');
       
        $count = $this->db->count_all_results();
        if($count > 0) {
            return "Exist";
        }
        else {
            $user_ip = get_client_ip();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $log_data = array(
                                    'first_name' => $data['first_name'], 
                                    'last_name' => $data['last_name'], 
                                    'email' => $data['email'], 
                                    'phone_no' => $data['phone_no'], 
                                    'status' => $data['status'], 
                                    'user_act_id' => $session_data['id'],
                                    'updated_at' => $date_time
                                    );
            $this->db->where('id',$id);
            $this->db->update('logisticsteam', $log_data); 
            $where = array('company_id' => '1',
                            'user_id' => $id,
                            'user_type_id' => '4');
            $rs = array('username' => $data['username'],
                       // 'passwd' => md5($data['password'])
                    );
            $this->db->where($where)->update('users', $rs); 


                    $log = array(
                         'id' =>$id,
                         'log' => 'Updated Logistics Member '.$data['first_name']. ''
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);



            if($res) {
                return "Success";
            }
            else {
                return "Error";
            }
        }
    }

    
}
