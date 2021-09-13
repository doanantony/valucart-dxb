<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wrong extends CI_Controller {
	
	




	public function indexx(){
		$template['title'] = 'AES | Wrong Place';		
		$template['title'] = "OOps! That page can't be found.";
		$template['small'] = "The page you where looking for could not be found!" ;
		
		
		$template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'wrong_view';
        $template['page_title'] = "View Customers";
        $template['page_data'] = $this->info;
      //  $template['data'] = $this->Customers_model->get_customers();
        $this->load->view('template',$template);
		
	}


	public function index(){
		$template['title'] = 'AES | No Permission';
		$template['main'] = '';
				$menu['sub'] = '';
		$template['title'] = "OOps! No Permission.";
		$template['small'] = "You Dont have Permission To access this page!" ;
		$template['head'] = '';
		$template['page'] = 'wrong_view';
		 $this->load->view('template',$template);
	}





	public function report(){
		$this->load->view('report_view');
	}
}