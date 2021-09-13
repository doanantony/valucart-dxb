<?php 
class Categories_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_categories(){
        $session_data = $this->session->userdata('logged_in');
        $this->db->where('department_id',$session_data['user_id']);
        $query =$this->db->get('categories');
        $result = $query->result();
        return $result;
    }


    function save_categories($data,$object_id) {
        $session_data = $this->session->userdata('logged_in');
        $name = $data['name'];
        $this->db->where('department_id',$session_data['user_id']);
        $this->db->where('name', $name);
        $this->db->from('categories');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['department_id'] = $session_data['user_id'];
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('categories', $data); 
            $insert_id = $this->db->insert_id();
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Category '.$data['name']. ''
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


    function get_single_categories($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('categories');
        $result = $query->row();
        return $result;
    }   


    function update_categories($data, $id,$object_id) {
        $session_data = $this->session->userdata('logged_in');
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where('department_id !=',$session_data['user_id']);
        $this->db->where("id !=",$id);
        $this->db->from('categories');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('categories', $data); 
            
                    
            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Category '.$data['name']. ''
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



        /* === Update Categories === */
    function update_categories_status($id,$data){
        $this->db->where('id',$id);
        $result = $this->db->update('categories',$data);
        return $result;
    }





    
}
