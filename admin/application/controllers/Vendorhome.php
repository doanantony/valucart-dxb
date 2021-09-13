<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendorhome extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$class = $this->router->fetch_class();
		$method = $this->router->fetch_method();
		$this->load->model('Vendors_model');
		$this->info = get_function($class,$method);
		if (!$this->session->userdata('logged_in')) {
			redirect(base_url());
		}
		$role_id = $this->session->userdata('user_type_id');
		if(!privillage($class,$method,$role_id)){
			redirect('wrong');
		}	
		$this->perm = get_permit($role_id);	
	}
	public function index()
	{
		$template['title'] = 'Valucart | '.$this->info->module_name;
		$template['main'] = $this->info->module_menu;
		$template['perm'] = $this->perm;
		$template['sub'] = $this->info->function_menu;
		$template['title'] = $this->info->function_title;
		$template['small'] = $this->info->function_small;
		$template['head'] = $this->info->function_head;
		$template['result'] = $this->Vendors_model->get_products();
		$template['latestorders'] = $this->Vendors_model->get_orders();
		$template['page'] = "vendor-home";
		$template['page_title'] = "Home Page";
		$template['data'] = "Home page";
		$this->load->view('template', $template);
	}
	public function statics(){
		$users = $this->Home_model->get_user_list();
		$cards = $this->Home_model->get_card_info();
		$chart = $this->Home_model->get_chart_info();
		$terminal = $this->Home_model->get_terminal_info();
		$transaction = $this->Home_model->get_transaction_info();
		print json_encode(array('users'=>$users,'cards'=>$cards,'chart'=>$chart,'terminal'=>$terminal,'transaction'=>$transaction));
	}
}