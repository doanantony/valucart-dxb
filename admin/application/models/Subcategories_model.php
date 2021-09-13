<?php 
class Subcategories_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_subcategories(){
        $query =$this->db->get('subcategories');
       // $query = $this->db->order_by("name", "asc");
        $result = $query->result();
        return $result;
    }


    function save_subcategories($data,$object_id) {
     
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where('category_id', $data['category_id']);
        $this->db->from('subcategories');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('subcategories', $data); 

            $insert_id = $this->db->insert_id();
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created SubCategory '.$data['name']. ''
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


    function get_single_subcategories($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('subcategories');
        $result = $query->row();
        return $result;
    }   


    function update_subcategories($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('subcategories');
        $data['updated_at'] = date("Y-m-d H:i:s");
        $count = $this->db->count_all_results();
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);

            $result = $this->db->update('subcategories', $data); 
                        $log = array(
                         'id' =>$id,
                         'log' => 'Updated SubCategory '.$data['name']. ''
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



            /* === Update subCategories === */
    function update_subcategories_status($id,$data){
        $this->db->where('id',$id);
        $result = $this->db->update('subcategories',$data);
        return $result;
    }





    
}
