<?php 
class Settlement_model extends CI_Model {
    public $user_id,$user_type,$companies;
    public function _consruct(){
        parent::_construct();
    }

    function Settlement_model(){
        $this->user_id = $this->session->userdata('logged_in')['user_id'];
        $this->user_type = $this->session->userdata('user_type_id');
        if($this->user_type!=1){
        //    $this->companies = get_access_company($this->user_type,$this->user_id);
        }
    }

        function get_settle_report($data){

        $type = "'".$data['company_id']."'";
        //  print_r($data);
        if($type == "'cash,card'") {
           
            $where = "WHERE orders.status = 4 AND orders.vendor_id = ".$data['vendor_id']." ";
        }else{

            $where = "WHERE orders.status = 4 AND orders.vendor_id = ".$data['vendor_id']." AND orders.payment_type =".$type;

        }
      

      //  $where = "WHERE orders.payment_type =".$data['company_id'];
        $period = '';
        if($data['custom_date']!=''){
            list($start_date,$end_date) = explode('_', $data['custom_date']);
            $where .= " AND orders.created_at >= '".$start_date."' AND orders.created_at <= '".$end_date."'";
            $period = $start_date." To ".$end_date;
        }

        if($type == "'cash,card'") {
           
            $rs = $this->db->query("SELECT SUM(orders.price) AS total_price,SUM(orders.commission) AS commission,  SUM(orders.vendor_payback) AS vendor_payback, COUNT(orders.id) AS sales,MIN(orders.created_at) AS start_date,MAX(orders.created_at) AS end_date,'period' FROM orders $where")->row();
        }else{

            $rs = $this->db->query("SELECT SUM(orders.price) AS total_price,SUM(orders.commission) AS commission,  SUM(orders.vendor_payback) AS vendor_payback, COUNT(orders.id) AS sales,orders.payment_type AS type,MIN(orders.created_at) AS start_date,MAX(orders.created_at) AS end_date,'period' FROM orders $where")->row();

        }
      


       // echo $this->db->last_query();
        if($period==''){
            if($rs->start_date!=null && $rs->end_date!=null){
                $period = $rs->start_date." To ".$rs->end_date;
            } else {
                $rs->period = "No record";
            }
        }

        if($rs){
          $rs->period = $period;  
        }

        return $rs;

    }

    
}
