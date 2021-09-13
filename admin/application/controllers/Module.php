<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Module extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$class = $this->router->fetch_class();
		$method = $this->router->fetch_method(); 
		$this->info = get_function($class,$method);
		$this->load->model('Module_model');
		if (!$this->session->userdata('logged_in')) {
			redirect(base_url());
		}
		$role_id = $this->session->userdata('user_type_id');
		if(!privillage($class,$method,$role_id)){
			redirect('wrong');
		}   
		$this->perm = get_permit($role_id);
	}


public function index() {

	$template['main'] = $this->info->module_menu;
    $template['perm'] = $this->perm;
    $template['sub'] = $this->info->function_menu;
	$template['page'] = 'Modules/module_view';
	$template['page_title'] = "View Module";
	$template['page_data'] = $this->info;
	$template['data'] = $this->Module_model->get_module();
	$this->load->view('template',$template);
}


/* === CREATE MODULE === */
public function create() {
	$template['main'] = $this->info->module_menu;
    $template['perm'] = $this->perm;
    $template['sub'] = $this->info->function_menu;
	$template['page'] = 'Modules/module_create';
	$template['page_title'] = "Create Module";
	$template['menu'] = $this->db->get('object')->result();
	$template['page_data'] = $this->info;
	if($_POST) {
		
		$data = $_POST;
		unset($data['submit']);
		$object_id =  $this->info->object_id;
		$result = $this->Module_model->save_module($data,$object_id);
		if($result == "Exist") {
			$this->session->set_flashdata('message', array('message' => 'Module Already Exists','class' => 'danger'));
		}
		else {	
			
			$this->session->set_flashdata('message', array('message' => 'Module Created successfully','class' => 'success'));
		}
		redirect(base_url().'module');
	}
	$this->load->view('template', $template);
}


/* === CREATE FUNCTION === */
public function create_function() {

	$template['main'] = $this->info->module_menu;
    $template['perm'] = $this->perm;
    $template['sub'] = $this->info->function_menu;
	$template['page'] = 'Modules/function_create';
	$template['page_title'] = "Create Function";
	$template['page_data'] = $this->info;
	$template['module'] = $this->db->get('module')->result();
	if($_POST) {
		$data = $_POST;
		unset($data['submit']);
		$object_id =  $this->info->object_id;
		$result = $this->Module_model->save_function($data,$object_id);
		if($result == "Exist") {
			$this->session->set_flashdata('message', array('message' => 'Error Ocured','class' => 'danger'));
		}
		else {	
			
			$this->session->set_flashdata('message', array('message' => 'Function Created successfully','class' => 'success'));
		}
		redirect(base_url().'module/function_view');
	}
	$this->load->view('template', $template);
}


/* === VIEW FUNCTION === */
public function function_view() {

	$template['main'] = $this->info->module_menu;
    $template['perm'] = $this->perm;
    $template['sub'] = $this->info->function_menu;
	$template['page'] = 'Modules/function_view';
	$template['page_title'] = "View Function";
	$template['page_data'] = $this->info;
	$template['data'] = $this->Module_model->get_function();
	$this->load->view('template',$template);
}


/* === UPDATE MODULE === */
public function edit($id=null) {
	if($id==''){
		redirect('module');
	}

	$template['main'] = $this->info->module_menu;
    $template['perm'] = $this->perm;
    $template['sub'] = $this->info->function_menu;
	$template['page'] = 'Modules/edit_module';
	$template['page_title'] = "Edit Module";
	$template['page_data'] = $this->info;
	$id = $this->uri->segment(3);
	$template['data'] = $this->Module_model->get_single_module($id);
	$template['object'] = $this->db->get('object')->result();
	if(empty($template['data'])){
		redirect(base_url('module'));
	}
	if($_POST) {
		$data = $_POST;
		unset($data['submit']);
		$object_id =  $this->info->object_id;
		$result = $this->Module_model->update_module($data, $id,$object_id);
		if($result == "Exist") {
			$this->session->set_flashdata('message', array('message' => 'Module already exist','class' => 'danger'));
		}
		else {
			$this->session->set_flashdata('message', array('message' => 'Module  Updated Successfully','class' => 'success'));
		}
		redirect(base_url().'module');
	}
	else {
		$this->load->view('template', $template);
	}
}


/* === UPDATE FUNCTION === */
public function edit_function($id=null) {
	if($id==''){
		redirect('module/function_view');
	}

	$template['main'] = $this->info->module_menu;
    $template['perm'] = $this->perm;
    $template['sub'] = $this->info->function_menu;
	$template['page'] = 'Modules/edit_function';
	$template['page_title'] = "Edit Function";
	$template['page_data'] = $this->info;
	$template['module'] = $this->db->get('module')->result();
	$id = $this->uri->segment(3);
	$template['data'] = $this->Module_model->get_single_function($id);
	if(empty($template['data'])){
		redirect(base_url('module/function_view'));
	}
	if($_POST) {
		$data = $_POST;
		unset($data['submit']);
		$object_id =  $this->info->object_id;
		$result = $this->Module_model->update_function($data, $id,$object_id);
		if($result == "Exist") {
			$this->session->set_flashdata('message', array('message' => 'Function already exist','class' => 'danger'));
		}
		else {
			$this->session->set_flashdata('message', array('message' => 'Function  Updated Successfully','class' => 'success'));
		}
		redirect(base_url().'module/function_view');
	}
	else {
		$this->load->view('template', $template);
	}
}
} 
