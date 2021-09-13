<?php 
class Metatags_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_allproducts($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("products.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

       // $this->db->select("products.*");
      //  $this->db->where("products.status",1);

        $this->db->select("products.id,products.name,products.sku,products.brand_id,products.department_id,products.category_id,products.subcategory_id,products.maximum_selling_price,products.valucart_price");



        
        $this->db->from("products");
       
        
        $result = $this->db->get()->result();
        
      
        return $result;
    }



    function get_featuredproducts($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("products.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }
        $this->db->select("products.*");
        $this->db->where("products.is_featured",'1');
        $this->db->from("products");
        
        
        $result = $this->db->get()->result();
        
        return $result;
    }



    function get_brands(){
        return $this->db->select('brands.id,brands.name')->from('brands')->get()->result();
    }

    function get_departments(){
        return $this->db->select('departments.id,departments.name')->from('departments')->get()->result();
    }

    function get_category(){
        return $this->db->select('categories.id,categories.name')->from('categories')->get()->result();
    }


    function get_single_product($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('products');
        $result = $query->row();
        return $result;
    }


    function get_product($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('products');
        $result = $query->row();
        return $result;
    }



    function save_metatags($data,$id,$object_id) {
       
        $product_id = $id;
        $this->db->where('product_id', $product_id);
        $this->db->from('products_meta_tags');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['product_id'] = $product_id;
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('products_meta_tags', $data); 

            $product_details = $this->db->where('id', $product_id)->get('products')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Metatgs of '.$product_details->name. ''
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



    function update_metatags($data,$id,$object_id) {
                
            $product_id = $id;

            $this->db->where("id",$product_id);
            $result = $this->db->update('products', $data); 

            $product_details = $this->db->where('id', $id)->get('products')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Metatgs of '.$product_details->name. ''
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
