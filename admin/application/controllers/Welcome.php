<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();

		$class = $this->router->fetch_class();

		$method = $this->router->fetch_method();
		
					
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
		//echo "string";die;

		$template['title'] = 'Valucart | '.'$this->info->module_name';
		$template['main'] = '$this->info->module_menu';
		$template['perm'] = $this->perm;
		$template['sub'] = '$this->info->function_menu';
		$template['title'] = '$this->info->function_title';
		$template['small'] = '$this->info->function_small';
		$template['head'] = '$this->info->function_head';


		$template['page'] = "Templates/home_view";
		$template['page_title'] = "Home Page";
		$template['data'] = "Home page";
		$this->load->view('template', $template);
	}
	




	

}