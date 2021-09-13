<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Management extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Management_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
                                                                                                                                                                                                                                                                                                                                                                                
       // $active_userid = $this->session->userdata('user_id');
        //$role_id = 1; // change it to id from session -usertype - merchant,superadmin,technician
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW MANAGEMENT === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Management/management_view';
        $template['page_title'] = "View Management Users";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Management_model->get_management();
        $this->load->view('template',$template);
    }


    /* === CREATE MARKETING === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_path;
        $template['page'] = 'Management/management_create';
        $template['page_title'] = "Create Management User";
        $template['page_data'] = $this->info;
        $result = array(
            'first_name' =>'',
            'last_name' =>'',
            'email' =>'',
            'phone_no' =>'',
            'status' =>1,
        );
        $user_data = array(
                'id' => '',
                'username' =>'',
                'passwd' => ''
            );
        $template['user_data'] = (object) $user_data;
        $template['result'] = (object) $result;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Management_model->save_management($data,$object_id);

            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Member Already Exist','class' => 'danger'));
            }
            else {  
                //frontendloggedin
                $this->session->set_flashdata('message', array('message' => 'Member Created successfully','class' => 'success'));
            }
            redirect(base_url().'management');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE MARKETING === */
    public function edit($id=null) {
        if($id==''){
            redirect('management');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Management/management_create';
        $template['page_title'] = "Edit Management Users";
        $template['page_data'] = $this->info;
        $query = $this->db->where('id',$id)->get('managementteam')->row();
        $where = array('user_id' => $id,
                      'user_type_id' => $query->user_type_id);
        $tech_data = $this->db->where($where)->get('users')->row();

        $user_data = array(
                'username' => $tech_data->username,
                'password' => '',
                'id' => $tech_data->id
            );
        $template['user_data'] = (object) $user_data;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Management_model->get_single_management($id);
        if(empty($template['result'])){
            redirect(base_url('management'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Management_model->update_management($data,$id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Member already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Member  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'management');
        }
        else {
            $this->load->view('template', $template);
        }
    }








} 
