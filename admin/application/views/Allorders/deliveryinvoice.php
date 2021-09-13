<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Valucart |Delivery Invoice</title>
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome/css/font-awesome.min.css">
      <!-- Ionicons -->
      <!--   <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css"> -->
      <!-- Theme style -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/AdminLTE.min.css">
      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <!-- Google Font -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
   </head>
   <body onload="window.print();">
      <div class="wrapper">
         <!-- Main content -->
         <section class="content">
            <div class="row">
               <div class="row">
                  <div class="col-xs-12">
                     <h2 class="page-header">
                        <img src="<?php echo base_url(); ?>assets/uploads/logo.svg" width="200px"/>
                        <small class="pull-right"><strong>Date: <?php
                           echo  date("d/m/Y");
                           ?></strong></small>
                     </h2>
                  </div>
                  <!-- /.col -->
               </div>
               <!-- left column -->
               <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="">
                     <div class="">
                        <?php if ($no_of_orders > 0){?>
                        <div class="col-xs-12" id="summery_output">
                           <div class="row">
                              <section class="invoice" style="clear:both;">
                                 <div class="row">
                                    <div class="col-xs-12">
                                       <h2 class="page-header">
                                          <i class="fa fa-bus"></i>&nbsp;<span class="company_info"> Delivery Information   </span><small class="pull-right">Date: <?php echo date("d/M/Y"); ?></small>
                                       </h2>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm-6 invoice-col">
                                       <b></b>
                                       <br>
                                       <br>
                                       <b>Delivery Date:</b>&nbsp;<span class="company_info"><?php
                                          echo $this->uri->segment(3);
                                          ?></span>
                                       <br>
                                       <hr/>
                                       <b>Vendors:</b>&nbsp;<span id="trans_count"><?php echo $vendors ?></span>
                                       <br>
                                       <hr/>
                                    </div>
                                    <div class="col-sm-6 invoice-col">
                                       <b></b>
                                       <br>
                                       <br>
                                       <b>No of Orders:</b>&nbsp;<span class="company_info"><?php echo $no_of_orders ?></span>
                                       <br>
                                       <hr/>
                                       <!-- <b>Vendors:</b>&nbsp;<span id="trans_count">20</span><br><hr/> -->
                                    </div>
                                 </div>
                                 <div class="row no-print">
                                    <div class="col-xs-12">
                                    </div>
                                 </div>
                              </section>
                              <section class="invoice">
                                 <h3 class="box-title">Products to be Picked & Packed</h3>
                                 <div class="row">
                                    <div class="col-xs-12 table-responsive">
                                       <?php
                                          if(count($departments) > 0){
                                          ?>
                                       <?php
                                          foreach($departments as $department) {
                                          ?>
                                       <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;color:#6610f2;">
                                          <strong> DEPARTMENT: <?php echo $department ?> </strong>
                                       </p>
                                       <table class="table table-striped">
                                          <thead>
                                             <tr>
                                                <th>Order Reference</th>
                                                <th>Item</th>
                                                <th>SKU</th>
                                                <th>Vendor</th>
                                                <th>Brand</th>
                                                <th>Pkg</th>
                                                <th>Qty</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <?php
                                                   foreach($orderdata as $product) {
                                                   ?>
                                                <?php
                                                   if(isset($product) && $product['department'] == $department) {
                                                   
                                                   ?>
                                                <td><?php echo $product['order_reference'] ?></td>
                                                <td><?php echo $product['name'] ?></td>
                                                <td><?php echo $product['sku'] ?></td>
                                                <td><?php echo $product['vendor'] ?></td>
                                                <td><?php echo $product['brand'] ?></td>
                                                <td><?php echo $product['packaging'] ?></td>
                                                <td><?php echo $product['quantity'] ?></td>
                                                <?php
                                                   }
                                                   ?>
                                             </tr>
                                             <?php
                                                }
                                                ?>
                                          </tbody>
                                       </table>
                                       <?php
                                          }
                                          ?>
                                       <?php
                                          }
                                          ?>
                                    </div>
                                 </div>
                              </section>
                           </div>
                        </div>
                        <?php }else{ ?>
                        <div class="col-xs-12" id="summery_output">
                           <div class="row">
                              <div class="error_div" style="text-align: center;">
                                 <br/>
                                 <h4 style="color: red">Oops! No any orders placed for this date!</h4>
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                  </div>
                  <!-- /.box -->
               </div>
            </div>
            <!-- /.row -->
         </section>
      </div>
   </body>
</html>