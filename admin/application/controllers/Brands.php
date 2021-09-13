<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Brands extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        
        $this->load->model('Brands_model');
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


    /* === VIEW BRANDS === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Brands/brands_view';
        $template['page_title'] = "View Segment";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Brands_model->get_brands();
        $this->load->view('template',$template);
    }

    public function get_all_brands(){
        $data = $_GET;

        $columns = array("id","name","created_at","id");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

     //   $search_box = array('user_type_id','user_id','log','ip_adress');

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

        
        $activity = $this->Brands_model->get_brands($value);
        $all_activity = $this->Brands_model->get_brands();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Brands_model->get_brands($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->name,
                    $r->created_at,
                    "<a class='btn btn-xs btn-primary' href='".base_url()."brands/edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit </a>"
                
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }





    /* === CREATE BRANDS === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Brands/brands_create';
        $template['page_title'] = "Create Brands";
        $template['page_data'] = $this->info;
        $result = array(
            'name' =>'',
        );
        $template['result'] = (object) $result;
        if($_POST) {
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Brands_model->save_brands($data,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Brand Already Exist','class' => 'danger'));
            }
            else {  
                
                $this->session->set_flashdata('message', array('message' => 'Brand Created successfully','class' => 'success'));
            }
            redirect(base_url().'brands');
        }
        $this->load->view('template', $template);
    }


    /* === UPDATE BRANDS === */
    public function edit($id=null) {
        if($id==''){
            redirect('brands');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Brands/brands_create';
        $template['page_title'] = "Edit Brands";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Brands_model->get_single_brand($id);
        if(empty($template['result'])){
            redirect(base_url('brands'));
        }
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Brands_model->update_brands($data, $id,$object_id);
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Brands already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Brands  Updated Successfully','class' => 'success'));
            }
            redirect(base_url().'brands');
        }
        else {
            $this->load->view('template', $template);
        }
    }








} 
