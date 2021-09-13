<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Activity extends CI_Controller
{
    
    
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Activity_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        $role_id = $this->session->userdata('user_type_id');
        if(!privillage($class,$method,$role_id)){
            redirect('wrong');
        }   
        $this->perm = get_permit($role_id); 
    }


    /* === VIEW ACTIVITY === */
    public function index() {

        $template['main'] = $this->info->module_menu;
        $template['perm'] = $this->perm;
        $template['sub'] = $this->info->function_menu;
        $template['page'] = 'Activity/activity_view';
        $template['page_title'] = "View Activity";
        $template['page_data'] = $this->info;
        $template['usertype'] = $this->Activity_model->get_usertype();
        $template['users'] = $this->Activity_model->get_users_name();
        $template['data'] = $this->Activity_model->get_activity();
        $this->load->view('template',$template);
    }

    public function get_all_activity(){
        $data = $_GET;
        $columns = array("id","user_type_id","user_id","log","ip_adress","date_time");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];
        $search_box = array('user_type_id','user_id','log','ip_adress');
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
        $activity = $this->Activity_model->get_activity($value);
        $all_activity = $this->Activity_model->get_activity();
        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Activity_model->get_activity($value);
            $filtered = count($page_activity);
        }
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    get_activity_cust_type($r->user_type_id),
                    get_activity_users_name($r->user_id),
                    $r->log,
                    $r->ip_adress,
                    $r->date_time,
                ));
            }
        }
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }


    



} 
