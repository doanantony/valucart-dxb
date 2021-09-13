<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Settlements extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method(); 
        $this->info = get_function($class,$method);
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('Settlements_model');
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
        $template['page'] = 'Settlement/settlement';
        $template['page_title'] = "View Cards";
        $template['page_data'] = $this->info;
      //  $template['companies'] = $this->Settlement_model->get_companies();
     //   $template['card_info'] = $this->Settlement_model->get_card_info();
      //  $template['result'] = $this->Settlement_model->card_result();
        $this->load->view('template',$template);  

    }

    

    public function all_cards(){
        $data = $_GET;

        $columns = array("company_cards.card_num","company.company_name","card_type.type_name","active_cards.balance","concat_ws(' ',app_customer.first_name,app_customer.last_name)","app_customer.phone");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

        $search_box = array('company_cards.card_num','company.id','card_type.id','concat_ws(" ",app_customer.first_name,app_customer.last_name)','app_customer.phone','active_cards.status');

        //$search_box = array('card_num','company_name','type_name','status','name','phone','status');

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

        
        $activity = $this->Settlement_model->get_card_details($value);        
        $all_activity = $this->Settlement_model->get_card_details();
        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Settlement_model->get_card_details($value);
            $filtered = count($page_activity);
        }
        

        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->cards_id,
                    $r->card_num,
                    $r->company_name,
                    $r->type_name,
                    $r->balance,
                    $r->name,
                    $r->phone,
                    /*$r->email,*/
                    $r->status,
                    //anchor('test/view/' . $r->id, 'View'),
                    $r->status!='Suspend'?"<a class='btn btn-sm btn-danger' href='".base_url()."card_management/delete_active/".$r->cards_id."'> <i class='fa fa-fw fa-trash'></i> Suspend </a>":""
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }






    //
        public function all_settlement(){


        $data = $_GET;

        $columns = array("orders.id");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

        $search_box = array('transact_date',"orders.payment_type",'sales_users_id','company_cards.company_id');

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
        for($i=1;$i<$limit;$i++){
            if($data['columns'][$i]['search']['value']!='' && $data['columns'][$i]['search']['value']!=-1){
                $search_val = $data['columns'][$i]['search']['value'];
                $custom_where[] = $search_box[$i]." = '".$search_val."' ";
            }
        }

        if($data['columns'][0]['search']['value']!=''){
            $date_range = $data['columns'][0]['search']['value'];
            list($start_date,$end_date) = explode('_', $date_range);
            $custom_where[] = "created_at >= '".$start_date." 00:00:00' AND created_at <= '".$end_date." 00:00:00'";
          //  print_r($custom_where);
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

        
        $activity = $this->Settlement_model->get_transaction($value);
        $all_activity = $this->Settlement_model->get_transaction();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Settlement_model->get_transaction($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();

        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->order_reference,
                    get_customer_name($r->customer_id),
                    get_customer_email($r->customer_id),
                    $r->price,
                    "<span style='float:right;'>AED ".number_format((float)$r->price, 2, '.', '')."&nbsp;</span>",
                    // "<span style='float:right;'>JD ".number_format((float)$r->service_charge, 2, '.', '')."&nbsp;</span>",
                    // "<span style='float:right;'>JD ".number_format((float)$r->net_amount, 2, '.', '')."&nbsp;</span>",
                    //anchor('test/view/' . $r->id, 'View'),
                    /*"<a class='btn btn-sm btn-primary' href='".base_url()."activity/activity_edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit </a> 
                     <a class='btn btn-sm btn-danger' href='".base_url()."activity/activity_delete/".$r->id."'> <i class='fa fa-fw fa-trash'></i> Delete </a>"*/
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }








    //


















    public function all_settlement_bck(){
        $data = $_GET;

        $columns = array("orders.status");
        $value['search'] = $data['search']['value'];
        $value['start'] = $data['start'];
        $value['length'] = $data['length'];

   //     $search_box = array('transact_date',"company.id",'sales_users_id','company_cards.company_id');

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
        for($i=1;$i<$limit;$i++){
            if($data['columns'][$i]['search']['value']!='' && $data['columns'][$i]['search']['value']!=-1){
                $search_val = $data['columns'][$i]['search']['value'];
                $custom_where[] = $search_box[$i]." = '".$search_val."' ";
            }
        }

        if($data['columns'][0]['search']['value']!=''){
            $date_range = $data['columns'][0]['search']['value'];
            list($start_date,$end_date) = explode('_', $date_range);
            $custom_where[] = "transact_date >= '".$start_date."' AND transact_date <= '".$end_date."'";
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

        
        $activity = $this->Settlement_model->get_transaction($value);
        $all_activity = $this->Settlement_model->get_transaction();

        $filtered = count($all_activity);
        if($value['where']!='') {
            $value['length'] = '-1';
            $page_activity = $this->Settlement_model->get_transaction($value);
            $filtered = count($page_activity);
        }
        
        
        $data = array();
        if($activity) {
            foreach ($activity  as $r) {
                array_push($data, array(
                    $r->id,
                    $r->id,
                    $r->status,
                    $r->status,
                    // "<span style='float:right;'>JD ".number_format((float)$r->total_fare, 2, '.', '')."&nbsp;</span>",
                    // "<span style='float:right;'>JD ".number_format((float)$r->service_charge, 2, '.', '')."&nbsp;</span>",
                    // "<span style='float:right;'>JD ".number_format((float)$r->net_amount, 2, '.', '')."&nbsp;</span>",
                    //anchor('test/view/' . $r->id, 'View'),
                    /*"<a class='btn btn-sm btn-primary' href='".base_url()."activity/activity_edit/".$r->id."'> <i class='fa fa-fw fa-edit'></i> Edit </a> 
                     <a class='btn btn-sm btn-danger' href='".base_url()."activity/activity_delete/".$r->id."'> <i class='fa fa-fw fa-trash'></i> Delete </a>"*/
                ));
            }
        }
 
        echo json_encode(array('recordsFiltered' => $filtered, 'recordsTotal' => count($all_activity), 'data' => $data));
    }

    public function chart_data(){
        $data = $_POST;
        $data = $this->Settlement_model->chart_data($data);
        echo json_encode($data);
    }

    public function export_data(){
        $custom_where = array();
        $request =  $this->input->get();
        $value['where'] = '';
        $value['length'] = -1;
        $value['order'] = 'orders.id';
        $value['order_type'] = 'ASC';
        
        
        if(count($request)>0){
            if($request['period']){
                $date_range = $request['period'];
                list($start_date,$end_date) = explode('_', $date_range);
                $custom_where[] = "created_at >= '".$start_date." 00:00:00' AND created_at <= '".$end_date." 00:00:00'";
            }

            if($request['company']){
                $company_id = $request['company'];
                $custom_where[] = "orders.payment_type = '".$company_id."'";
            }
        }

        if(count($custom_where)>0){
            $where = implode(" AND ", $custom_where);
            $where = "(".$where.")";
            $value['where'] = $where;
        }        
        $result = $this->Settlement_model->get_transaction($value);
        return $result;

    }

    public function pdf_generate(){
        $data['result'] = $this->export_data();
       // print_r($data);
        $this->load->view('Settlement/settlement_pdf', $data);
    }

    public function csv_generate(){ 
       // file name 

        $result = $this->export_data();

        $filename = 'settlement_'.date('Ymd').'.csv'; 
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Content-Type: application/csv; ");


        // file creation 
        $file = fopen('php://output', 'w');

        $header = array("Order Reference","Customer","Email","Total Price"); 
        fputcsv($file, $header);

        foreach ($result as $key=>$line){ 
                unset($line->id);
                $line->price = "AED ".number_format((float)$line->price, 2, '.', '');
                $line->customer_name = get_customer_name($line->customer_id);
                $line->customer_email = get_customer_email($line->customer_id);
            //$line = (array)$line;
            $data = array('order_reference'=>$line->order_reference,
                          'customer'=>$line->customer_name,
                          'email'=>$line->customer_email,
                          'total_price'=>$line->price,
                        );
            
            fputcsv($file,$data); 
        }

        fclose($file); 
        exit; 
      }

    public function xlxs_generate() {
        $this->load->library('Excel');
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Settlement');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Company');
        $this->excel->getActiveSheet()->setCellValue('A4', 'Transaction');
        $this->excel->getActiveSheet()->setCellValue('B4', 'Amount');
        $this->excel->getActiveSheet()->setCellValue('C4', 'Service Charge');
       // $this->excel->getActiveSheet()->setCellValue('D4', 'Net Amount');
        //merge cell A1 until C1
        $this->excel->getActiveSheet()->mergeCells('A1:C1');
        //set aligment to center for that merged cell (A1 to C1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
        for($col = ord('A'); $col <= ord('D'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
             //change the font size
            $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
             
            $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //retrive contries table data
        $result = $this->export_data();
        $exceldata="";

        

        foreach ($result as $line){
            unset($line->id);
            $line->price = "AED ".number_format((float)$line->price, 2, '.', '');
        //$line = (array)$line;
          
            $data = array('company_name'=>$line->company_name,
                      'transaction'=>$line->trans_count,
                      'amount'=>$line->amount,
                      'service_charge'=>$line->service_charge,
                 //     'net_amount'=>$line->net_amount
                    );

            // $data = array('Order_Reference'=>$line->order_reference,
            //           'customer'=>$line->customer_id,
            //           'email'=>$line->customer_id,
            //           'Total_Price'=>$line->price
            //         );
            // echo "<pre>";
            // print_r($data);

            $exceldata[] = $data;
        }
        //Fill data 
        $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A4');
         
        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         
        $filename='settlement.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
                 
    }

    public function settle_filter(){
        $data = $_POST;
      
        $res = $this->Settlement_model->get_settle_report($data);
        print json_encode($res);
    }


} 
