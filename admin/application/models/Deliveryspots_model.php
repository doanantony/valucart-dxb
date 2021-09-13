<?php 
class Deliveryspots_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function get_spots(){

        $session_data = $this->session->userdata('logged_in');
        $vendor_id = $session_data['user_id'];
        $data = "select departments.name, vendor_location.id,vendor_location.name,vendor_location.short_name,vendor_location.range, vendor_location.latitude, vendor_location.longitude,vendor_location.published from departments left join vendor_location on departments.id = vendor_location.vendor_id where vendor_location.vendor_id='$vendor_id'";

        $result = $this->db->query($data)->result();
        return $result;
    }

    function save_spots($data) {
        $session_data = $this->session->userdata('logged_in');
        $data = array(
                    'vendor_id' => $session_data['user_id'],
                    'name' => $data['location'],
                    'short_name' => $data['name'],
                    'latitude' => $data['lat'],
                    'longitude' => $data['lng'],
                    'range' => $data['range'],
                    'published' => $data['status']

            );
         $result = $this->db->insert('vendor_location', $data); 
            $insert_id = $this->db->insert_id();
            
            $log = array(
                         'id' =>$insert_id,
                         'log' => 'Created vendor location '.$data['name']. ''
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


    function get_terminal_info(){
        $rs = $this->db->query("SELECT vendor_location.latitude,vendor_location.longitude,vendor_location.id as name FROM vendor_location")->result();
        return $rs;
    }


    function update_location_status($id,$data){
        $this->db->where('id',$id);
        $result = $this->db->update('vendor_location',$data);
        return $result;
    }









  



    }







