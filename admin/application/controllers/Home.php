<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
		$this->load->model('Home_model');
		
					
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

		$template['title'] = 'Valucart | '.$this->info->module_name;
		$template['main'] = $this->info->module_menu;
		$template['perm'] = $this->perm;
		$template['sub'] = $this->info->function_menu;
		$template['title'] = $this->info->function_title;
		$template['small'] = $this->info->function_small;
		$template['head'] = $this->info->function_head;
		$template['result'] = $this->Home_model->get_vendors();
		$template['latestorders'] = $this->Home_model->get_orders();

		$template['page'] = "table-demo";
		$template['page_title'] = "Home Page";
		$template['data'] = "Home page";

		
		$this->load->view('template', $template);
	}

	public function statics(){
		$terminal = $this->Home_model->get_terminal_info();
		
		print json_encode(array('terminal'=>$terminal));
	}



	public function profile() {
    //    $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        //$template['sub'] = $this->info->function_menu;
        $template['page'] = 'Profile/profile_edit';
        $template['page_title'] = "Edit Profile";
        $template['page_data'] = $this->info;
        $session_data = $this->session->userdata('logged_in');
      //  print_r($session_data);die;
        if($_POST) {
            $data = $_POST;
            $config = $this->set_upload_options();
            $this->load->library('upload');
            $this->upload->initialize($config);
            if ( ! $this->upload->do_upload('image')){
                $this->session->set_flashdata('message', array('message' => 'Error Ocured While Uploading Files','class' => 'danger'));
            }else{
                $upload_data = $this->upload->data();
                $data['profile_picture'] = $config['upload_path']."/".$upload_data['file_name'];
                $object_id =  $this->info->object_id;
                $result = $this->Portalusers_model->save_portalusers($data,$object_id);
                if($result == "Exist") {
                    $this->session->set_flashdata('message', array('message' => 'Portal User Already Exist','class' => 'danger'));
                }
                else {  
                    $this->session->set_flashdata('message', array('message' => 'Portal User Created successfully','class' => 'success'));
                }
                redirect(base_url().'portalusers');
            }
        }
        $this->load->view('template', $template);
    }

	




	

}