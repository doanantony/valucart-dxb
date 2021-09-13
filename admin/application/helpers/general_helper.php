<?php



function ApiCallPost($function,$postdata){


     $ch =curl_init();
          
    //$url = "http://api7.com/". $function;
     $url = "http://valucart.com:5000/". $function;

     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

     $response = curl_exec($ch);

}


function ApiCallGet($function)
{		
	$ch =curl_init();

	//$url = "http://api7.com/". $function;

	$url = "http://valucart.com:5000/". $function;

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($ch);
 
    curl_close($ch);

    $json_data = json_decode($output, true);
    
    return $json_data;

}

function get_key(){
	$CI = & get_instance();
	$rs = $CI->db->select('key')->where('id','1')->get('system_settings');
	return $rs->row()->key;
}



  function updatelog($log,$session_data){
           date_default_timezone_set("Asia/Dubai");

            $user_ip = get_client_ip(); 
            $date_time = date('Y-m-d H:i:s');
           
            $activity_data = array(
                                    'user_id' => $session_data['id'],
                                    'user_type_id' => $session_data['user_type_id'],
                                    'date_time' => $date_time,
                                    'object_id' => $object_id,
                                    'log' => $log['log'],
                                    'edited_id' => $log['id'],
                                    'ip_adress' => $user_ip,
                                    'status' => '1'
                                    );
            $res = insert_user_activity($activity_data);

 }



function get_module($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('module')->row();
		//echo $CI->db->last_query();
	if($rs){
		return $rs->module_name;
	}else{
		return null;
	}
}



function insert_user_activity($activity_data){
	$CI = & get_instance();
	$rs = $CI->db->insert('user_activities', $activity_data); 
	//echo $CI->db->last_query();die;
	return "Success";

}


function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }



function get_user_permit($role){
		$ci=& get_instance();
		$ci->load->database(); 

		$result = $ci->db->query('SELECT admin_permissions.module_id,admin_permissions.function_id,module.module_control FROM admin_permissions INNER JOIN module ON admin_permissions.module_id = module.id  WHERE admin_permissions.group_id ='.$role.' ORDER BY module.module_priority ASC');
		//print_r($result);die;
		//echo $ci->db->last_query();die;
		$sub =array();
		$menu = array();
		if($result->num_rows()>0){
			$row = $result->result();
			foreach($row as $rs){
				$function_ids = explode(',', $rs->function_id);
				$module = $ci->db->where('id',$rs->module_id)->get('module')->row();
				$sub =array();
				foreach ($function_ids as $key) {
					$data = $ci->db->where('id',$key)->get('function')->row();
					
					array_push($sub, $data);

				}
				$module->sub = $sub;
				//print_r($module->sub);die;
				array_push($menu, $module);
			}
			return $menu;
			
		} else {
			return null;
		}
	}



function sub_nav($sub,$assigned) {
	//print_r($assigned);die;
		if($sub==$assigned){
			return 'active';
		}
	}


function side_nav($tag,$assigned) {
		if($tag==$assigned){
			return 'active';
		}
	}



	function get_function($class,$function){
		$ci=& get_instance();
		$ci->load->database(); 

		$result = $ci->db->query("SELECT function.function_name,function.function_path,function.id,function.module_id,function.function_menu,function.function_title,function.function_head,function.function_small,module.object_id,module.module_menu,module.module_name,function.message_id FROM function INNER JOIN module ON function.module_id = module.id WHERE module.module_control = '$class' AND function.function_path = '$function'");
			//echo $ci->db->last_query();
		if($result->num_rows()>0){
			return $result->row();
		} else {
			return null;
		}
	
	}



	function get_permit($role){
		$ci=& get_instance();
		$ci->load->database(); 

		$result = $ci->db->query('SELECT admin_permissions.module_id,admin_permissions.function_id,module.module_control FROM admin_permissions INNER JOIN module ON admin_permissions.module_id = module.id WHERE admin_permissions.group_id ='.$role.' ORDER BY module.module_priority ASC');
		//echo $ci->db->last_query();die;
		$sub =array();
		$menu = array();
		if($result->num_rows()>0){
			$row = $result->result();
			foreach($row as $rs){
				$function_ids = explode(',', $rs->function_id);
				$module = $ci->db->where('id',$rs->module_id)->get('module')->row();
				$sub =array();
				foreach ($function_ids as $key) {
					$data = $ci->db->where('id',$key)->get('function')->row();
					array_push($sub, $data);
				}
				$module->sub = $sub;
				array_push($menu, $module);
			}
			return $menu;
			
		} else {
			return null;
		}
	}



	function privillage($class,$method,$role_id){


		$ci=& get_instance();
		$array = array();

		$rs = $ci->db->query("SELECT function.id,function.module_id FROM function INNER JOIN module ON function.module_id = module.id WHERE function.function_path = '$method' AND module.module_control = '$class'");
		if($rs->num_rows()>0){
			$rs = $rs->row();
			$row = $ci->db->query("SELECT admin_permissions.function_id FROM admin_permissions WHERE admin_permissions.group_id = $role_id AND admin_permissions.module_id =".$rs->module_id);
			

			if($row->num_rows()>0){


				$result = $row->row();
				$array = explode(',', $result->function_id);		
				if (in_array($rs->id, $array)) {
				//	echo "string";die;
					return true;
				} else {

					return false;
				}
			} else {

				return false;
			}
		} else {
			return true;
		}
	}


function get_activity_cust_type($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('user_type')->row();
		//echo $CI->db->last_query();die;
	//if(count($rs)>0){
	if($rs){
		return $rs->type_name;
	}else{
		return null;
	}
}

function get_activity_users_name($id){
	$CI = & get_instance();
	//$id = 1;
	if($id>0){
		$user = $CI->db->select('user_type.user_type')->where('users.id',$id)->from('user_type')->join('users','user_type.id = users.user_type_id')->get()->row();
		//echo $CI->db->last_query();
		if(count($user)>0){
			$table_name = $user->user_type;
			$query = $CI->db->select("CONCAT(table_name.first_name,' ',table_name.last_name) AS display_name")->where('users.id',$id)->from('users')->join(''.$table_name.' table_name','users.user_id = table_name.id')->get();
			if($query->num_rows()>0){
				$rs = $query->row();
				return $rs->display_name;
			}else{
				return null;
			}
		} else {
			return null;
		}
		
	}
	
}


function total_vendor_products($id){

	$CI = & get_instance();
	$rs = $CI->db->where('vendor_id',$id)->select('COUNT(*) AS products')->get('products_vendors');
	return $rs->row()->products;

}


function get_vendor_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('product_id',$id)->get('products_vendors')->row();
	$rs = $CI->db->where('id',$rs->vendor_id)->get('vendors')->row();
	return $rs->name;
}



function get_brand_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('brands')->row();
	return $rs->name;
}

function get_package_unit($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('matric_units')->row();
	return $rs->name;
}



function get_department_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('departments')->row();
	return $rs->name;
}

function set_log($class,$method,$postdata,$auth){
//date_default_timezone_set('Asia/Kolkata');
	$CI = & get_instance();
	$url = $class.'/'.$method;
	$data = array('url'=>$url,
	'parameter'=>$postdata,
	'auth'=>$auth);
	//'time'=>date('Y-m-d h:i:s'));
	$CI->db->insert('service_log',$data);
	return $CI->db->insert_id();
}


function get_category_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('categories')->row();
	return $rs->name;
}

function get_subcategory_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('subcategories')->row();
	return $rs->name;
}


function get_state_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('states')->row();
	return $rs->name;
}


function total_customers(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS user')->get('customers');
	return $rs->row()->user;
}


function total_coupons(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS coupons')->get('coupons');
	return $rs->row()->coupons;
}




function total_communities(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS communities')->get('communities');
	return $rs->row()->communities;
}


function total_bundle_categories(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS bundles_categories')->get('bundles_categories');
	return $rs->row()->bundles_categories;
}

function total_brands(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS brands')->get('brands');
	return $rs->row()->brands;
}

function total_departments(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS departments')->get('departments');
	return $rs->row()->departments;
}

function total_categories(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS categories')->get('categories');
	return $rs->row()->categories;
}

function total_subcategories(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS subcategories')->get('subcategories');
	return $rs->row()->subcategories;
}

function total_products(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS products')->get('products_vendors');
	return $rs->row()->products;
}

function total_featured_products(){

	$CI = & get_instance();
	$rs = $CI->db->where('is_featured','1')->select('COUNT(*) AS products')->get('products');
	
	return $rs->row()->products;
}

function total_offered_products(){

	$CI = & get_instance();
	$rs = $CI->db->where('is_offer','1')->select('COUNT(*) AS products')->get('products');
	return $rs->row()->products;
}

function total_cust_bundle_products(){

	$CI = & get_instance();
	$rs = $CI->db->where('is_customer_bundlable','1')->select('COUNT(*) AS products')->get('products');
	return $rs->row()->products;
}

function total_admin_bundle_products(){

	$CI = & get_instance();
	$rs = $CI->db->where('is_admin_bundlable','1')->select('COUNT(*) AS products')->get('products');
	return $rs->row()->products;
}

function total_bundles(){

	$CI = & get_instance();
	$rs = $CI->db->select('COUNT(*) AS bundles')->get('bundles');
	return $rs->row()->bundles;
}

function total_emcoop_products(){

	$CI = & get_instance();
	$rs = $CI->db->where('vendor_id',13)->select('COUNT(*) AS emcoop')->get('products_vendors');
	return $rs->row()->emcoop;
}

function total_vcgt_products(){

	$CI = & get_instance();
	$rs = $CI->db->where('vendor_id',3)->select('COUNT(*) AS vcgt')->get('products_vendors');
	return $rs->row()->vcgt;
}


function total_meatone_products(){

	$CI = & get_instance();
	$rs = $CI->db->where('vendor_id',2)->select('COUNT(*) AS meatone')->get('products_vendors');
	return $rs->row()->meatone;
}


function get_bundlecat_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('bundles_categories')->row();
	return $rs->name;
}

function get_product_image($id){
	$CI = & get_instance();
	$rs = $CI->db->where('product_id',$id)->get('products_images')->row();
	return $rs->path;
}



function get_bundle_image($id)
{			
	$url = "http://v2api.valucart.com/show_single_bundle/$id";
	

    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false); 
 
    $output=curl_exec($ch);
 
    curl_close($ch);


    $json_data = json_decode($output, true);
    
    return $json_data["data"]["thumbnail"];


}




function get_customer_name($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('customers')->row();
	return $rs->name;
}

function get_customer_email($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('customers')->row();
	return $rs->email;
}

function get_customer_phone($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('customers')->row();
	return $rs->phone_number;
}






function get_coupon_itemname($item_id,$item_type){
	$CI = & get_instance();

	$type = $item_type;

	switch ($type) {

                  case "product":

                      $rs = $CI->db->where('id',$item_id)->get('products')->row();
					  
					  return $rs->name;

                  case "product_department":

                      $rs = $CI->db->where('id',$item_id)->get('departments')->row();
					  
					  return $rs->name;

                  case "product_category":

                       $rs = $CI->db->where('id',$item_id)->get('categories')->row();
					  
					  return $rs->name;

				   case "product_sub_category":

                       $rs = $CI->db->where('id',$item_id)->get('	subcategories')->row();
					  
					  return $rs->name;

				    case "product_brand":

                       $rs = $CI->db->where('id',$item_id)->get('brands')->row();
					  
					  return $rs->name;

					case "bundle":

                       $rs = $CI->db->where('id',$item_id)->get('bundles')->row();
					  
					  return $rs->name;

					case "bundle_category":

                       $rs = $CI->db->where('id',$item_id)->get('bundles_categories')->row();
					  
					  return $rs->name;


                  default:
                      
                      $result = array(
                                 'status'  => 0,
                                 'message' => 'Invalid Type'
                                 
                      );

                      return $result;

              }


}


function get_time_slot($id){
	$CI = & get_instance();
	$rs = $CI->db->where('id',$id)->get('delivery_time_slots')->row();
	return $rs->from.' - '.$rs->to;
}

function tcpdf() {
    //require_once('tcpdf/config/lang/eng.php');
    require_once('tcpdf/tcpdf.php');
}


function get_banner_landimage($id)
{		

	$url = "http://v2api.valucart.com/banners/detail/$id";
	 
	//$url = "http://testing.v2.api.valucart.com/banners/kw3G0YgnpjbA";
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($ch);
 
    curl_close($ch);


    $json_data = json_decode($output, true);
    
    return $json_data["landscape"];

}

function get_banner_portimage($id)
{		

	$url = "http://v2api.valucart.com/banners/detail/$id";
	//$url = "http://testing.v2.api.valucart.com/banners/kw3G0YgnpjbA";
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($ch);
 
    curl_close($ch);


    $json_data = json_decode($output, true);
    
    return $json_data["portrait"];

}


function get_customer_cartitems($id){
	$CI = & get_instance();
	$rs = $CI->db->where('customer_id',$id)->get('carts')->row();

	$rs = $CI->db->where('cart_id',$rs->id)->select('COUNT(*) AS cartitems')->get('cart_items');
	return $rs->row()->cartitems;

}


function get_customer_totorders($id){

	$CI = & get_instance();
	$rs = $CI->db->where('customer_id',$id)->select('COUNT(*) AS totalorders')->get('orders');
	return $rs->row()->totalorders;
}


	function GetMasterInfo($table,$id)
		{

			$CI = & get_instance();

			$query = $CI
                     ->db
                     ->where('id',$id)
                     ->get($table);

            $master_result = $query->row();

            return $master_result->first_name;

		}


function get_itemname_cart($id,$type){

	$CI = & get_instance();

	if($type == 'product' ){

		$rs = $CI->db->where('id',$id)->get('products')->row();

	}else if($type == 'bundle' ){

		$rs = $CI->db->where('id',$id)->get('bundles')->row();

	}
	//echo $CI->db->last_query();
	return $rs->name;

}


function get_admin_name($id,$user_type_id){
	$CI = & get_instance();
	if($user_type_id == '1'){
		$rs = $CI->db->where('id',$id)->get('super_admin')->row();
	}elseif ($user_type_id == '6') {
		$rs = $CI->db->where('id',$id)->get('techteam')->row();
	}elseif ($user_type_id == '4') {
		$rs = $CI->db->where('id',$id)->get('logisticsteam')->row();
	}elseif ($user_type_id == '2') {
		$rs = $CI->db->where('id',$id)->get('managementteam')->row();
	}elseif ($user_type_id == '3') {
		$rs = $CI->db->where('id',$id)->get('marketingteam')->row();
	}else{
		return false;
	}
	return $rs->first_name .'&nbsp;' . $rs->last_name;
}









?>