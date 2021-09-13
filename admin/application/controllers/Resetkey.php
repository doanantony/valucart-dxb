<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Resetkey extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Resetkey_model');
    }


// resetting portal user credentials
    public function key_reset(){
        $reset_key = $this->uri->segment(3);
        $query = $this->db->where('reset_key',$reset_key)->get('portal_users')->row();
        if($query){
            $array = array(
                'user_id' => $query->id,
                'user_type_id' => $query->user_type_id);
            $row = $this->db->where($array)->get('users')->row();
            $user_id = $row->id;
            if($user_id){
                if(isset($_POST)) {
                    
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('f_password', 'FPassword', 'trim|required');
                    $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');              
                    if($this->form_validation->run() == TRUE) {
                        $this->session->sess_destroy();
                        redirect(base_url().'login');
                    }
                }
                $data['id'] = $query->id;
                $this->load->view('reset-form',$data);
            }else{
                $this->reset_failed();
            }
        }else{
            $this->reset_failed();
        }
    }


    public function reset_failed(){
        echo "Resetting Your key Failed";
    }


    function check_database($password) {
        $id = $this->input->post('id');
        $f_password = $this->input->post('f_password');
        $result = $this->Resetkey_model->check_portal_users($f_password,$password);
        if($f_password == $password) {
            $this->session->sess_destroy();
            $sql = $this->db->where('id',$id)->get('portal_users')->row();
            $array = array('user_id' => $id,
                'user_type_id' => $sql->user_type_id
            );
            $rs = $this->db->where($array)->update('users', array('passwd' => md5($password)));
            redirect(base_url().'login');  
        }
        else {
            $this->form_validation->set_message('check_database', 'Password Mismatch');
            return false;
        }
    }




} 
