<?php 
class Export_model extends CI_Model {
    public function _consruct(){
        parent::_construct();
    }


    function update_products_from_orders() {
        $data = "SELECT * FROM `orders` WHERE `updated_at` LIKE '%2020-04%' AND `status` = 4 ORDER BY `id` DESC";
        $result = $this->db->query($data)->result();
        foreach ($result as $orders) {
            $order = unserialize($orders->snapshots);
            $order_reference = $order['reference'];
            $order_placed_date = $order['created_at'];

            foreach ($order['products'] as $product) {
                
            $num = $this->db->where('sku', $product['sku'])->get('export')->num_rows();

            if ($num > 0){

                $query = $this->db->where('sku', $product['sku'])->get('export')->row();

                $quantity = $query->quantity;

                $new_quantity = $quantity + 1;

                $data = array('quantity' => $new_quantity);

                $this->db->where('sku', $product['sku'])->update('export', $data);


            }else{

                $rs = array(
                        'sku' =>$product['sku'],
                        'name' =>$product['name'],
                        'department' =>$product['department'],
                        'category' =>$product['category'],
                        'subcategory' =>$product['subcategory'],
                        'brand' =>$product['brand'],
                        'vendor' =>$product['vendor'],
                        'packaging' =>$product['packaging'],
                        'maximum_selling_price' =>$product['maximum_selling_price'],
                        'valucart_price' =>$product['valucart_price'],
                        'order_reference' =>$order_reference,
                        'order_placed' =>$order_placed_date,
                        'quantity' => $product['quantity']
                        
                        );
                $this->db->insert('export', $rs); 

            }

            }

            // foreach ($order['bundles'] as $bundle) {
               
            // $rs = array(
            //             'name' =>$bundle['name'],
            //             'maximum_selling_price' =>$bundle['maximum_selling_price'],
            //             'valucart_price' =>$bundle['valucart_price'],
            //             'quantity' => $bundle['quantity'],
            //             'order_reference' =>$order_reference,
            //             'order_placed' =>$order_placed_date,
                        
            //             );
            // $this->db->insert('export_bundle', $rs); 


            // }

        }
    }


    function get_products() {
        $data = "SELECT * FROM `coupons` WHERE `coupon` LIKE '%ET%'
";
        $result = $this->db->query($data)->result();
        return $result;

    }

    function get_wallettransaction() {
        $data = "SELECT * FROM `wallet_transactions` WHERE `description` LIKE 'Credited Wallet'";
        $result = $this->db->query($data)->result();
        return $result;
    }




    
}
