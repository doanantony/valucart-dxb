<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Permission extends CI_Controller
{
	public function __construct()
{
	parent::__construct();
	$class = $this->router->fetch_class();
	$method = $this->router->fetch_method(); 
	$this->info = get_function($class,$method);
	$this->load->model('Permission_model');
	if (!$this->session->userdata('logged_in')) {
		redirect(base_url());
	}
	$role_id = $this->session->userdata('user_type_id');
	//$i = privillage($class,$method,$role_id);
	//print_r($i);die;
		if(!privillage($class,$method,$role_id)){
			redirect('wrong');
		}   
		$this->perm = get_permit($role_id);
}


	public function index() {

		$template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
		$template['page'] = 'Permission/permission_view';
		$template['page_title'] = "Assign Permission";
		$template['page_data'] = $this->info;
		$template['user_type_id'] = $this->session->userdata('user_type_id');
		$template['data'] = $this->Permission_model->get_roles();
		$this->load->view('template',$template);
	}


	
	




	public function permission_assign($id=null){
		if($id==null){
			redirect('permission');
		}


		$result = array();

		$data['perm'] =  $this->db->select('function_id')->where('group_id',$id)->get('admin_permissions')->result();
		
			foreach ($data['perm'] as $rs) {

				$permission = explode(',', $rs->function_id);
				$result = array_merge($result, $permission);
			}
			$template['permission'] = $result;

		$module = $this->db->order_by('module_name','ASC')->get('module')->result();
		$function = array();
		foreach ($module as $rs) {
			$label = $rs->module_control;
			$function[$label] = $this->db->where('module_id',$rs->id)->order_by('function_name','ASC')->get('function')->result();
		}
		$template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
		$template['module'] = $module;
		$template['function'] = $function;
		$count_module = count($module);
		$row = ceil($count_module/3);
		$template['row'] = $row;
		//$template['perm'] = $this->perm;
		$template['group_id'] = $id;
		$template['page_data'] = $this->info;
		$template['page'] = 'Permission/perm_groups';
		$template['page_title'] = "Assign Permission";
		$this->load->view('template', $template);
	}

/*

	public function perm_assign(){
	
		$string='';
		$all_data = array();
		foreach ($_POST as $key => $value) {
			if($key!='group_id'){
				
			$module = $this->db->where('module_control',$key)->get('module')->row();
			$value_id = $module->id;
			print_r($_POST['group_id']);
			if($string!=''){
				$string = $string.",".$value_id;
			} else {
				$string = $value_id;
			}

				$this->db->where('module_id',$module->id);
				$this->db->where('group_id',$_POST['group_id']);
				$query = $this->db->get('admin_permissions');

				$value = implode(',',$_POST[$key]);
				$data = array('group_id'=>$_POST['group_id'],
							  'module_id'=>$module->id,
							  'function_id'=>$value);

				echo $this->db->last_query()."<br/>";
				
				if($query->num_rows()==0){
					$this->db->insert('admin_permissions',$data);					
				} else {
					echo "string";
					$this->db->where('module_id',$module->id);
					$this->db->where('group_id',$_POST['group_id']);
					$this->db->update('admin_permissions',$data);					
					echo $this->db->last_query();
				}

				

			}

		}

}
*/



public function perm_assign(){
	
		$string='';
		$all_data = array();
		foreach ($_POST as $key => $value) {
			if($key!='group_id'){
				
				$module = $this->db->where('module_control',$key)->get('module')->row();
				$value_id = $module->id;

				if($string!=''){
					$string = $string.",".$value_id;
				} else {
					$string = $value_id;
				}

				$value = implode(',',$_POST[$key]);
				$data = array('group_id'=>$_POST['group_id'],
							  'module_id'=>$module->id,
							  'function_id'=>$value);

				array_push($all_data, $data);

			}

		}

		print_r($all_data);

		$res = $this->db->where('group_id',$_POST['group_id'])->delete('admin_permissions');

		if($res && !empty($all_data)){
			$this->db->insert_batch('admin_permissions',$all_data);
			//$this->db->insert('admin_permissions',$data);	
		}				
	}


	



} 
