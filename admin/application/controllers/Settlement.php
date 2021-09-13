<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Settlement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Settlement_model');
        $this->load->model('Vendors_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    public function index($id=null) {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Vendors/vendors_view';
        $template['page_title'] = "View Vendors";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Vendors_model->get_vendors();
        $this->load->view('template',$template);

    }

    public function settle_filter(){
        $data = $_POST;
        $res = $this->Settlement_model->get_settle_report($data);
        print json_encode($res);
    }


} 
