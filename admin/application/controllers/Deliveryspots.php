<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Deliveryspots extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Deliveryspots_model');
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
        $template['page'] = 'Deliveryspots/spots_view';
        $template['page_title'] = "View Deliveryspots";
        $template['page_data'] = $this->info;
        $template['data'] = $this->Deliveryspots_model->get_spots();
        if($_POST) {
            $data = $_POST;
           // echo "<pre>";print_r($data);
            $result = $this->Deliveryspots_model->save_spots($data);
            $this->session->set_flashdata('message', array('message' => 'Location added successfully','class' => 'success'));
            redirect(base_url().'Deliveryspots');
        }
        $this->load->view('template',$template);

    }

    public function spots(){
        $terminal = $this->Deliveryspots_model->get_terminal_info();
        
        print json_encode(array('terminal'=>$terminal));
    }

        public function publish(){
            $data1 = array(
                  "published" => '1'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Deliveryspots_model->update_location_status($id, $data1);

            $vendor_details = $this->db->where('id', $id)->get('vendor_location')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Vendor Location '.$vendor_details->name. ' to  closed for delivery'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);


            $this->session->set_flashdata('message', array('message' => 'Deliveryspot Opened for delivery  Successfully ','class' => 'success'));
            redirect(base_url().'Deliveryspots');
        }

        public function unpublish(){
            $data1 = array(
                  "published" => '0'
                 );
            $id = $this->uri->segment(3);
            $s=$this->Deliveryspots_model->update_location_status($id, $data1);

            $vendor_details = $this->db->where('id', $id)->get('vendor_location')->row();

            $log = array(
                         'id' =>$id,
                         'log' => 'Changed Category '.$vendor_details->name. ' to closed for delivery'
                      );

            $session_data = $this->session->userdata('logged_in');

            updatelog($log,$session_data);



            $this->session->set_flashdata('message', array('message' => ' Deliveryspot closed for delivery Successfully ','class' => 'warning'));
            redirect(base_url().'Deliveryspots');
        }


    public function location_createpopup() {  

      // $id=$_POST['patientdetailsval'];
        $template['id'] = 1;
   //     $template['data'] = $this->Vendors_model->view_popup_vendor($id);
        $this->load->view('Deliveryspots/location_createpopup',$template);

    }


























} 
