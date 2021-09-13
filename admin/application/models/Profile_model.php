<?php 
class Profile_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function update_admin($data,$arr){ 
     //   print_r($arr);die;
        $this->db->where('id',$id);
        $result = $this->db->update('users',$data);
        if($result){
                return true;      
              }          
        }
        function get_admin($id){
            $query = $this->db->get('admin',array('id'=>$id));
            $result = $query->row();
            return $result;
        }
        function update_admin_password($data,$arr){   
       
            $data_update =array('passwd' => md5($data['n_password']));
            $query = $this->db->get_where('users',array('user_id' => $arr['user_id'],'user_type_id' => $arr['user_type_id'],'passwd' => md5($data['password'])));
            $result1 = $query->row();
                if($result1){
                    $this->db->where($arr); 
                    $result2 = $this->db->update('users',$data_update);
                    if($result2){
                        return true;
                    }   
                }
        }








}
