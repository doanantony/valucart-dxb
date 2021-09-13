<?php 
class Vendorcategories_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_vendorcategories(){
       $query =$this->db->get('departments_categories');
      //   $query = $this->db->order_by("id","asc")->get('bundles_categories');
        //$query = $this->db->order_by("name", "asc");
        $result = $query->result();
        return $result;
    }


    function save($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('departments_categories');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('departments_categories', $data); 

            $insert_id = $this->db->insert_id();
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Vendor Category '.$data['name']. ''
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


    function get_single_vendorcategories($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('departments_categories');
        $result = $query->row();
        return $result;
    }   


    function update($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('departments_categories');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('departments_categories', $data); 
            
            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Vendor Category '.$data['name']. ''
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



    public function delete_bundlecat($id){

        $result = $this->db->where('id',$id)->delete('bundles_categories');
        if($result){
            return "success"; 
        }
        else{
             return "error";
        }
    }





    
}
