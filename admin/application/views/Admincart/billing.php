

<link rel="stylesheet" href="<?php echo base_url('assets/css/morris.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-datepicker.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/daterangepicker.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/daterangepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/morris.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/raphael.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.min.js'); ?>"></script>


<div class="content-wrapper">


  <section class="content-header">
    <h1>
      Place New Order
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Orders</a></li>
      <li class="active">Invoice</li>
    </ol>
  </section>



  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- left column -->
      <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Order Details</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <!-- Main content -->
          <section class="invoice">

            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Qty</th>
                      <th>Price</th>

                    </tr>
                  </thead>
                  <tbody>


                    <?php
                    $grand_total = 0;
// Calculate grand total.
                    if ($cart = $this->cart->contents()):
                      foreach ($cart as $data):
                        $grand_total = $grand_total + $data['subtotal'];
                        $delivery_charge = 7.5;
                        $total = $grand_total + $delivery_charge;
                      endforeach;
                    endif;
                    ?>   



                    <?php 
                    if(isset($cart) && is_array($cart) && count($cart)){
          // echo "<pre>";
          // print_r($cart);
                      $i=1;
                      foreach ($cart as $key => $data) { 
                        ?>



                        <tr class="item first rowid<?php echo $data['rowid'] ?>">

                          <td class="name"><?php echo $data['name'] ?></td>
                          <td class="name"><?php echo $data['qty'] ?></td>
                          <td class="price">AED 
                            <span class="price<?php echo $data['rowid'] ?>"><?php echo $data['price'] ?>
                          </span>
                        </td>

                      </tr>




                      <?php
                      $i++;
                    } }
                    ?>


                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">

              <div class="col-xs-6">


                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <th style="width:50%">Subtotal:</th>
                      <td><?php echo $grand_total; ?></td>
                    </tr>
                    <tr>
                      <th>Tax (0.0%)</th>
                      <td>0</td>
                    </tr>
                    <tr>
                      <th>Shipping Fee:</th>
                      <td>7.5</td>
                    </tr>
                    <tr>
                      <th>Total:</th>
                       <td>AED: <?php echo $total; ?></td>
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
                <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
                </button>
                <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                  <i class="fa fa-download"></i> Generate PDF
                </button>
              </div>
            </div>
          </section>
        </div>






      </div>
      <!--/.col (left) -->
      <!-- right column -->
      <div class="col-md-6">
        <!-- Horizontal Form -->
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Customer Details Form</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <!-- <form class="form-horizontal"> -->
            <form role="form" action="" method="post"  data-parsley-validate="" class="form-horizontal"  enctype="multipart/form-data">
              <div class="box-body">





               <div class="form-group has-feedback">
                <label for="exampleInputEmail1" class="col-sm-2 control-label"> Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="150"   required="" placeholder="Please Enter Customer Name" value="">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>
              </div>


              <div class="form-group has-feedback">
                <label for="exampleInputEmail1" class="col-sm-2 control-label"> Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control " name="email" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="150"   required="" placeholder="Please Enter Customer Email" value="">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>
              </div>


              <div class="form-group has-feedback">
                <label for="exampleInputEmail1" class="col-sm-2 control-label">Phone No</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control " name="phone_no" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="15"   required="" placeholder="Please Enter Customer Phone " value="">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>
              </div>


              <div class="form-group has-feedback">
                <label for="exampleInputEmail1" class="col-sm-2 control-label">Address</label>
                <div class="col-sm-10">
                  <textarea id="compose-textarea" class="form-control" name="adress" data-parsley-trigger="change" required="" style="height: 100px"></textarea>
                  <span class="glyphicon  form-control-feedback"></span>
                </div>
              </div>


                <div class="form-group has-feedback">
                <label for="exampleInputEmail1" class="col-sm-2 control-label">Del Date</label>
                 <div class="col-sm-10">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="delivery_date" class="form-control pull-right" required="" id="datepicker">
                </div>
              </div>
                <!-- /.input group -->
              </div>


              <div class="form-group has-feedback">
                  <label for="exampleInputEmail1" class="col-sm-2 control-label">Del Time</label>
                       <div class="col-sm-10">
                        <select name="delivery_time" class="form-control select2 required">
                          <option value="" selected="selected">Select Delivery Time </option>
                              <?php
                                  foreach ($timeslots as $rs) {?>
                          <option value="<?php echo $rs->time_slots; ?>"><?php echo $rs->time_slots; ?></option>
                              <?php } ?>
                      </select>
              </div>
            </div>





              
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" class="btn btn-info pull-right">Place Order</button>
            </div>
            <!-- /.box-footer -->
          </form>
        </div>
        <!-- /.box -->

        <!-- /.box -->
      </div>
      <!--/.col (right) -->
    </div>
    <!-- /.row -->
  </section>
</div>




<script type="text/javascript">




    $('#datepicker').datepicker({
      autoclose: true
    })

    $('#datepickers').datepicker({
      autoclose: true
    })

</script>
