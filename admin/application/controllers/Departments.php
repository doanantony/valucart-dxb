<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Departments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        
        $this->load->model('Departments_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW DEPARTMENTS === */
    public function index() {
        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Departments/departments_view';
        $template['page_title'] = "View Departments";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Departments_model->get_alldepartments();
        $this->load->view('template',$template);
    }


    public function get_all_departments(){
        $data = $_GET;
        $columns = array("id","name","is_popular","deleted_at");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];
        $limit = count($data['columns']);
        $value['where'] = '';
        $where_data = array();
        if(!empty($value['search'])) {
            $where = array();
            foreach($columns as $c) {
                $where_data[] = $c." like '%".$value['search']."%' ";
            }
            $where = implode(" OR ", $where_data);
            $where = "(".$where.")";
            $value['where'] = $where;
        }
        $custom_where = array();
        for($i=0;$i<$limit;$i++){
            if($data['columns'][$i]['search']['value']!=''){
                $search_val = $data['columns'][$i]['search']['value'];
                $custom_where[] = $search_box[$i]." like '%".$search_val."%' ";
            }
        }
        if(count($custom_where)>0){
            $where = implode(" AND ", $custom_where);
            $where = "(".$where.")";
            if($value['where']!=''){
                $value['where'] = $value['where']." AND ".$where;
            } else {
                $value['where'] = $where;
            }            
        }
        $order = $data['order'][0]['column'];
        $value['order'] = $columns[$order];
        $value['order_type'] = $data['order'][0]['dir'];
        $activity = $this->Departments_model->get_alldepartments($value);
        $all_activity = $this->Departments_model->get_alldepartments();
        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Departments_model->get_alldepartments($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->name,
                    "<span class='center label label label-".$r->classname."'>" .$r->popular. "</span>",
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    $r->created_at,
                    "<a class='btn btn-xs btn-success' href='".base_url()."customers/view_profile/".$r->id."'> <i class='fa fa-fw fa-edit'></i> View Profile </a>"

                ));
            }
        }
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    /* === CREATE DEPARTMENTS === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Departments/departments_create';
        $template['page_title'] = "Create Departments";
        $template['page_data'] = $this->info;

        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Departments_model->save_departments($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Department Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Department Created successfully','class' => 'success'));
            }
            redirect(base_url().'departments');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE DEPARTMENTS === */
    public function edit($id=null) {
        if($id==''){
            redirect('departments');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Departments/departments_edit';
        $template['page_title'] = "Edit Departments";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Departments_model->get_single_departments($id);
        if(empty($template['result'])){
            redirect(base_url('departments'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Departments_model->update_departments($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Department already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Department  Updated Successfully','class' => 'success'));
            }

            // if($_FILES["image"]["error"] != 0) {

            //       redirect(base_url().'departments/edit/'.$id);

            // } 
            // else{
            //         echo "<pre>";
            //         print_r($_FILES);die;
            //        $this->Departments_model->upload_department_image($_FILES, $id);
            // }

            $this->Departments_model->upload_department_image($_FILES, $id);

            redirect(base_url().'departments');
        }
        else {
            $this->load->view('template', $template);
        }
    }





        public function publish(){

            $deleted_at = date("Y-m-d H:i:s");


            $data1 = array(
                  "deleted_at" => NULL
                 );

            $id = $this->uri->segment(3);
            
            $s=$this->Departments_model->update_department_status($id, $data1);

            $dep_details = $this->db->where('id', $id)->get('departments')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Department '.$dep_details->name. ' to Published Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);


            $this->session->set_flashdata('message', array('message' => 'Department Published  Successfully ','class' => 'success'));
            redirect(base_url().'departments');
        }

        public function unpublish(){
            
            $deleted_at = date("Y-m-d H:i:s");


            $data1 = array(
                  "deleted_at" => $deleted_at
                 );

            $id = $this->uri->segment(3);

            $s=$this->Departments_model->update_department_status($id, $data1);

            $dep_details = $this->db->where('id', $id)->get('departments')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Department '.$dep_details->name. ' to UnPublished Status'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => ' Departments Unpublished Successfully ','class' => 'warning'));
            redirect(base_url().'departments');
        }















} 
