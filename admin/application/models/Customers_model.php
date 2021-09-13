<?php 
class Customers_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }



    function get_allcustomers($filter=null){
        if($filter) {
            if($filter['length']!=-1)
                $this->db->limit($filter['length'], $filter['start']);
            $this->db->order_by("customers.id","desc");
            $this->db->order_by("customers.".$filter['order'], $filter['order_type']);
            
            if(!empty($filter['where'])) {
                $this->db->where($filter['where']);
            }
        }

        $this->db->select("customers.id,customers.name,customers.email,customers.contact_email,customers.phone_number,customers.created_at");

        $this->db->from("customers");
        $result = $this->db->get()->result();
        return $result;

    }


    function get_single_customer($id) {
        $query = $this->db->where('id', $id);
        $query = $this->db->get('customers');
        $result = $query->row();
        return $result;
    }  


    function get_wallet_info($id){
        
       //$query = $this->db->order_by("id", "desc");
        $query = $this->db->where('customer_id', $id);
        $query =$this->db->get('wallet_transactions');
        $result = $query->result();
        return $result;
    }


    function topupwallet($data, $id) {

        $query = $this->db->where('id', $id);
        $query = $this->db->get('customers');
        $result = $query->row();
        $name = $result->name;
        $available_amt = $result->wallet;
        $total = $available_amt + $data['amount'];
        $array = array('wallet' => $total);
        
            $this->db->where('id',$id);
            $result = $this->db->update('customers',$array); 
            
                    
            $log = array(
                         'id' =>$id,
                         'log' => 'Recharge Wallet AED'.$data['amount'] .' to '.$name 
                      );

            $session_data = $this->session->userdata('logged_in');

            $res = updatelog($log,$session_data);

            $created_at = date("Y-m-d H:i:s");

            $wallet_transaction = array(

                                    'customer_id'    => $id,
                                    'description'    => 'Valucart Topup',
                                    'transact_amt'   => $data['amount'],
                                    'type'           => 'credited',
                                    'amt_left'       => $total,
                                    'created_at'     => $created_at
                                    );
            
            $this->db->insert('wallet_transactions', $wallet_transaction); 

            return "Success"; 

            
        }

    function get_cart($id){
        
        $query = $this->db->where('customer_id', $id);
        $query =$this->db->get('carts')->row();
        $cart_items = $this->db->where('cart_id', $query->id);
        $cart_items =$this->db->get('cart_items');
        $result = $cart_items->result();
        return $result;
    }

    }







