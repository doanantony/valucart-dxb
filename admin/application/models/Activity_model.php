<?php 
class Activity_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }

    function get_activity($filter=null){
    	if($filter) {
    		if($filter['length']!=-1)
				$this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("user_activities.id","desc");
			$this->db->order_by("user_activities.".$filter['order'], $filter['order_type']);
			if(!empty($filter['where'])) {
				$this->db->where($filter['where']);
			}
		}
		$this->db->select("user_activities.*");
		$this->db->where("user_activities.status",1);
		$this->db->from("user_activities");
		$result = $this->db->get()->result();
		return $result;
    }

    function get_usertype(){
    	return $this->db->select('user_type.id,user_type.type_name')->from('user_type')->join('user_activities','user_type.id = user_activities.user_type_id','inner')->group_by('user_type.id')->get()->result();
    }

    function get_users_name(){

    	$result = $this->db->select('user_activities.user_id,user_type.user_type')->from('user_activities')->join('user_type','user_type.id = user_activities.user_type_id','inner')->group_by('user_type.id')->group_by('user_activities.user_id')->get()->result();
    	$customers = array();
    	foreach ($result as $rs) {
    		$table_name = $rs->user_type;
    		$user_name = $this->db->select("CONCAT(table_name.first_name,' ',table_name.last_name) AS display_name")->where('users.id',$rs->user_id)->from('users')->join(''.$table_name.' table_name','users.user_id = table_name.id')->get()->row();
    		if(count($user_name)>0){
    			$first_name = $user_name->display_name;
    		} else {
    			$first_name = "None";
    		}
    		$data = array('id'=>$rs->user_id,
    				 'name'=>$first_name);
    		array_push($customers,$data);
    	}
    	return $customers;

    }


    
}
