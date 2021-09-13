<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Settings extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Settings_model');
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
        $template['page'] = 'Settings/system_settings';
        $template['page_title'] = "View Settings";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Settings_model->settings_viewing();
        $template['result'] = $this->Settings_model->settings_viewing(); 
        if($_POST){
            if(isset($_POST) && !empty($_POST)){
                $data = $_POST;
                unset($data['submit']); 

                $result = $this->Settings_model->update_settings($data);

                        if($result) {
                             $this->session->set_flashdata('message',array('message' => 'Settings Updated Succesfully','class' => 'success'));
                             redirect('Settings');
                        }
                        else {
                             $this->session->set_flashdata('message', array('message' => 'Error','class' => 'error'));  
                        }
            }
        }
        $this->load->view('template',$template);
    }




    







} 












