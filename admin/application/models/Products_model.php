<?php 
class Products_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }

    function update_product_status($id,$data){
        if($data['published'] == 0){
            $this->db->where('item_id', $id);
            $this->db->where('item_type', 'product');
            $this->db->delete('cart_items');
             $this->db->where('id',$id);
            $result = $this->db->update('products',$data);
        }else{
            $this->db->where('id',$id);
            $result = $this->db->update('products',$data);
        }
        return $result;
    }


    function get_allproducts($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("products.id","desc");
            $this->db->order_by("products.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

        $session_data = $this->session->userdata('logged_in');
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
            $this->db->where('department_id',$user_id);
        }
        
        $this->db->select("products.id,products.name,products.sku,products.brand_id,products.department_id,products.category_id,products.subcategory_id,products.maximum_selling_price,products.valucart_price,products.published,(CASE products.published WHEN '1' THEN 'Unpublish'  ELSE 'Publish' END) AS publish_status,(CASE products.published WHEN '1' THEN 'Published'  ELSE 'Unpublished' END) AS status,(CASE products.published WHEN '1' THEN 'success'  ELSE 'danger' END) AS classname");
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

        $session_data = $this->session->userdata('logged_in');
        if($session_data['user_type_id'] == 5){
            $user_id =  $session_data['user_id'];
            $this->db->where('department_id',$user_id);
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


    function sub_list($data) {
        return $this->db->where('category_id', $data['cat_id'])->get('subcategories')->result();
    }


    function get_single_products($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('products');
        $result = $query->row();
        return $result;
    }   


    function get_single_product_vendor($id) {
        $query = $this->db->where('product_id', $id);
        $query = $this->db->get('products_vendors');
        $result = $query->row();
        return $result;
    }   


    function get_single_product_community($id) {
        $query = $this->db->where('product_id', $id);
        $query = $this->db->get('products_communities');
        $result = $query->row();
        return $result;
    } 


    function update_products($data, $id,$object_id) {
        $product_data = array(
                                    'name' => $data['name'],
                                    'sku' => $data['sku'],
                                    'brand_id' => $data['brand_id'],
                                    'category_id' => $data['category_id'],
                                    'subcategory_id' => $data['subcategory_id'],
                                    'description' => $data['description'],
                                    'packaging_quantity' => $data['packaging_quantity'],
                                    'packaging_quantity_unit_id' => $data['packaging_quantity_unit_id'],
                                    'minimum_inventory' => $data['minimum_inventory'],
                                    'maximum_selling_price' => $data['maximum_selling_price'],
                                    'valucart_price' => $data['valucart_price'],
                                    'is_featured' => $data['is_featured'],

                            );

            $this->db->where('id',$id);
            $result = $this->db->update('products', $product_data); 
            $community_data = array('community_id' => $data['community_id']);
            $this->db->where('product_id',$id);
            $result = $this->db->update('products_communities', $community_data); 
             $log = array(
                         'id' =>$id,
                         'log' => 'Updated Product '.$data['name']. ''
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



    function save_products($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('products');
        $count = $this->db->count_all_results();
        $this->db->where('sku', $data['sku']);
        $this->db->from('products');
        $count1 = $this->db->count_all_results();
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }elseif($count1 > 0){
             return "Exist";
        }
        else {
             $product_data = array(

                                    'name' => $data['name'],
                                    'sku' => $data['sku'],
                                    'department_id' => $data['department_id'],
                                    'brand_id' => $data['brand_id'],
                                    'category_id' => $data['category_id'],
                                    'subcategory_id' => $data['subcategory_id'],
                                    'description' => $data['description'],
                                    'packaging_quantity' => $data['packaging_quantity'],
                                    'packaging_quantity_unit_id' => $data['packaging_quantity_unit_id'],
                                    'minimum_inventory' => $data['minimum_inventory'],
                                    'maximum_selling_price' => $data['maximum_selling_price'],
                                    'valucart_price' => $data['valucart_price'],
                                //    'is_admin_bundlable' => $data['is_admin_bundlable'],
                                //    'admin_bundle_discount' => $data['admin_bundle_discount'],
                                //    'is_customer_bundlable' => $data['is_customer_bundlable'],
                                //    'customer_bundle_discount' => $data['customer_bundle_discount'],
                                //    'is_bulk' => $data['is_bulk'],
                                //    'bulk_quantity' => $data['bulk_quantity'],
                                    'is_featured' => $data['is_featured'],
                                //    'is_offer' => $data['is_offer'],
                                    'created_at' => $created_at,
                                    'updated_at' => $updated_at,

                            );


            $result = $this->db->insert('products', $product_data); 
            $insert_id = $this->db->insert_id();
            // $vendor_data = array(

            //                     'product_id' => $insert_id,
            //                     'vendor_id' => $data['vendor_id'],
            //                     'price' => $data['price'],
            //                     'inventory' => $data['minimum_inventory'],
            //                 );
            // $this->db->insert('products_vendors', $vendor_data); 
            $community_data = array(

                                'product_id' => $insert_id,
                                'community_id' => $data['community_id']
                            );
            $this->db->insert('products_communities', $community_data); 
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Product '.$data['name']. ''
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);
            return $insert_id;
          
        }
    }


    function get_allproductsimages($id){

       $query =$this->db->where('product_id',$id)->get('products_images');
       $result = $query->result();
       return $result;
    }


    function is_thump_exists($data,$id) {
         if($data['is_thumb'] == 1){
         $this->db->where('is_thumb',1);
         $this->db->where('product_id',$id);
         $this->db->from('products_images');
         $count = $this->db->count_all_results();
         if($count > 0) {
             return "Exist";
         }
         else {
             return "Success";
        }
        }else{
            return "Success";
        }

    }
        

      public function product_image_delete($id){ 

        $this->db->where('id', $id);
        $this->db->delete('products_images');
        return "success"; 

     }


 



    
}
