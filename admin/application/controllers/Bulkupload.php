<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bulkupload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        $this->load->model('Products_model');
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
        $template['page'] = 'Bulkupload/bulkupload_create';
        $template['page_title'] = "View Bulkupload";
        $template['page_data'] = $this->info;
        //print_r($_POST);die;
        if($_FILES) {
            if(isset( $_FILES['csv'] )){
                $csv_file = $_FILES['csv']['tmp_name'];
                if(is_file( $csv_file)){
                    $row = 1;
                    if(($handle = fopen($csv_file,"r")) !== FALSE){
                        while (($csv_data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $num = count($csv_data);
                            if($row == 1){
                                $row++;
                                continue;
                            }
                            $name = $csv_data[1];
                            $description = $csv_data[2];
                            $sku = $csv_data[3];
                            $category = $csv_data[4];
                            $subcategory = $csv_data[5];
                            $brand = $csv_data[6];
                            $packaging_quantity = $csv_data[7];
                            $packaging_quantity_unit = $csv_data[8];
                            $msp = $csv_data[10];
                            $rsp = $csv_data[11];
                            $valucart_price = $csv_data[12];
                            $community = 'All';
                            $published = 1;
                            $cost_price = $rsp * 0.95;

                            // echo "<pre>";
                            // print_r($sku);die;
                            $is_product_exists = $this->db->where('sku', $sku)->get('products')->num_rows();
                            //print_r($is_product_exists);die;
                            if ($is_product_exists == 0){

                                $is_category_exists = $this->db->where('name', $category)->get('categories')->num_rows();
                               
                                if ($is_category_exists == 0){
                                     print_r($is_category_exists);die;
                                     $this->db->insert('categories', array('name' => $category, ));
                                }
                            }    

                        }
                    }else{
                        $this->session->set_flashdata('message', array('message' => 'unable to read the format try again','class' => 'warning'));
                    }
                }else{
                    $this->session->set_flashdata('message', array('message' => 'CSV format File not found','class' => 'warning'));
                }
            }else{
                $this->session->set_flashdata('message', array('message' => 'File should be csv','class' => 'warning'));
            }
        }else{
            $this->session->set_flashdata('message', array('message' => ' Csv file is missing','class' => 'warning'));
        }
        $this->load->view('template',$template);

    }



} 
