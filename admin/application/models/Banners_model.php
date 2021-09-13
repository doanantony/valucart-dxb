<?php 
class Banners_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_banners(){
        $query = $this->db->order_by("id","desc")->get('banners');
        $result = $query->result();
        return $result;
    }



    function view_popup_banner($id){
        
        $query = $this->db->where('id', $id);
        $query = $this->db->get('banners');
        $result = $query->row();
        return $result;     

     }


     /* === Update Banners === */
    function update_banner_status($id,$data){
        $this->db->where('id',$id);
        $result = $this->db->delete('banners');
        return $result;
    }


    /* === Delete  Banners=== */
    public function leads_delete($id){
        $this->db->where('id',$id);
        $result = $this->db->delete('home_banners');
        if($result){
            return "success"; 
        }
        else{
             return "error";
        }
    }





    function save_banners($data) {
        $name = $data['name'];
        $this->db->where('name', $name);
   //     $this->db->where('status!=', 2);
        $this->db->from('banners');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {

            // echo "<pre>";
            // print_r($data);die;
        //    $this->upload_banners($_FILES, $data);
            $banner_data = array();
            if($data['mode'] == 'department')
            {
                $banner_data['resource_type'] = 'product_department';

                $banner_data['resource_identifier'] = $data['dep_id'];

                $function = 'generatehash/' . $data['dep_id'];
         
                $response = ApiCallGet($function);
               
                $resource_id = $response['data'];

                $banner_data['redirect_url'] = 'https://v2api.valucart.com/products?department='.$resource_id;
            }

            if($data['mode'] == 'brand')
            {
                $banner_data['resource_type'] = 'product_brand';

                $banner_data['resource_identifier'] = $data['brand_id'];

                $function = 'generatehash/' . $data['brand_id'];
         
                $response = ApiCallGet($function);
               
                $resource_id = $response['data'];

                $banner_data['redirect_url'] = 'https://v2api.valucart.com/products?brand='.$resource_id;
            }

            if($data['mode'] == 'category')
            {
                $banner_data['resource_type'] = 'product_category';

                $banner_data['resource_identifier'] = $data['cat_id'];

                $function = 'generatehash/' . $data['cat_id'];
         
                $response = ApiCallGet($function);
               
                $resource_id = $response['data'];

                $banner_data['redirect_url'] = 'https://v2api.valucart.com/products?category='.$resource_id;

            }

            if($data['mode'] == 'subcategory')
            {
                $banner_data['resource_type'] = 'product_sub_category';

                $banner_data['resource_identifier'] = $data['sub_id'];

                $function = 'generatehash/' . $data['sub_id'];
         
                $response = ApiCallGet($function);
               
                $resource_id = $response['data'];

                $banner_data['redirect_url'] = 'https://v2api.valucart.com/products?subcategory='.$resource_id;

            }


            if($data['mode'] == 'bundle')
            {
                $banner_data['resource_type'] = 'bundle';

                $banner_data['resource_identifier'] = $data['bund_id'];

                $function = 'generatehash/' . $data['bund_id'];
         
                $response = ApiCallGet($function);
               
                $resource_id = $response['data'];

                $banner_data['redirect_url'] = 'https://v2api.valucart.com/bundles/'.$resource_id;

            }

            if($data['mode'] == 'bundlecategory')
            {
                $banner_data['resource_type'] = 'bundle_category';

                $banner_data['resource_identifier'] = $data['bund_cat_id'];

                $function = 'generatehash/' . $data['bund_cat_id'];
         
                $response = ApiCallGet($function);
               
                $resource_id = $response['data'];

                $banner_data['redirect_url'] = 'https://v2api.valucart.com/bundles?category='.$resource_id;

            }

            if($data['mode'] == 'product')
            {
                $banner_data['resource_type'] = 'product';

                $banner_data['resource_identifier'] = $data['product_id'];

                $function = 'generatehash/' . $data['product_id'];
         
                $response = ApiCallGet($function);
               
                $resource_id = $response['data'];

                $banner_data['redirect_url'] = 'https://v2api.valucart.com/products/'.$resource_id;


            }



            $banner_data['position'] = $data['position'];
            $banner_data['name'] = $data['name'];
            //$banner_data['redirect_url'] = $data['href'];
            

            $this->upload_banners($_FILES, $banner_data);

            


            $log = array(
                         'id' =>'1',
                         'log' => 'Created Banner '.$banner_data['name']. ''
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


    function get_single_banner($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('home_banners');
        $result = $query->row();
        return $result;
    }   


    function update_banners($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->where('status!=', 2);
        $this->db->from('home_banners');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $this->update_existing_banners($data,$id);
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'Existing Banner Updated', //action
                                    'edited_id' => $id, //particular id of activity done
                                    'ip_adress' => $user_ip, //ip of user who done the activity
                                    'status' => '1'  //by default
                                    );
            $res = insert_user_activity($activity_data);
            if($res) {
                return "Success";
            }
            else {
                return "Error";
            }
        }
    }











        //upload
   function upload_banners($FILES,$banner_data){

       if(isset($_FILES['landscape']['tmp_name'])){

        $ch =curl_init();

        $landscape = new CURLfile($_FILES['landscape']['tmp_name'],$_FILES['landscape']['type'],$_FILES['landscape']['name']);

        $portrait = new CURLfile($_FILES['portrait']['tmp_name'],$_FILES['portrait']['type'],$_FILES['portrait']['name']);

     

        $banner_data['landscape'] = $landscape;
        $banner_data['portrait'] = $portrait;

          echo "<pre>";
         // print_r($banner_data);die;
          
          $url = "http://v2api.valucart.com/banners";


          // print_r($url);die;
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $banner_data);

           $response = curl_exec($ch);
         
    
       }


    
}



   function update_existing_banners($data,$id){

       if(isset($data)){

       

        $ch =curl_init();

        $data = array(

                    'name' => $data['name'],
                    'redirect_url' => $data['href'],
                    );

          
          $url = "http://v2api.valucart.com/banners/". $id;
       

          // print_r($url);die;
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

           $response = curl_exec($ch);

        // print_r($response);die;
       }


    
}







    function prod_list($data) {
        return $this->db->where('category_id', $data['subcat_id'])->get('products')->result();
    }








    
}
