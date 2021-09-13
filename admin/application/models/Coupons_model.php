<?php 
class Coupons_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_allcoupons($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("coupons.created_at","desc");
            $this->db->order_by("coupons.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

        $this->db->where("starts_at!=",'2020-03-30 20:00:00' );
        $this->db->where("starts_at!=",'2020-03-31 20:00:00' );
       // $this->db->select("orders.*");


          $this->db->select("coupons.coupon AS id,coupons.coupon,coupons.minimum_order_value,coupons.discount,coupons.for_payment_method,(CASE coupons.for_first_order WHEN 1 THEN 'First Order'  ELSE 'All Orders' END) AS applicable_order,(CASE coupons.for_payment_method WHEN 'cash' THEN 'Cash'  WHEN 'card' THEN 'Card' ELSE 'All' END) AS applicable_payents,(CASE coupons.for_first_order WHEN 1 THEN 'info'  ELSE 'danger' END) AS classname,(CASE coupons.for_payment_method WHEN 'card' THEN 'primary'  ELSE 'danger' END) AS classnames,coupons.created_at");
         $this->db->select("coupons.*");

        $this->db->from("coupons");
       
        
        $result = $this->db->get()->result();
     //  echo $this->db->last_query();
      
        return $result;
    }
    


    function save_coupons($data,$object_id) {
        $coupon = $data['coupon'];
        $this->db->where('coupon', $coupon);
        $this->db->from('coupons');
        $count = $this->db->count_all_results();

        if($count > 0) {
            return "Exist";
        }
        else {



            $start_date = date("Y-m-d", strtotime($data['start']));
             
            $newStartDate = $start_date.' 00:00:00';

            $end_date = date("Y-m-d", strtotime($data['end']));
             
            $newEndDate = $end_date.' 00:00:00';

            if ($end_date < $start_date) {
              
               $this->session->set_flashdata('message', array('message' => 'Coupon Expiry Date should be greater than Start Date','class' => 'danger'));
                redirect(base_url().'coupons/'.'create');exit;
            }
       

            if($data['percent']){

                $array = array(

                                'coupon' => $data['coupon'],
                                'minimum_order_value' => $data['minimum_order_value'],
                                'discount' => $data['percent'].'%',
                                'usage_limit' => $data['usage_limit'],
                                'for_first_order' => $data['for_first_order'],
                                'starts_at'  => $newStartDate,
                                'expires_at' => $newEndDate,
                        );
            }else{

                 $array = array(

                                'coupon' => $data['coupon'],
                                'minimum_order_value' => $data['minimum_order_value'],
                                'discount' => $data['aed'].'aed',
                                'usage_limit' => $data['usage_limit'],
                                'for_first_order' => $data['for_first_order'],
                                'starts_at'  => $newStartDate,
                                'expires_at' => $newEndDate,
                        );
            }

            if($data['for_payment_method'] == 'cash'){

                $array['for_payment_method'] = 'cash';

            }else if($data['for_payment_method'] == 'card'){

                $array['for_payment_method'] = 'card';

            }else{

                $array['for_payment_method'] = '';
            }

           
        $this->InsertCoupons($array);


            if($data['for_all_customers'] == '1'){

               $this->AddCouponUsers($data);

            }





            $log = array(
                         'id' =>'1',
                         'log' => 'Created Coupon '.$data['coupon']. ''
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





    //api call

    function InsertCoupons($data){

       if(isset($data)){

        $ch =curl_init();

            
          $url = "http://v2api.valucart.com/coupons";

          // print_r($url);die;
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

           $response = curl_exec($ch);


       }

    
    }


    function AddCouponUsers($data){
       

       if(isset($data)){

        $ch =curl_init();

         
        

          $url = "http://v2api.valucart.com/coupons/". $data['coupon']."/users";

          $postdata = array('customer_identifier' => '*');

       
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

           $response = curl_exec($ch);


       }

    
    }




    /* === Delete  coupons=== */
    public function delete_coupon($id){

        $result = $this->db->where('coupon',$id)->delete('coupons');
        //echo $this->db->last_query();die;

        $this->db->where('coupon',$id)->delete('coupon_users');

        if($result){
            return "success"; 
        }
        else{
             return "error";
        }
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

        $this->db->select("products.*");
      //  $this->db->where("products.status",1);
        $this->db->from("products");
        
        
        $result = $this->db->get()->result();
        
        return $result;
    }



    function get_all_coupon_users($id){

     //  $query =$this->db->where('coupon',$id)->where("user_identifier !=",'*')->get('coupon_users');
    //   echo $this->db->last_query();die;
    //   SELECT * FROM `coupon_users` WHERE `coupon` = 'valuoff' AND `user_identifier` != '*' AND user_identifier REGEXP '^[0-9]+$'
       $query =$this->db->query("SELECT * FROM `coupon_users` WHERE `coupon` = '$id' AND `user_identifier` != '*' AND user_identifier REGEXP '^[0-9]+$' ");

       $result = $query->result();
       return $result;
   }


    function get_all_coupon_items($coupon){

      //$query =$this->db->query("SELECT * FROM `coupon_items` WHERE `coupon` = '$id' AND `user_identifier` != '*' AND user_identifier REGEXP '^[0-9]+$' ");
       $query =$this->db->where('coupon',$coupon)->get('coupon_items');

       $result = $query->result();
       return $result;
   }





    function get_all_coupon_domainusers($id){

        $query1 =$this->db->query("SELECT * FROM `coupon_users` WHERE `coupon` = '$id' AND `user_identifier` != '*' AND user_identifier REGEXP '@' ")->row();
     
        $identifier = $query1->user_identifier;

    
       $query =$this->db->query("SELECT * FROM `customers` WHERE `email` LIKE '%$identifier%' ");
      
       $result = $query->result();
       return $result;
   }




   function is_cust_exists($data,$id) {
    
     $identifier = $data['customer_id'];

     $this->db->where('user_identifier',$identifier);
     $this->db->where('coupon',$id);
     $this->db->from('coupon_users');
       
     $count = $this->db->count_all_results();


     $this->db->where('user_identifier','*');
     $this->db->where('coupon',$id);
     $this->db->from('coupon_users');
       
     $count1 = $this->db->count_all_results();




     if($count > 0) {
         return "Exist";
     }elseif($count1 > 0){
             return "Exist";
        }
     else {
     
         return "Success";
    }


}




    /* === Delete  coupons=== */
    public function delete_coupon_users($id){


        $result = $this->db->where('id',$id)->delete('coupon_users');

        if($result){
            return "success"; 
        }
        else{
             return "error";
        }
    }

    public function delete_coupon_items($id){


        $result = $this->db->where('id',$id)->delete('coupon_items');

        if($result){
            return "success"; 
        }
        else{
             return "error";
        }
    }
    


//adding items to coupon




    function add_coupon_items($data,$coupon) {
       
        $name = $data['cat_id'];
        $this->db->where('item_type', 'category');
        $this->db->where('coupon', $coupon);
        $this->db->from('coupon_items');
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
            $item_data = array();
            if($data['mode'] == 'department')
            {
                $item_data['resource_type'] = 'product_department';

                $item_data['resource_identifier'] = $data['dep_id'];
            }

            if($data['mode'] == 'brand')
            {
                $item_data['resource_type'] = 'product_brand';

                $item_data['resource_identifier'] = $data['brand_id'];
            }

            if($data['mode'] == 'category')
            {
                $item_data['resource_type'] = 'product_category';

                $item_data['resource_identifier'] = $data['cat_id'];
            }

            if($data['mode'] == 'subcategory')
            {
                $item_data['resource_type'] = 'product_sub_category';

                $item_data['resource_identifier'] = $data['sub_id'];
            }


            if($data['mode'] == 'bundle')
            {
                $item_data['resource_type'] = 'bundle';

                $item_data['resource_identifier'] = $data['bund_id'];
            }

            if($data['mode'] == 'bundlecategory')
            {
                $item_data['resource_type'] = 'bundle_category';

                $item_data['resource_identifier'] = $data['bund_cat_id'];
            }

            if($data['mode'] == 'product')
            {
                $item_data['resource_type'] = 'product';

                $item_data['resource_identifier'] = $data['product_id'];
            }

            //echo "<pre>";
            // print_r($item_data);die;
            $postdata = array('item_type' => $item_data['resource_type'], 'item_identifier' => $item_data['resource_identifier']);

            $function = 'coupons/' . $coupon . '/items';
         
            ApiCallPost($function, $postdata);

            $user_ip = get_client_ip();
            $insert_id = 1;
            $session_data = $this->session->userdata('logged_in');
            $date_time = date('Y-m-d H:i:s');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'New Coupon Item added', //action
                                    'edited_id' => $insert_id, //particular id of activity done
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






















    
}
