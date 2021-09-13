<?php 
class Bankoffers_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }



    function get_alloffers($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("offers.id","desc");
            $this->db->order_by("offers.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

        $this->db->select("offers.id,offers.code,offers.title,offers.description,offers.color_code,(CASE offers.status WHEN 1 THEN 'Published' WHEN 0 THEN 'Unpublished'  ELSE 'Deleted' END) AS status,(CASE offers.status WHEN 1 THEN 'info' WHEN 2 THEN 'warning' WHEN 3 THEN 'success' ELSE 'danger' END) AS classname,offers.created_at");

        $this->db->from("offers");
        $result = $this->db->get()->result();
        return $result;

    }


    function save_offers($data,$object_id) {
   
        $code = $data['code'];
        $this->db->where('code', $code);
        $this->db->from('offers');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            unset($data['_wysihtml5_mode']);
           
            $result = $this->db->insert('offers', $data); 
           // echo $this->db->last_query();die;
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();

            $this->upload_bankoffer_image($_FILES, $insert_id);


            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Bank Offer '.$data['code']. ''
                      );

            $session_data = $this->session->userdata('logged_in');

            $res = updatelog($log,$session_data);

          //  print_r($res);die;
            if($result) {
                return $insert_id;
            }
            else {
                return "Error";
            }
        }
    }


    function get_single_offer($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('offers');
        $result = $query->row();
        return $result;
    }   


    function update_offers($data, $id,$object_id) {
        // echo "<pre>";
        //  print_r($data);die;
       
        $code = $data['code'];
        $this->db->where('code', $name);
        $this->db->where("id !=",$id);
        $this->db->from('offers');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
             unset($data['_wysihtml5_mode']);
            $this->db->where('id',$id);
            $result = $this->db->update('offers', $data); 

            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Bank Offer '.$data['code']. ''
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




    function upload_bankoffer_image($FILES,$id){

       if(isset($_FILES['image']['tmp_name'])){

        $ch =curl_init();

        $cfile = new CURLfile($_FILES['image']['tmp_name'],$_FILES['image']['type'],$_FILES['image']['name']);

         $data = array("offerimage"=>$cfile);

         $url = "http://v2api.valucart.com/update_bankoffer_image/" . $id;

           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

           $response = curl_exec($ch);

       }
    
}







  



    }







