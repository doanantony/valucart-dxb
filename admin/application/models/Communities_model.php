<?php 
class Communities_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_communities(){
        
       // $query = $this->db->order_by("id", "desc");
        $query =$this->db->get('communities');
        $result = $query->result();
       // echo $this->db->last_query();die;
        return $result;
    }


    function save_communities($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('communities');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('communities', $data); 

            $insert_id = $this->db->insert_id();
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Community '.$data['name']. ''
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


    function get_single_communities($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('communities');
        $result = $query->row();
        return $result;
    }   


    function update_communities($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('communities');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('communities', $data); 
            
                    
            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Community '.$data['name']. ''
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
