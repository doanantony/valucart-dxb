<?php 
class Dashboard_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
        //$this->session->set_userdata('user_id',26);
    }

    function get_activity(){    	
		$user_id = $this->session->userdata('logged_in')['user_id'];
		$card = $this->db->where('appcust_id',$user_id)->get('active_cards')->result();

		$card_array = array();

		foreach ($card as $rs) {
			$card_array[] = $rs->cards_id;
		}


		$card_id = implode(',', $card_array);

		//$card_id = 1;

		$card_top = $this->db->where_in('card_id',$card_id)->get('card_topup')->result();
		$trans = $this->db->where_in('ac_cards_id',$card_id)->get('transactions')->result();

		$result = array();
		if(count($card_top)){
			foreach ($card_top as $rs) {
				$res = array(
						'trans_id'=>$rs->trans_no,
						'transact_date'=>$rs->transact_date,
						'mode'=>1,
						'activity'=> 'Topup',
						'fare'=>$rs->amount,
						'discount'=>0
					);
			$result[] = $res;	
			}
		}


		if(count($trans)){
			foreach ($trans as $rs) {
				$res = array(
						'trans_id'=>$rs->txn_num,
						'transact_date'=>$rs->transact_date,
						'mode'=>0,
						'activity'=> get_station($rs->start_stop_id)." - ".get_station($rs->end_stop_id),
						'fare'=>$rs->fare,
						'discount'=>$rs->discount
					);
			$result[] = $res;	
			}
		}

		$result_date = $this->sortByTime($result);		

		return $result;


    }

    public function sortByTime($result){
      for($i=0;$i<count($result);$i++){
      	for($j=$i;$j<count($result);$j++){
      		if(strtotime($result[$i]['transact_date']) > strtotime($result[$j]['transact_date'])){
      			$time = $result[$i]['transact_date'];
      			$result[$i]['transact_date'] = $result[$j]['transact_date'];
      			$result[$j]['transact_date'] = $time;
      		}
      	}
      }
      return $result;
   	}

   	public function get_trip_info(){

		$user_id = $this->session->userdata('logged_in')['user_id'];
		$card = $this->db->where('appcust_id',$user_id)->get('active_cards')->result();

		$card_array = array();

		foreach ($card as $rs) {
			$card_array[] = $rs->cards_id;
		}


		$card_id = implode(',', $card_array);

   		return $rs = $this->db->where_in('transactions.ac_cards_id',$card_id)->select('transactions.txn_num,transactions.transact_date,transactions.fare_applied,transactions.status,transactions.start_stop_id,transactions.end_stop_id,terminals.bus_name,active_cards.card_num')->from('transactions')->join('terminals','terminals.id = transactions.terminals_id')->join('active_cards','active_cards.cards_id = transactions.ac_cards_id')->group_by('transactions.id')->get()->result();   	
   		//echo $this->db->last_query();	
   	}

   	public function card_info(){
   		$user_id = $this->session->userdata('logged_in')['user_id'];
   		return $card = $this->db->SELECT('CONCAT(first_name," ",last_name) AS name,active_cards.card_type_id,active_cards.card_num,active_cards.activation_date,active_cards.status,active_cards.balance')->where('appcust_id',$user_id)->from('active_cards')->join('app_customer','app_customer.id = active_cards.appcust_id')->get()->result();
   	}

   	public function setting_info(){
   		$user_id = $this->session->userdata('logged_in')['user_id'];
   		$this->db->SELECT('app_customer.*,active_cards.card_type_id,active_cards.card_num,active_cards.activation_date,active_cards.status AS card_status,active_cards.balance')->where('appcust_id',$user_id)->where('active_cards.status',1)->from('app_customer')->join('active_cards','app_customer.id = active_cards.appcust_id','left')->get()->row();
   		//echo $this->db->last_query();
   		//die;

   	}

      
}