<link rel="stylesheet" href="<?php echo base_url('assets/css/morris.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/jvectormap/jquery-jvectormap.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/js/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/morris.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/raphael.min.js'); ?>"></script>
<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
<link rel="manifest" href="manifest.json">
<style type="text/css">
   .general_div {
   cursor: pointer;
   }
</style>
<div class="content-wrapper" style="min-height: 550px">
   <section class="content-header">
      <h1>
         Dashboard
         <small>Quick Info</small>
      </h1>
   </section>
   <section class="content">
      <div class="row">
         <!-- <div class="box box-info box-solid" > -->
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="customers">
               <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>
               <div class="info-box-content ">
                  <span class="info-box-text">Customers</span>
                  <span class="info-box-number" id="merchant"><?php echo total_customers(); ?></span>
               </div>
            </div>
         </div>
         <!-- /.col -->
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="coupons">
               <span class="info-box-icon bg-pink"><i class="fa fa-copyright"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Coupons</span>
                  <span class="info-box-number" id="subcategories"><?php echo total_coupons(); ?></span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="brands">
               <span class="info-box-icon bg-yellow"><i class="fa fa-bitcoin"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Brands</span>
                  <span class="info-box-number" id="brands"><?php echo total_brands(); ?></span>
               </div>
            </div>
         </div>
         <div class="clearfix visible-sm-block"></div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="Bundlecategories">
               <span class="info-box-icon bg-pink"><i class="fa fa-gift"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Bundle Categories</span>
                  <span class="info-box-number" id="Bundlecategories"><?php echo total_bundle_categories(); ?></span>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="departments">
               <span class="info-box-icon bg-aqua"><i class="fa fa-building-o"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Departments</span>
                  <span class="info-box-number" id="departments"><?php echo total_departments(); ?></span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="categories">
               <span class="info-box-icon bg-green"><i class="fa fa-list"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Categories</span>
                  <span class="info-box-number" id="categories"><?php echo total_categories(); ?></span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="subcategories">
               <span class="info-box-icon bg-red"><i class="fa fa-list-alt"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Sub Categories</span>
                  <span class="info-box-number" id="subcategories"><?php echo total_subcategories(); ?></span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box general_div" data-id="communities">
               <span class="info-box-icon bg-blue"><i class="ion ion-ios-analytics-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Communities</span>
                  <span class="info-box-number" id="communities"><?php echo total_communities(); ?></span>
               </div>
            </div>
         </div>
         <div class="clearfix visible-sm-block"></div>
      </div>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-xs-8">
            <div class="box box-info box-solid">
               <div class="box-header with-border">
                  <h3 class="box-title">Latest Orders</h3>
                  <div class="box-tools pull-right">
                     <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                     </button>
                     <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <div class="box-body">
                  <div class="table-responsive">
                     <table class="table no-margin">
                        <thead>
                           <tr>
                              <th>Order ID</th>
                              <th>Customer</th>
                              <th>Status</th>
                              <th>Delivery Date</th>
                              <th>Delivery Time</th>
                              <th>Price</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              foreach($latestorders as $orders) {
                                    
                              ?>
                           <tr>
                              <td class="hidden"><?php echo $orders->id; ?></td>
                              <td class="center"><?php echo $orders->order_reference; ?></td>
                              <td class="center"><?php echo get_customer_name($orders->customer_id); ?></td>
                              <td><span class="center label  <?php if($orders->status == '1')
                                 {
                                 echo "label label-info";
                                 }elseif($orders->status == '2')
                                 { 
                                 echo "label label-warning"; 
                                 }elseif($orders->status == '3')
                                 { 
                                 echo "label label-success"; 
                                 }else{
                                 echo "label label-danger";   
                                 }
                                 ?>"><?php if($orders->status == '1')
                                 {
                                     echo "Order Created";
                                 }elseif($orders->status == '2'){
                                     echo "Order Placed";
                                 }elseif($orders->status == '3'){
                                      echo "Shipped";
                                 }
                                 else
                                 { 
                                 echo "Delivered"; 
                                 }
                                 ?></span>                                                         
                              </td>
                              <td class="center"><?php echo $orders->delivery_date; ?></td>
                              <td class="center"><?php echo get_time_slot($orders->time_slot_id); ?></td>
                              <td class="center">AED<?php echo $orders->price; ?></td>
                           </tr>
                           <?php
                              }
                              ?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="box-footer clearfix">
                  <a href="<?php echo base_url(); ?>Allorders/neworders" class="btn btn-sm btn-success btn-flat pull-right">View All Orders</a>
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="info-box bg-aqua">
               <span class="info-box-icon"><i class="ion-ios-pricetag-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">FALCON FRESH</span>
                  <span class="info-box-number">0</span>
                  <div class="progress">
                     <div class="progress-bar" style="width: 0%"></div>
                  </div>
                  <span class="progress-description">
                  0% Increase in Total Items
                  </span>
               </div>
            </div>
            <div class="info-box bg-yellow">
               <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">EMCOOP ITEMS</span>
                  <span class="info-box-number"><?php echo total_emcoop_products(); ?></span>
                  <div class="progress">
                     <div class="progress-bar" style="width: 80%"></div>
                  </div>
                  <span class="progress-description">
                  <?php echo round(((total_emcoop_products()/total_products())*100),2); ?>% Increase in Total Items
                  </span>
               </div>
            </div>
            <div class="info-box bg-green">
               <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">VCGT ITEMS</span>
                  <span class="info-box-number"><?php echo total_vcgt_products(); ?></span>
                  <div class="progress">
                     <div class="progress-bar" style="width: 20%"></div>
                  </div>
                  <span class="progress-description">
                  <?php echo round(((total_vcgt_products()/total_products())*100),2); ?>% Increase in Total Items
                  </span>
               </div>
            </div>
            <div class="info-box bg-red">
               <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">MEATONE ITEMS</span>
                  <span class="info-box-number"><?php echo total_meatone_products(); ?></span>
                  <div class="progress">
                     <div class="progress-bar" style="width: 10%"></div>
                  </div>
                  <span class="progress-description">
                  <?php echo round(((total_meatone_products()/total_products())*100),2); ?>% Increase in Total Items
                  </span>
               </div>
            </div>
            
         </div>
      </div>
   </section>
   <section class="content">

  

      <div class="row">
         <div class="col-xs-8">
            <div class="col-lg-4 col-xs-12">
               <!-- small box -->
               <div class="small-box bg-aqua">
                  <div class="inner">
                     <h3 id="issued"><?php echo total_products(); ?></h3>
                     <p>Total Products</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-ios-cart-outline"></i>
                  </div>
                  <a href="<?php echo base_url('products'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-4 col-xs-12">
               <div class="small-box bg-green">
                  <div class="inner">
                     <h3 id="actived"><?php echo total_featured_products(); ?></h3>
                     <p>Featured Products</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-stats-bars"></i>
                  </div>
                  <a href="<?php echo base_url('products/featured'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-4 col-xs-12">
               <div class="small-box bg-yellow">
                  <div class="inner">
                     <h3 id="suspend"><?php echo total_offered_products(); ?></h3>
                     <p>Offer Products</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-stats-bars"></i>
                  </div>
                  <a href="<?php echo base_url('products/offer'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-4 col-xs-12">
               <!-- small box -->
               <div class="small-box bg-red">
                  <div class="inner">
                     <h3 id="remaining"><?php echo total_cust_bundle_products(); ?></h3>
                     <p>Customer Bundlelable</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-pie-graph"></i>
                  </div>
                  <a href="<?php echo base_url('products/userbundelable'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-4 col-xs-12">
               <!-- small box -->
               <div class="small-box bg-aqua">
                  <div class="inner">
                     <h3 id="topup_count"><?php echo total_admin_bundle_products(); ?></h3>
                     <p>Admin Bundelable</p>
                  </div>
                  <div class="icon">
                     <i class="fa fa-mobile"></i>
                  </div>
                  <a href="<?php echo base_url('products/adminbundelable'); ?>" class="small-box-footer" style="color: #000">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-4 col-xs-12">
               <!-- small box -->
               <div class="small-box" style="border:1px solid #d4d8dc">
                  <div class="inner">
                     <h3 id="topup_amount"><?php echo total_bundles(); ?></h3>
                     <p>Total Bundles</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-ios-cart-outline"></i>
                  </div>
                  <a href="<?php echo base_url('bundles'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <!-- ./col -->
         </div>
         <div class="col-md-4">
            <!-- PRODUCT LIST -->
            <div class="box box-info box-solid">
               <div class="box-header with-border">
                  <h3 class="box-title">Recently Added Vendors</h3>
                  <div class="box-tools pull-right">
                     <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                     </button>
                     <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <ul class="products-list product-list-in-box">
                     <?php
                        // print_r($data);die;
                         foreach($result as $vendors) {
                                                ?>
                     <li class="item">
                        <div class="product-img">
                           <img src="<?php echo $vendors->image; ?>?w=50&h=50" alt="Product Image">
                        </div>
                        <div class="product-info">
                           <a href="javascript:void(0)" class="product-title"><?php echo $vendors->name; ?>
                           <span class="label label-success pull-right">0</span></a>
                           <span class="product-description">
                           <?php echo $vendors->description; ?>
                           </span>
                        </div>
                     </li>
                     <?php
                        }
                        ?>
                  </ul>
               </div>
               <!-- /.box-body -->
               <div class="box-footer text-center">
                  <a href="<?php echo base_url('vendors'); ?>" class="uppercase">View All Vendors</a>
               </div>
               <!-- /.box-footer -->
            </div>
         </div>
      </div>



   </section>
   <!-- Content Header (Page header) -->
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script>
<script type="text/javascript">
  
   refresh_home();        
   
   setInterval(
       function(){ 
           refresh_home();
       }, 5000
   );
   
   function refresh_home(){
       $.ajax({
           type: "POST",
           url: '<?php echo base_url("home/statics"); ?>',
           success: function(response) {
            response = JSON.parse(response);
           },
           error: function(response) {
               //reject(new Error("Script load error: " + response));
           },
           async: false
           });
       }

   
   $(".general_div").on('click',function(){
   var div = $(this).data('id');
   window.location.href = base_url+div;
   })

</script>