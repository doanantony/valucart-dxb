<link rel="stylesheet" href="<?php echo base_url('assets/css/morris.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/jvectormap/jquery-jvectormap.css'); ?>">
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
   <div style="padding: 20px 30px; background: rgb(243, 156, 18); z-index: 999999; font-size: 16px; font-weight: 600;"><a class="float-right" href="#" data-toggle="" data-placement="left" title="" style="color: rgb(255, 255, 255); font-size: 20px;"></a><a href="<?php echo base_url(); ?>Settings" style="color: rgb(249, 249, 249); display: inline-block; margin-right: 10px; text-decoration: underline;">Change the application system settings -delivery charges,minimum order amount !</a><a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>Settings" style="margin-top: -5px; border: 0px; box-shadow: none; color: rgb(243, 156, 18); font-weight: 600; background: rgb(255, 255, 255);">Click Here!</a></div>
   <section class="content-header">
      <h1>
         Dashboards
         <small>Quick Info</small>
      </h1>
   </section>
    <!-- Main content -->
    <section class="content">
           <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>0</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>0<sup style="font-size: 20px"></sup></h3>

              <p>Total Products</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>Featured Products</p>
            </div>
            <div class="icon">
              <i class="ion ion-pricetag"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>0</h3>

              <p>Coupons</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Placed Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-ms-8">
                  

                   <div class="col-xs-8">
            <div class="box box-info box-solid">
               
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
                  <!-- /.chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                 

                   <!-- PRODUCT LIST -->
                      <div class="box box-info box-solid">

            
            <!-- /.box-header -->
            <div class="box-body">
               <p class="text-center">
                    <strong>Recently Added Products</strong>
                  </p>
               <ul class="products-list product-list-in-box">
                     <?php
                        // print_r($data);die;
                         foreach($result as $product) {
                                                ?>
                     <li class="item">
                        <div class="product-img">
                           <img src="<?php echo get_product_image($product->id); ?>?w=50&h=50" alt="Product Image">
                        </div>
                        <div class="product-info">
                           <a href="javascript:void(0)" class="product-title"><?php echo $product->name; ?>
                           <span class="label label-success pull-right">AED<?php echo $product->valucart_price; ?></span></a>
                           <span class="product-description">
                           <?php echo $product->description; ?>
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
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            <!-- /.box-footer -->
          </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
           
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


      <!-- /.row -->
    </section>
    <!-- /.cont
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
               console.log(response.users);
               called_graph(response.chart);
               called_transact(response.transaction);
               called_users(response.users);
               called_card(response.cards);
               called_world_map(response.terminal)
               //resolve(response)
           },
           error: function(response) {
               //reject(new Error("Script load error: " + response));
           },
           async: false
           });
       }
   


   
   function called_users(data){
   $("#communities").html(data.communities);
   $("#brands").html(data.brands);
   $("#departments").html(data.departments);
   $("#categories").html(data.categories);
   $("#subcategories").html(data.subcategories);
   $("#sales").html(data.sales_users);
   $("#customers").html(data.customers);
   }
   
   $(".general_div").on('click',function(){
   var div = $(this).data('id');
   window.location.href = base_url+div;
   })

</script>