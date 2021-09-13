<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          Assign Customers to <?php echo urldecode($couponname); ?>
      </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-male"></i>Home</a></li>
            <li class="active"> Add Users</li>
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

            <!--  form -->

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">Coupon: <?php echo urldecode($couponname); ?></h3>
                        <div class="pull-right box-tools">
                            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <form role="form" action="" method="post" data-parsley-validate="" class="validate" enctype="multipart/form-data">
                        <div class="box-body">

                            <div class="col-md-12">

                                <div class="row">
                                    <div class="form-group col-md-11">
                                        <label>Allow <?php echo urldecode($couponname); ?> to: </label>
                                        <br/>

                                        <div class="col-md-3">
                                            <label>
                                                <input type="radio" name="mode" value="specificuser" class="required">&nbsp;Specific Customers</label>
                                        </div>

                                        <div class="col-md-3">
                                            <label>
                                                <input type="radio" name="mode" value="domainuser" class="required">&nbsp;Specific Domain Customers</label>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                            


                                <div class="row mode" id="specificuser">
                                    <div class="col-md-6" style="width:535px;">
                                        <label class="exampleInputEmail1">Customer</label>
                                        <select name="customer_id" class="form-control select2 ">
                                            <option value="" selected="selected">Select Email </option>
                                            <?php
                                      foreach ($customer as $rs) {?>
                                                <option value="<?php echo $rs->id; ?>">
                                                    <?php echo $rs->email; ?>
                                                </option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                </div>



                               <div class="row mode" id="domainuser">
                                 <div class="col-md-6" style="width:535px;">
                                    <label for="exampleInputEmail1">Domain Name</label>
                                    <input type="text" class="form-control " name="domainuser" placeholder="Enter Domain Name" >
                                 <span class="glyphicon  form-control-feedback"></span>
                                  </div>
                              </div>





                              

                            </div>
                        </div>

                        <div class="box-footer text">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>

                </div>
                <!-- /.box -->
            </div>
            <!-- form -->

            <div class="col-xs-6">
                <!-- /.box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                       <h3 class="box-title"><?php echo urldecode($couponname); ?></h3> <h4>Specific Users List</h4>
                        <div class="pull-right box-tools">
                            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th class="hidden">ID</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <!--  <th>Phone</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                           foreach($data as $coupon) {
                          // print_r($all);

                           ?>
                                    <tr>
                                        <td class="hidden">
                                            <?php echo $coupon->id; ?>
                                        </td>
                                        <td class="center">
                                            <?php echo get_customer_name($coupon->user_identifier); ?>
                                        </td>
                                        <td class="center">
                                            <?php echo get_customer_email($coupon->user_identifier); ?>
                                        </td>
                                        <!-- <td class="center"><?php echo get_customer_phone($coupon->user_identifier); ?></td> -->

                                        <td class="center">

                                            <a class="btn btn-xs btn-danger" href="<?php echo base_url();?>coupons/remove_couponuser/<?php echo urlencode($coupon->coupon); ?>/<?php echo $coupon->id; ?>" onClick="return doconfirm_coupon()"><i class="fa fa-fw fa-close"></i>Remove User</a>

                                        </td>
                                    </tr>
                                    <?php
                           }
                           ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col ends-->

            <div class="col-xs-6">
                <!-- /.box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo urldecode($couponname); ?></h3> <h4>Domain Users List</h4>
                        <div class="pull-right box-tools">
                            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th class="hidden">ID</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                   
                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                           foreach($domaindata as $domain) {
                          // print_r($all);

                           ?>
                                    <tr>
                                        <td class="hidden">
                                            <?php echo $domain->id; ?>
                                        </td>
                                        <td class="center">
                                           <?php echo $domain->name; ?>
                                        </td>
                                        <td class="center">
                                           <?php echo $domain->email; ?>
                                        </td>
                                        <!-- <td class="center"><?php echo get_customer_phone($coupon->user_identifier); ?></td> -->

                                       
                                    </tr>
                                    <?php
                           }
                           ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade modal-wide" id="popup-patientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">View Cars Details</h4>
            </div>
            <div class="modal-patientbody">
            </div>
            <div class="business_info">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

  })

</script>