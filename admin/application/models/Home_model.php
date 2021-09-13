<?php 
class Home_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }

    function get_terminal_info(){
        $rs = $this->db->query("SELECT departments.latitude,departments.longitude,departments.name FROM departments")->result();

        return $rs;
    }
    function get_bundles(){
        
        $query = $this->db->limit(4); 
        $query = $this->db->order_by("id","desc")->get('bundles');
        $result = $query->result();
        return $result;
    }


    function get_orders(){
        
        $query = $this->db->limit(7); 
        $query = $this->db->order_by("id","desc")->get('orders');
        $result = $query->result();
        return $result;
    }

    function get_vendors(){
        
        $query = $this->db->limit(4); 
        $query = $this->db->order_by("id","desc")->get('departments');
        $result = $query->result();
        return $result;
    }





}
?>