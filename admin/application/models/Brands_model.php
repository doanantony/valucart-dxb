<?php 
class Brands_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_brands($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("brands.id","desc");
            $this->db->order_by("brands.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
        $this->db->select("brands.*");
        $this->db->from("brands");
        
        
        $result = $this->db->get()->result();
        
        return $result;
    }


    function save_brands($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('brands');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('brands', $data); 

            $insert_id = $this->db->insert_id();
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Brand '.$data['name']. ''
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


    function get_single_brand($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('brands');
        $result = $query->row();
        return $result;
    }   


    function update_brands($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('brands');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('brands', $data); 
                    
            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Brand '.$data['name']. ''
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
