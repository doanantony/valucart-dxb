<?php 
class Departments_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_alldepartments($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("departments.id","desc");
            $this->db->order_by("departments.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

        $this->db->select("departments.id,departments.name,departments.is_popular,departments.created_at,(CASE departments.is_popular WHEN '1' THEN 'Popular'  ELSE 'Not Popular' END) AS popular,(CASE departments.deleted_at WHEN 'NULL' THEN 'Published'  ELSE 'Unpublished' END) AS status,(CASE departments.is_popular WHEN '1' THEN 'success'  ELSE 'danger' END) AS classname");

        $this->db->from("departments");
        $result = $this->db->get()->result();
        return $result;
    }


    function save_departments($data,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->from('departments');
        $count = $this->db->count_all_results();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $result = $this->db->insert('departments', $data); 
            $insert_id = $this->db->insert_id();
            
            $this->upload_department_image($_FILES, $insert_id);

            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created Department '.$data['name']. ''
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);


            if($res) {
                return "Success";
            }
            else {
                return "Error";
            }
        }
    }


    function get_single_departments($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('departments');
        $result = $query->row();
        return $result;
    }   


    function update_departments($data, $id,$object_id) {
        $name = $data['name'];
        $this->db->where('name', $name);
        $this->db->where("id !=",$id);
        $this->db->from('departments');
        $count = $this->db->count_all_results();
        $data['updated_at'] = date("Y-m-d H:i:s");
        if($count > 0) {
            return "Exist";
        }
        else {
            $this->db->where('id',$id);
            $result = $this->db->update('departments', $data); 

                    
            $log = array(
                         'id' =>$id,
                         'log' => 'Updated Department '.$data['name']. ''
                      );

            $session_data = $this->session->userdata('logged_in');
            $res = updatelog($log,$session_data);
            
            if($res) {
                return "Success";
            }
            else {
                return "Error";
            }
        }
    }



    function update_department_status($id,$data){
        $this->db->where('id',$id);
        $result = $this->db->update('departments',$data);
        return $result;
    }


    function upload_department_image($FILES,$id){
         


         if(isset($_FILES['image']['error']) && $_FILES['image']['error'] == '0') {
            
                $cfile = new CURLfile($_FILES['image']['tmp_name'],$_FILES['image']['type'],$_FILES['image']['name']);

                 $postdata = array("departmentimage"=>$cfile);

                 $function = 'update_department_icon/' . $id ;
         
                 ApiCallPost($function, $postdata);


         }

        if(isset($_FILES['dep_image']['error']) && $_FILES['dep_image']['error'] == '0') {
                
                $cfile = new CURLfile($_FILES['dep_image']['tmp_name'],$_FILES['dep_image']['type'],$_FILES['dep_image']['name']);

                 $postdata = array("department_backimage"=>$cfile);
                 
                 $function = 'update_department_image/' . $id ;
         
                 ApiCallPost($function, $postdata);

         }



    }





    
}
