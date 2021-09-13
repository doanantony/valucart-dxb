<?php 
class Bundles_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_bundles(){
        $this->db->where('status!=', 2);
        $query =$this->db->get('bundles');
       // $query = $this->db->order_by("name", "asc");
        $result = $query->result();
        return $result;
    }


    function save_bundles($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('bundles');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Bundles '.$data['name']. ''
                      );

            $session_data = $this->session->userdata('logged_in');

            $res = updatelog($log,$session_data);

            $result = $this->db->insert('bundles', $data); 
            
            $insert_id = $this->db->insert_id();

            $this->upload_bundle_image($_FILES, $insert_id);

            
            if($result) {
                return $insert_id;
            }
            else {
                return "Error";
            }
        }
    }

    function popular_prod() {
        $this->db->limit(10);
        $products = $this->db->select('products.id, products.name, SUM(cart_items.quantity) AS votes')->from('products')->join('cart_items', 'products.id = cart_items.item_id', 'LEFT')->where('is_admin_bundlable', '1')->group_by('products.id')->order_by('votes', 'DESC')->get();
        return $products->result();
    }

    function prod_list_bck($data) {
        $this->db->limit(10);
        if(isset($data['cat_id']) && $data['cat_id']!='') {
            $this->db->where('category_id', $data['cat_id']);
        }
        if(isset($data['sub_id']) && $data['sub_id']!='') {
            $this->db->where('subcategory_id', $data['sub_id']);
        }

        if(isset($data['search_val']) && $data['search_val']!=''){
            $this->db->where('name LIKE ', '%'.$data['search_val'].'%');
        }

        $this->db->where('is_admin_bundlable', '1');
        $products = $this->db->select('products.id, products.name')->get('products');
        return $products->result();
    }

    function get_bundle($id) {
        $bundle = $this->db->select('bundles_categories.name AS cat_name,bundles.id,bundles.name,bundles_images.path, bundles.description')->where('bundles.id', $id)->from('bundles')->join('bundles_categories', 'bundles.category_id = bundles_categories.id')->join('bundles_images', 'bundles_images.bundle_id = bundles.id', 'LEFT')->get();
        return $bundle->row();
    }

    function get_list_products($ids){
        $this->db->select('products.id, products.name, products.valucart_price, products_images.path, 1 AS qty');
        $this->db->from('products');
        $this->db->join('products_images', 'products_images.product_id = products.id AND products_images.is_thumb = 1', 'LEFT');
        $this->db->group_by('products.id');
        $this->db->where_in('products.id', $ids);
        $rs = $this->db->get();
        //echo $this->db->last_query();
        return $rs->result();

    }

    function get_bundle_prod($id) {
        $res = $this->db->where('bundles_products.bundle_id', $id)->select('bundles_products.id,products.name')->from('bundles_products')->join('products', 'products.id = bundles_products.product_id')->get();
        return $res->result();
    }


    function get_single_bundles($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('bundles');
        $result = $query->row();
        return $result;
    }   


    function update_bundles($data, $id,$object_id) {
        // echo "<pre>";
        //  print_r($data);die;
       
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('bundles');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {

            $this->db->where('id',$id);
            $result = $this->db->update('bundles', $data); 
           
            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Bundles '.$data['name']. ''
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

    function sub_list($data) {
        return $this->db->where('category_id', $data['cat_id'])->get('subcategories')->result();
    }



    //upload



   function upload_bundle_image($FILES,$id){



       if(isset($_FILES['image']['tmp_name'])){

        $ch =curl_init();

        $cfile = new CURLfile($_FILES['image']['tmp_name'],$_FILES['image']['type'],$_FILES['image']['name']);

         $data = array("bundleimage"=>$cfile);

          $url = "http://v2api.valucart.com/update_bundle_image/" . $id;
           


          // print_r($url);die;
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

           $response = curl_exec($ch);

        //  print_r($response);die;
       }


    
}












/***********New Updates**********************/

function get_bundle_alter_prod($id) {
        $res = $this->db->query("SELECT bundles_products.id, GROUP_CONCAT(bundles_products_alternatives.product_id) AS prod_ids, prod.name, GROUP_CONCAT(products.name) AS alter_name FROM bundles_products LEFT JOIN bundles_products_alternatives ON bundles_products_alternatives.bundles_products_id = bundles_products.id JOIN products AS prod ON prod.id = bundles_products.product_id LEFT JOIN products ON bundles_products_alternatives.product_id = products.id WHERE bundles_products.bundle_id = $id GROUP BY bundles_products.id");
        return $res->result();
}

function prod_list($data) {
        $this->db->limit(10);
        if(isset($data['cat_id']) && $data['cat_id']!='') {
            $this->db->where('category_id', $data['cat_id']);
        }
        if(isset($data['sub_id']) && $data['sub_id']!='') {
            $this->db->where('subcategory_id', $data['sub_id']);
        }

        if(isset($data['search_val']) && $data['search_val']!=''){
            $this->db->where('name LIKE ', '%'.$data['search_val'].'%');
        }

        if(isset($data['product_id']) && count($data['product_id']) > 0){
            $this->db->where_not_in('products.id', $data['product_id']);
        }

        $this->db->where('is_admin_bundlable', '1');
        $products = $this->db->select('products.id, products.name')->get('products');
        return $products->result();
    }


    function get_bundle_all_prod($id) {
        $res = $this->db->where('bundles_products.bundle_id', $id)->select('bundles_products.id,products.name, bundles_products.product_id, bundles_products.quantity,products.valucart_price')->from('bundles_products')->join('products', 'products.id = bundles_products.product_id')->get();
        return $res->result();
    }




    /* === Update Bundles === */
    function update_bundle_status($id,$data){
        $this->db->where('id',$id);
        $result = $this->db->update('bundles',$data);
        return $result;
    }





}