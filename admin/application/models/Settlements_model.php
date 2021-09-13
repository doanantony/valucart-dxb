<?php 
class Settlements_model extends CI_Model {
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


    function get_companies(){
        $session      = $this->session->userdata('logged_in');
        $user_type_id = $session['user_type_id'];
        $user_id      = $session['user_id'];
        if($user_type_id==1){
            $result = $this->db->get('company')->result();
        } else if($user_type_id==2){
            $result = $this->db->where('id',$user_id)->get('company')->result();
        }
        else {
            $result = $this->db->query("SELECT company.id,company.company_name FROM company INNER JOIN company_access ON FIND_IN_SET(company.id,company_access.companies) WHERE user_id=" . $user_id)->result();
        }
        return $result;
    }

    function get_sales_user(){
        if($this->companies){
            $this->db->where_in('sales_users.company_id',$this->companies);
        }
        return $this->db->where('status','1')->select('CONCAT(first_name," ",last_name) AS display_name')->get('sales_users')->result();
    }


    function save_issued($data,$object_id) {

        $issued_date = date('Y-m-d H:i:s');
        $company_id = $data['merchant_id'];
        $session      = $this->session->userdata('logged_in');
        $user_type_id = $session['user_type_id'];
        $user_id      = $session['user_id'];

        $cards = array();    
        if($data['mode']=='individual'){
            $card_length = count($data['card_no']);
            $cards = $data['card_no'];
        } else if($data['mode']=='range'){
            $start_at = $data['start_at'];
            $end_at = $data['end_at'];
            $card_length = $data['end_at'] - $data['start_at'];
            for($i=$start_at;$i<=$end_at;$i++){
                $cards[] = $i;
            }
        } else {
            $tmpName = $_FILES['csv']['tmp_name'];
            $csvAsArray = array_map('str_getcsv', file($tmpName));
            foreach ($csvAsArray as $data) {
                $cards[] = $data[0];
            }
            $card_length = count($cards);
        }

        $role = get_role($user_type_id);

        //Insert into issued cards
        $data = array('company_id'=>$company_id,
                      'issued_cards'=>$card_length,
                      'issued_by'=>$user_type_id,
                      'issued_user_role'=> $role,
                      'issued_date'=>$issued_date,
                      'user_act_id'=>$user_type_id
                  );
        $this->db->insert('issued_cards',$data);
        $insert_id = $this->db->insert_id();
        $company = get_company($company_id);


       
        $user_ip = get_client_ip();
        $session_data = $this->session->userdata('logged_in');
        $date_time = date('Y-m-d H:i:s');
        $activity_data = array(
                                'user_id' => $session_data['id'], // id of the user done the activity
                                'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                'date_time' => $date_time, //time of activity
                                'object_id' => $object_id,
                                'log' => $role.' issued '.$card_length.' cards for'.$company, //action
                                'edited_id' => $insert_id, //particular id of activity done
                                'ip_adress' => $user_ip, //ip of user who done the activity
                                'status' => '1'  //by default
                                );
        $res = insert_user_activity($activity_data);

        foreach ($cards as $rs) {
            $string = number_format($rs, 0, '', '');
            $data = array('issued_cards_id'=>$insert_id,
                          'company_id'=>$company_id,
                          'card_num'=>$string);
            $all_cards[] = $data;
        }

        $res = $this->db->insert_batch("company_cards",$all_cards);
        if($res) {
            return "Success";
        }
        else {
            return "Error";
        }
    }


    function get_single_cards($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('card_type');
        $result = $query->row();
        return $result;
    }   


    function update_cards($data, $id,$object_id) {
        $type_name = $data['type_name'];
        $this->db->where('type_name', $type_name);
        $this->db->where("id !=",$id);
        $this->db->from('card_type');
        $count = $this->db->count_all_results();
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('card_type', $data); 
            $user_ip = get_client_ip();
            $insert_id = $this->db->insert_id();
            $date_time = date('Y-m-d H:i:s');
            $session_data = $this->session->userdata('logged_in');
            $activity_data = array(
                                    'user_id' => $session_data['id'], // id of the user done the activity
                                    'user_type_id' => $session_data['user_type_id'], //id of the usertype
                                    'date_time' => $date_time, //time of activity
                                    'object_id' => $object_id,
                                    'log' => 'Existing Card Updated', //action
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

    function get_card_info(){
        $total_card = $this->db->select_sum('issued_cards')->get('issued_cards')->row();
        $total_query = $this->db->query("SELECT COUNT(card_num) AS count,(CASE WHEN status = 0 THEN 'remain' WHEN status = 1 THEN 'active' ELSE 'suspend'  END) AS type  FROM `company_cards` GROUP BY status")->result();
        $total_data = array('remain'=>0,
                            'active'=>0,
                            'suspend'=>0,
                            'issued'=>$total_card->issued_cards);
        foreach ($total_query as $rs) {
            $total_data[$rs->type] = $rs->count;
        }

        return $total_data;
    }

    function card_result(){
        $where = '';
        if($this->companies){
            $where = "WHERE company.id IN (".$this->companies.")";
        }
        $data = $this->db->query("SELECT company.id,company.company_name,COUNT(DISTINCT case when company_cards.status = 0 then company_cards.id end) as remain, COUNT(DISTINCT case when company_cards.status = 1 then company_cards.id end) as active,COUNT(DISTINCT case when company_cards.status = 2 then company_cards.id end) as suspend,COUNT(DISTINCT company_cards.id) AS issued from company LEFT JOIN company_cards ON company_cards.company_id = company.id $where GROUP BY company.id")->result();
        /*$total_data = array('remain'=>0,
                            'active'=>0,
                            'suspend'=>0,
                            'issued'=>0,
                            "name"=>""
                            );
        $res = array();

        $res_query = $this->db->query("SELECT company.id,company.company_name,COALESCE(SUM(issued_cards.issued_cards),0) AS total_issued FROM `company` LEFT JOIN issued_cards ON company.id = issued_cards.company_id GROUP BY company.id")->result();


        foreach ($res_query as $rs) {             
           $res[$rs->id] = $total_data;           
           $res[$rs->id]['name'] = $rs->company_name;
           $res[$rs->id]['issued'] = $rs->total_issued;
        }

        foreach ($data as $rs) {
           $res[$rs->id][$rs->type] = $rs->count;
        }*/
        return $data;
    }







    //
        function get_transaction($filter=null){
          
        $limit = '';
        $where = '';
        $order_by = '';
        if($filter) {
            if($filter['length']!=-1)
                $limit = " LIMIT ".$filter['start'].",".$filter['length'];
            $order_by = " ORDER BY ".$filter['order']." ".$filter['order_type'];
            //$this->db->order_by($filter['order'], $filter['order_type']);

            //print_r($filter['where']);
            
            if(!empty($filter['where'])) {
                $where = " WHERE ".$filter['where'];             
                //$this->db->where($filter['where']);
            }
        } 

        if($this->companies){
            if($where!=''){
                $where .= " AND orders.payment_type IN (".$this->companies.")";
            } else {
                $where = " WHERE payment_type.id IN (".$this->companies.")";
            }
            
        }

        
    //  $query = 'SELECT company.id,company.company_name,COUNT(transactions.id) AS trans_count, COALESCE(SUM(transactions.fare_applied),0) AS total_fare, COALESCE((SUM(transactions.fare_applied)-SUM(act_charge)),0) AS net_amount,COALESCE(SUM(act_charge),0) AS service_charge FROM transactions RIGHT JOIN company ON company.id = transactions.ac_card_comp_id'.$where.' GROUP BY company.id'.$order_by.$limit;

        $query = 'SELECT orders.id,orders.order_reference,orders.customer_id,orders.price FROM orders'.$where.' GROUP BY orders.id'.$order_by.$limit;


        $result = $this->db->query($query)->result();

      //  echo $this->db->last_query();
        
        return $result;
    }





    //








    function get_transaction_bck($filter=null){
        $limit = '';
        $where = '';
        $order_by = '';
        if($filter) {
            if($filter['length']!=-1)
                $limit = " LIMIT ".$filter['start'].",".$filter['length'];
            $order_by = " ORDER BY ".$filter['order']." ".$filter['order_type'];
            //$this->db->order_by($filter['order'], $filter['order_type']);

         //  print_r($this->companies);
            
            if(!empty($filter['where'])) {
                $where = " WHERE ".$filter['where'];             
                //$this->db->where($filter['where']);
            }
        } 

        if($this->companies){
            if($where!=''){
                $where .= " AND company.id IN (".$this->companies.")";
            } else {
                $where = " WHERE company.id IN (".$this->companies.")";
            }
            
        }

        
      $query = 'SELECT orders.id,orders.customer_id,orders.status,orders.payment_status FROM orders'  .$order_by.$limit ;
        

        $result = $this->db->query($query)->result();
      //  echo $this->db->last_query();
        return $result;
    }

    function get_card_type(){
        return $this->db->get('card_type')->result();
    }


    function get_topup_details($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by($filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

        if($this->companies){
            $this->db->where_in('company.id',$this->companies);
        }

        $this->db->select("card_topup.*,company.company_name");
        $this->db->from("card_topup");
        $this->db->join('company_cards','company_cards.id = card_topup.card_id','LEFT');
        $this->db->join('company','company.id = company_cards.company_id','LEFT');
        
        
        $result = $this->db->get()->result();
        //echo $this->db->last_query();
        
        return $result;
    }



    function chart_data($data){
        if($data['company']!=0){
            $left_join = "LEFT JOIN company_cards ON company_cards.id = card_topup.card_id";
            $new_where = 'AND company_cards.company_id = '.$data['company']; 
            $new_where_or = 'WHERE company_cards.company_id = '.$data['company'];
        } else {
            $new_where = '';
            $left_join = '';
            $new_where_or = '';

            if($this->companies){
                $new_where = 'AND company_cards.company_id IN ('.$this->companies.')';
                $new_where_or = 'WHERE company_cards.company_id IN ('.$this->companies.')';
            }

        }
        if($data['type']=='range'){
            list($start_date,$end_date) = explode('_', $data['options']);
            $rs = $this->db->query("SELECT COUNT(card_topup.id) AS tran_count,COALESCE(SUM(amount),0) AS total FROM `card_topup` $left_join WHERE transact_date >= '".$start_date."' AND transact_date <= '".$end_date."' $new_where")->row();
            $chart_content = "SELECT transact_date,COUNT(card_topup.id) AS tran_count,COALESCE(SUM(amount),0) AS total FROM `card_topup` $left_join WHERE transact_date >= '".$start_date."' AND transact_date <= '".$end_date."' $new_where GROUP BY transact_date";
            $rs->start_date = $start_date;
            $rs->end_date = $end_date;

        } else {
            $rs = $this->db->query("SELECT COUNT(card_topup.id) AS tran_count,COALESCE(SUM(amount),0) AS total,MIN(transact_date) AS start_date,MAX(transact_date) AS end_date FROM `card_topup` $left_join $new_where_or")->row();
            //echo $this->db->last_query();
            if($data['type']=='yearly'){
                $chart_content = "SELECT YEAR(transact_date) AS transact_date,COUNT(card_topup.id) AS tran_count,COALESCE(SUM(amount),0) AS total FROM `card_topup` $left_join $new_where_or GROUP BY YEAR(transact_date)";
            } else if($data['type']=='daily'){
                $year = date('Y');
                $month = date('m');
                $chart_content = "SELECT transact_date,COUNT(card_topup.id) AS tran_count,COALESCE(SUM(amount),0) AS total FROM `card_topup` $left_join WHERE YEAR(transact_date)=$year $new_where GROUP BY transact_date";
            } else {
                $year = date('Y');
                $chart_content = "SELECT CONCAT(YEAR(transact_date),'-',MONTH(transact_date)) AS transact_date,COUNT(card_topup.id) AS tran_count,COALESCE(SUM(amount),0) AS total FROM `card_topup` $left_join WHERE YEAR(transact_date)=$year $new_where GROUP BY MONTH(transact_date)";
            }           
            
        }
       // echo $chart_content;
        $chart_array = $this->db->query($chart_content)->result();

        if($data['mode']=='sum'){
            $chart_rs = array();

            foreach ($chart_array as $res) {
                $chart_rs[] = array('year'=>$res->transact_date,
                                    'value'=>$res->total);
            }


        } else {
            $chart_rs = array();

            foreach ($chart_array as $res) {
                $chart_rs[] = array('year'=>$res->transact_date,
                                    'value'=>$res->tran_count);
            }
        }

        if($data['company']!=0){
            $rs->comapany_name = get_company($data['company']);
        } else {
            $rs->comapany_name = 'All Companies';
        }


        return array('statics'=>$rs,"chart"=>$chart_rs);
    }


    //


        function get_settle_report($data){
          
         

       // print_r($data);

         $type = "'".$data['company_id']."'";
          
         
         $where = "WHERE orders.payment_type =".$type;
        
     //  $where = "WHERE orders.payment_type ="' .strval$data['company_id'] . '""; 


        $period = '';

        if($data['custom_date']!=''){
            
            list($start_date,$end_date) = explode('_', $data['custom_date']);

            $where .= " AND orders.created_at >= '".$start_date." 00:00:00' AND orders.created_at <= '".$end_date." 00:00:00'";

            $period = $start_date." To ".$end_date;
        }

         $rs = $this->db->query("SELECT SUM(orders.price) AS total_price,SUM(orders.sub_total_price) AS sub_total_price, COUNT(orders.id) AS count,orders.payment_type AS type FROM orders $where")->row();

         // $rs = $this->db->query("SELECT company.id,COUNT(transactions.id) AS trans_count,company.company_name,company_settings.service_charge,COALESCE(SUM(transactions.fare),0) AS actual_price,(COALESCE(SUM(transactions.fare),0)-COALESCE(SUM(transactions.fare_applied),0)) AS discount_offer,COALESCE(SUM(transactions.fare_applied),0) AS fare_applied,COALESCE(SUM(transactions.act_charge),0) AS aes_charge, COUNT(driver_trips.id) AS trip_count,MIN(transactions.transact_date) AS start_date,MAX(transactions.transact_date) AS end_date,'period' FROM company LEFT JOIN transactions ON transactions.ac_card_comp_id = company.id LEFT JOIN company_settings ON company.id = company_settings.company_id LEFT JOIN driver_trips ON driver_trips.comp_id = company.id $where GROUP BY company.id")->row();


         // $rs = $this->db->query("SELECT orders.id,COUNT(orders.id) AS count,COALESCE(SUM(orders.price),0) AS total_price,MIN(orders.start_date) AS start_date,MAX(orders.start_date) AS end_date,'period' FROM orders $where GROUP BY orders.id")->row();





        // $rs = $this->db->query("SELECT company.id,COUNT(transactions.id) AS trans_count,company.company_name,company_settings.service_charge,COALESCE(SUM(transactions.fare),0) AS actual_price,(COALESCE(SUM(transactions.fare),0)-COALESCE(SUM(transactions.fare_applied),0)) AS discount_offer,COALESCE(SUM(transactions.fare_applied),0) AS fare_applied,COALESCE(SUM(transactions.act_charge),0) AS aes_charge, COUNT(driver_trips.id) AS trip_count,MIN(transactions.transact_date) AS start_date,MAX(transactions.transact_date) AS end_date,'period' FROM company LEFT JOIN transactions ON transactions.ac_card_comp_id = company.id LEFT JOIN company_settings ON company.id = company_settings.company_id LEFT JOIN driver_trips ON driver_trips.comp_id = company.id $where GROUP BY company.id")->row();

        //echo $this->db->last_query();
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


        //
        return $rs;
    }




    //
    function get_settle_report_bck($data){

        // $where = "WHERE company.id =".$data['company_id'];
        $period = '';
        // if($data['custom_date']!=''){
        //     list($start_date,$end_date) = explode('_', $data['custom_date']);
        //     $where .= " AND transactions.transact_date >= '".$start_date."' AND transactions.transact_date <= '".$end_date."'";
        //     $period = $start_date." To ".$end_date;
        //}
        $rs = $this->db->query("SELECT SUM(orders.price) AS id, SUM(orders.price) AS trans_count, SUM(orders.price) AS payment_type, SUM(orders.price) AS actual_price FROM orders
")->row();

       // echo $this->db->last_query();
        // if($period==''){
        //     if($rs->start_date!=null && $rs->end_date!=null){
        //         $period = $rs->start_date." To ".$rs->end_date;
        //     } else {
        //         $rs->period = "No record";
        //     }
        // }

        // if($rs){
        //   $rs->period = $period;  
        // }
        $rs->period = $period;  

        //
        return $rs;
    }

    
}
