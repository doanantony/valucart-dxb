<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Valucart | Invoice</title>
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
         <section class="invoice">
            <!-- title row -->
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
            <!-- info row -->
            <div class="row invoice-info">
               <div class="col-sm-4 invoice-col">
                  From
                  <address>
                     <strong>Valucart General Trading LLc.</strong><br>
                     Office no: 115<br>
                     Garhoud Star Building - Dubai<br>
                     Phone: 04 223 1188<br>
                     Email: info@valucart.com
                  </address>
               </div>
               <!-- /.col -->
               <div class="col-sm-4 invoice-col">
                  To
                  <address>
                     <strong><?php echo  $data['data']['customer']['name'] ?></strong><br>
                     Adress: <?php echo $data['data']['delivery_information']['address'] ?><br>
                     Phone: <?php echo  $data['data']['customer']['phone_number'] ?><br>
                     Email: <?php echo  $data['data']['customer']['email'] ?><br>
                     Delivery date/time: <?php echo $data['data']['delivery_information']['date'] ?>, <?php echo $data['data']['delivery_information']['time'] ?>
                  </address>
               </div>
               <!-- /.col -->
               <div class="col-sm-4 invoice-col">
                  <b>Invoice: <?php echo $data['data']['reference'] ?></b><br>
                  <b>Order Date:</b><?php echo $data['data']['created_at'] ?><br>
                  <b>Payment Due:</b> <br>
                  <b>TRN:</b> 100546479500003
               </div>
               <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- Table row -->
            <div class="row">
               <div class="col-xs-12 table-responsive">
                  <!-- products -->
                  <?php
                     if(count($data['data']['products']) > 0){
                     ?>
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>Item</th>
                           <th>SKU</th>
                           <th>Vendor</th>
                           <th>Brand</th>
                           <th>Pkg</th>
                           <th>Qty</th>
                           <th>Unit Price</th>
                           <th>Total Price</th>
                           <th>Allow Alternatives</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <?php
                              foreach($data['data']['products'] as $product) {
                              ?>
                           <td><?php echo $product['name'] ?></td>
                           <td><?php echo $product['sku'] ?></td>
                           <td><?php echo $product['department'] ?></td>
                           <td><?php echo $product['brand'] ?></td>
                           <td><?php echo $product['packaging'] ?></td>
                           <td><?php echo $product['quantity'] ?></td>
                           <td><?php echo $product['price'] ?></td>
                           <td><?php echo $product['price'] * $product['quantity'] ?></td>
                           <td><span class="center label  <?php if($product['allow_alternatives'] == 'Yes')
                             {
                              echo "label-success";
                              }else
                              { 
                                echo "label-danger"; 
                              }
                              ?>"><?php if($product['allow_alternatives'] == 'Yes')
                              {
                                echo "YES";
                              }else
                              { 
                                echo "NO"; 
                              }
                              ?></span>                                                         
                         </td> 
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                  </table>
                  <?php
                     }
                     ?>
                  <!-- products -->
                  <!-- bundles -->
                  <?php
                     if(count($data['data']['bundles']) > 0){
                     
                     ?>
                  <?php
                     foreach($data['data']['bundles'] as $bundle) {
                     ?>
                  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;color:#6610f2;" >
                     <strong > BUNDLE: <?php echo $bundle['name'] ?> </strong>
                     Qty : <?php echo $bundle['quantity'] ?> |
                     Unit price : <?php echo $bundle['price'] ?> |
                     Total price: <?php echo $bundle['price'] ?> * <?php echo $bundle['quantity'] ?>
                  </p>
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>Bundled items </th>
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
                              foreach($bundle['products'] as $product) {
                              ?>
                           <td><?php echo $product['name'] ?></td>
                           <td><?php echo $product['sku'] ?></td>
                           <td><?php echo $product['vendor'] ?></td>
                           <td><?php echo $product['brand'] ?></td>
                           <td><?php echo $product['packaging'] ?></td>
                           <td><?php echo $product['quantity'] ?></td>
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
                  <!-- bundles -->
                  <!-- customer bundles -->
                  <?php
                     if(count($data['data']['customer_bundles']) > 0){
                     
                     ?>
                  <?php
                     foreach($data['data']['customer_bundles'] as $bundle) {
                     ?>
                  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;color:#6610f2;" >
                     <strong > BUNDLE: <?php echo $bundle['name'] ?> </strong>
                     Qty : <?php echo $bundle['quantity'] ?> |
                     Unit price : <?php echo $bundle['price'] ?> |
                     Total price: <?php echo $bundle['price'] ?> * <?php echo $bundle['quantity'] ?>
                  </p>
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>Bundled items </th>
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
                              foreach($bundle['products'] as $product) {
                              ?>
                           <td><?php echo $product['name'] ?></td>
                           <td><?php echo $product['sku'] ?></td>
                           <td><?php echo $product['vendor'] ?></td>
                           <td><?php echo $product['brand'] ?></td>
                           <td><?php echo $product['packaging'] ?></td>
                           <td><?php echo $product['quantity'] ?></td>
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
                  <!-- customer bundles -->
               </div>
               <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
               <!-- accepted payments column -->
               <div class="col-xs-6">
                  <p class="lead">Payment Methods:</p>
                  <img src="<?php echo base_url('assets/credit/visa.png') ;?>" alt="Visa">
                  <img src="<?php echo base_url('assets/credit/mastercard.png') ;?>" alt="Mastercard">
                  <img src="<?php echo base_url('assets/credit/american-express.png') ;?>" alt="American Express">
                  <img src="<?php echo base_url('assets/credit/paypal2.png') ;?>" alt="Paypal">
                  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;color:#6610f2;">
                     Payment Type: <?php echo $order->payment_type; ?>
                  </p>
               </div>
               <!-- /.col -->
               <div class="col-xs-6">
                  <!--  <p class="lead">Amount Due 2/22/2014</p> -->
                  <div class="table-responsive">
                     <table class="table">
                        <tr>
                           <th style="width:50%">Subtotal:</th>
                           <td>AED<?php echo $data['data']['sub_total'] ?></td>
                        </tr>
                        <tr>
                           <th style="width:50%">Delivery Charges:</th>
                           <td>AED<?php echo $data['data']['delivery_charge'] ?></td>
                        </tr>
                        <tr>
                           <th>Discount</th>
                           <td>AED<?php echo $data['data']['discount'] ?></td>
                        </tr>
                        <tr>
                           <th>VAT:</th>
                           <td><?php echo $data['data']['vat'] ?></td>
                        </tr>
                        <?php
                           if(count($data['data']['valucredits']) > 0){
                           
                            ?>
                        <tr>
                           <th>Valucredits:</th>
                           <td>AED<?php echo $data['data']['valucredits'] ?></td>
                        </tr>
                        <?php
                           }
                           ?>
                        <tr>
                           <th>Total:</th>
                           <td>AED<?php echo $data['data']['total'] ?></td>
                        </tr>
                     </table>
                  </div>
               </div>
               <!-- /.col -->
            </div>
            <!-- /.row -->
         </section>
      </div>
   </body>
</html>