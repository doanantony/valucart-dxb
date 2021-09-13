<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class VendorSettlements extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        if($method == 'settlement'){
            $method = 'create';
        }
        $this->info = get_function($class,$method);
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


    public function index() {
        $session_data = $this->session->userdata('logged_in');
        $vendor_id =  $session_data['user_id'];
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['vendor_id'] = $vendor_id;
        $template['vendor_profile'] = $this->Vendors_model->view_popup_vendor($vendor_id);
        $template['data'] = $this->Vendors_model->get_vendors_settings($vendor_id);
        $template['page'] = 'Settlement/settlement';
        $template['page_title'] = "View Cards";
        $template['page_data'] = $this->info;
        $this->load->view('template',$template); 
    }


} 
