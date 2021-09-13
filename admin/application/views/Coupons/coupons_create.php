<link rel="stylesheet" href="<?php echo base_url('assets/css/morris.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-datepicker.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/daterangepicker.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/daterangepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/morris.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/raphael.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.min.js'); ?>"></script>



<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Coupons</a></li>
            <li class="active">Create</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-xs-12">
            <?php
               if($this->session->flashdata('message')) {
          $message = $this->session->flashdata('message');
              ?>
            <div class="alert alert-<?php echo $message['class']; ?>">
               <button class="close" data-dismiss="alert" type="button">×</button>
               <?php echo $message['message']; ?>
            </div>
            <?php
               }
               ?>
         </div>
         
         
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                
                <h3 class="box-title"><?php echo $page_data->function_head; ?></h3>
                <div class="pull-right box-tools">
            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>




              </div>
                <form role="form" action="" method="post"  data-parsley-validate="" class="validate"  enctype="multipart/form-data">
                 <div class="box-body">
                  <div class="col-md-6">

                            

                          <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Coupon Code</label>
                                <input type="text" class="form-control " name="coupon" data-parsley-trigger="change" data-parsley-minlength="3"   required="" placeholder="Enter Coupon Code">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>


                          <div class="form-group has-feedback">
                            <label>Payment Method</label>
                            <select class="form-control select2 required" name="for_payment_method">
                              <option value="cash">Cash</option>
                              <option value="card">Card</option>
                              <option value="all">All</option>
                            </select>
                          </div>

                           <div class="form-group has-feedback">
                            <label>For First Orders</label>
                            <select class="form-control select2 required" name="for_first_order">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                          </div>


                          <div class="form-group has-feedback">
                            <label>For All Customers</label>
                            <select class="form-control select2 required" name="for_all_customers">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                          </div>



                     <div class="row">
                       <div class="form-group col-md-11">
                          <label>Mode of Discount</label><br/>
                          <div class="col-md-3"><label><input type="radio" name="mode" value="csv" class="required">&nbsp;In Percent</label></div>
                         
                          <div class="col-md-3"><label><input type="radio" name="mode" value="individual" class="required">&nbsp;In AED</label></div>
                       </div>
                     </div>

  

                  <div class="row mode" id="csv">
                      <div class="col-md-12">
                      <label>Discount(In Percent)</label>
                      <div class="card_div">
                        <div class="row">
                          <div class="col-md-11">
                          <input name="percent" type="text" class="form-control"></div><div class="col-md-1"></div>
                        </div>
                      </div>
                      </div>
                  </div>




                    
                     <div class="row mode" id="individual">
                      <div class="col-md-12">
                       <label>Discount(In Aed)</label>
                      <div class="card_div">
                        <div class="row">
                          <div class="col-md-11">
                          <input name="aed" type="number" id="cards" class="form-control required unique_no"></div><div class="col-md-1"></div>
                        </div>
                      </div>
                      </div>
                  </div>


                </div>



                          <div class="col-md-6">

                          
                          <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Minimum Order Value</label>
                                <input type="text" class="form-control " name="minimum_order_value" data-parsley-trigger="change"  data-parsley-maxlength="10"  data-parsley-type="digits" required="" placeholder=" Enter Minimum Order Value" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>


                           <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Usage Limit</label>
                                <input type="text" class="form-control " name="usage_limit" data-parsley-trigger="change"  data-parsley-maxlength="10"  data-parsley-type="digits" required="" placeholder=" Enter Usage Limit" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>


                <div class="form-group">
                <label>Start Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="start" class="form-control pull-right" required="" id="datepicker">
                </div>
                <!-- /.input group -->
              </div>






                <div class="form-group">
                <label>Expiry Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="end" class="form-control pull-right" required="" id="datepickers">
                </div>
                <!-- /.input group -->
              </div>




<!--                      <div class="form-group has-feedback">
                      <div class="row">
                        <div class="col-xs-8">
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right trans_date" id="trans_date">
                          </div>
                          </div> 
                          <div class="col-xs-4"><button class="btn btn-success" onclick="filer_result()"> Apply</button>&nbsp;<button class="btn btn-info" onclick="reset_result()"> Reset</button></div>                       
                      </div>
                    </div>
 -->
                     




                  </div>




                </div>


            <div class="box-footer text-center">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
                </form> 


                


              </div><!-- /.box -->
            </div>
            
          </div>   <!-- /.row -->
        </section><!-- /.content -->
      </div>




<script type="text/javascript">
  $(".mode").hide('slow');
  var i = 0;
  $('input[type=radio]').on('click',function(){
    var div = $(this).val();
    $(".mode").hide('slow');
    $("#"+div).show('slow');
    $('input[type=number]').removeClass("required");
    $('input[type=text]').removeClass("required");
    if(div=='csv'){
       $('input[type=text]').addClass("required");
    } else if(div=='range'){
      $("#start_at").addClass("required");
      $("#end_at").addClass("required");
    } else {
      $(".unique_no").addClass("required");
    }
  })



    $('#datepicker').datepicker({
      autoclose: true
    })

    $('#datepickers').datepicker({
      autoclose: true
    })

</script>



