 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>

        Invoice
        <small><?php echo $data['data']['reference'] ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Orders</a></li>
        <li class="active">Invoice</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-invoice"></i> Order Invoice
            <small class="pull-right"><strong>Date: <?php
echo  date("d/m/Y");
?></strong></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
 
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
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
             
            <tr>
              <?php
               foreach($data['data']['products'] as $product) {
              ?>

              <td><?php echo $product['name'] ?></td>
              <td><?php echo $product['sku'] ?></td>
              <td><?php echo $product['vendor'] ?></td>
              <td><?php echo $product['brand'] ?></td>
              <td><?php echo $product['packaging'] ?></td>
              <td><?php echo $product['quantity'] ?></td>
              <td><?php echo $product['price'] ?></td>
              <td><?php echo $product['price'] * $product['quantity'] ?></td>
              <td class="center"><a class="btn btn-xs btn-danger" href="<?php echo base_url();?>allorders/remove/<?php echo $order_id; ?>/<?php echo $product['id']; ?>" onClick="return doconfirm()"><i class="fa fa-fw fa-trash"></i>Remove</a> </td>
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
             <strong > BUNDLES: <?php echo $bundle['name'] ?> </strong>
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

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">

          <a href="<?php echo base_url(); ?>allorders/invoice_generate/<?php echo $order_id; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>

          <!-- <button type="button" class="btn btn-success "><i class="fa fa-download"></i>  PDF </button> -->

         
        </div>
      </div>
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>