<?php 
class Resetkey_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function check_portal_users($f_password, $password) {
        if($f_password == $password){
            return true;
        }else{
            return false;
        }
    }
    
}
