<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>Orders
         <small>Out to Delivery</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="#">Manage Orders</a></li>
         <li class="active">Delivery</li>
      </ol>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Products For Delivery On: <?php
                     echo $this->uri->segment(3);
                     ?></h3>
                  <div class="pull-right box-tools">
                     <a class="btn btn-xs btn-primary" href="<?php echo base_url();?>allorders"><i class="fa fa-fw fa-backward"></i>Go Back</a>
                  </div>
                  <?php if ($no_of_orders > 0){?>
                  <div class="col-xs-12" id="summery_output">
                     <div class="row">
                        <section class="invoice" style="clear:both;">
                           <div class="row">
                              <div class="col-xs-12">
                                 <h2 class="page-header">
                                    <i class="fa fa-bus"></i>&nbsp;<span class="company_info"> Delivery Information   </span>
                                    <small class="pull-right">Date: <?php echo date("d/M/Y"); ?></small>
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
                           <!-- this row will not appear when printing -->
                           <div class="row no-print">
                              <div class="col-xs-12">
                                 <a href="<?php echo base_url(); ?>allorders/deliveryinvoice_generate/<?php echo $this->uri->segment(3); ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                                 <!-- <button type="button" class="btn btn-success "><i class="fa fa-download"></i>  PDF </button> -->
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
   <!-- /.content -->
</div>