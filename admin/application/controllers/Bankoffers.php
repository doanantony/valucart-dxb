<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bankoffers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Bankoffers_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW BANKOFFERS === */
    public function index() {


        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bankoffers/bankoffers_view';
        $template['page_title'] = "View Bankoffers";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Bankoffers_model->get_alloffers();
        $this->load->view('template',$template);

    }


        public function get_all_offers(){
        $data = $_GET;

        $columns = array("id","code","title","color_code","description","color_code","created_at");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

        $search_box = array('code','title','color_code','description','color_code','created_at');

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

      
        $activity = $this->Bankoffers_model->get_alloffers($value);
        $all_activity = $this->Bankoffers_model->get_alloffers();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Bankoffers_model->get_alloffers($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->code,
                    $r->title,
                    $r->color_code,
                    // $r->description,
                    // "<img src='".get_bankoffer($r->id)."?w=150&h=150' alt='Product Image'>",
                    "<span class='center label label label-".$r->classname."'>" .$r->status. "</span>",
                    $r->created_at,
                    "<a class='btn btn-xs btn-primary' href='".base_url()."bankoffers/edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit</a>"
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    /* === CREATE BANKOFFERS === */
    public function create() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bankoffers/bankoffers_create';
        $template['page_title'] = "Create Bankoffers";
        $template['page_data'] = $this->info;

        $template['result'] = (object) $result;
        if($_POST) {    
            $data = $_POST;
            unset($data['submit']);
            $object_id =  $this->info->object_id;
            $result = $this->Bankoffers_model->save_offers($data,$object_id);
           // print_r( $result);die;
            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Offer Already Exist','class' => 'danger'));
            }
            else if($result == "Error") {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong! Please try again','class' => 'danger'));
            } else {                  
                $this->session->set_flashdata('message', array('message' => 'Bank Offer Created successfully','class' => 'success'));
                redirect(base_url().'bankoffers');
            }
            redirect(base_url().'bankoffers');
           
        }
        $this->load->view('template', $template);
    }

    /* === EDIT BANKOFFERS === */
    public function edit($id=null) {
        if($id==''){
            redirect('bankoffers');
        }

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Bankoffers/bankoffers_edit';
        $template['page_title'] = "Edit Bankoffers";
        $template['page_data'] = $this->info;
        $id = $this->uri->segment(3);
        $template['result'] = $this->Bankoffers_model->get_single_offer($id);
        if(empty($template['result'])){
            redirect(base_url('bankoffers'));
        }
        $template['categories'] = $this->db->get('bundles_categories')->result();
        if($_POST) {
            $data = $_POST;
            $object_id =  $this->info->object_id;
            $result = $this->Bankoffers_model->update_offers($data, $id,$object_id);

            if($result == "Exist") {
                $this->session->set_flashdata('message', array('message' => 'Bank Offer already exist','class' => 'danger'));
            }
            else {
                $this->session->set_flashdata('message', array('message' => 'Bank Offer Updated Successfully','class' => 'success'));
            }

            if($_FILES["image"]["error"] != 0) {

                 redirect(base_url().'bankoffers');
                } 
                else{

                   $this->Bankoffers_model->upload_bankoffer_image($_FILES, $id);
                }
                


             redirect(base_url().'bankoffers');
        }
        else {
            $this->load->view('template', $template);
        }
    }





















} 
