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

    <div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> Alert:</h4>
        This page has been enhanced for printing. Click the print button at the bottom  to print the invoice.
      </div>
    </div><div class="alert alert-primary" role="alert">
  This is a primary alert—check it out!
</div>
    
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
            <strong><?php echo  $data['customer']['name'] ?></strong><br>
            Adress: <?php echo $data['delivery_adress'] ?><br>
            Phone: <?php echo  $data['customer']['telephone'] ?><br>
            Email: <?php echo  $data['customer']['email'] ?><br>
            Delivery date/time: <?php echo $data['delivery_date'] ?>, <?php echo $data['delivery_time'] ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice: <?php echo $data['reference'] ?></b><br>
          <b>Order Date:</b><?php echo $data['created_at'] ?><br>
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
               if(count($data['products']) > 0){
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
            </tr>
            </thead>
            <tbody>
             
            <tr>
              <?php
               foreach($data['products'] as $product) {
               
              ?>

              <td><?php echo $product['name'] ?></td>
              <td><?php echo $product['product_details']->sku; ?></td>
              <td><?php echo $product['vendor'] ?></td>
              <td><?php echo $product['brand'] ?></td>
              <td><?php echo $product['packaging'] ?></td>
              <td><?php echo $product['qty'] ?></td>
              <td><?php echo $product['price'] ?></td>
              <td><?php echo $product['subtotal']?></td>
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
               if(count($data['bundles']) > 0){

            ?>

            <?php
               foreach($data['bundles'] as $bundle) {
            ?>

            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;color:#6610f2;" >
             <strong > BUNDLE: <?php echo $bundle['name'] ?> </strong>
             Qty : <?php echo $bundle['qty'] ?> |
             Unit price : <?php echo $bundle['price'] ?> |
             Total price: <?php echo $bundle['subtotal'] ?>
            </p>


          
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
            Payment Type: COD
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
         <!--  <p class="lead">Amount Due 2/22/2014</p> -->

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>AED<?php echo $data['sub_total'] ?></td>
              </tr>
              <tr>
                <th style="width:50%">Delivery Charges:</th>
                <td>AED<?php echo $data['delivery_charge'] ?></td>
              </tr>
              <tr>
                <th>Discount</th>
                <td>AED<?php echo $data['discount'] ?></td>
              </tr>
              <tr>
                <th>VAT:</th>
                <td><?php echo $data['vat'] ?></td>
              </tr>
              <tr>
                <th>Total:</th>
                 <td>AED<?php echo $data['total'] ?></td>
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

         <!--  <a href="<?php echo base_url(); ?>allorders/invoice_generate/<?php echo $order_id; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> -->

          <!-- <button type="button" class="btn btn-success "><i class="fa fa-download"></i>  PDF </button> -->

         
        </div>
      </div>
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>