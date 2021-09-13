<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         System Settings
      </h1>
      <ol class="breadcrumb">
         <li>
            <a href="#">
            <i class="fa fa-dashboard">
            </i> Home
            </a>
         </li>
         <li>
            <a href="#">Settings
            </a>
         </li>
         <li class="active">View & Edit
         </li>
      </ol>
   </section>
   <!-- Main content -->
   <section class="content">
                   <?php
                  if($this->session->flashdata('message')) {
                    $message = $this->session->flashdata('message');
                    ?>
                    <div class="alert alert-<?php echo $message['class']; ?>">
                      <button class="close" data-dismiss="alert" type="button">×
                      </button>
                      <?php echo $message['message']; ?>
                    </div>
                    <?php
                  }
                  ?>
                  
      <div class="row">
         <div class="col-md-12">
            <div class="nav-tabs-custom">
              <div class="box box-primary">
                  <ul class="nav nav-tabs">
                     <li class="active">
                        <a href="#view_settings" data-toggle="tab"><i class='fa fa-fw fa-wrench'></i>
                        <b>SETTINGS OVERVIEW
                        </b>
                        </a>
                     </li>
                     <li>
                        <a href="#topup" data-toggle="tab"><i class='fa fa-fw fa-edit'></i>
                        <b>EDIT
                        </b>
                        </a>
                     </li>
                  </ul>
               </div>
               <div class="tab-content">
                  <div class="tab-pane" id="topup">
                     <div class="row">
                        <div class="col-xs-12">
                        </div>
                        <!-- left column -->
                        <div class="col-md-12">
                           <!-- general form elements -->
                           <div class="">
                              <form role="form" action="" method="post"  data-parsley-validate="" class="validate"  enctype="multipart/form-data">
                                 <div class="box-body">

                                 <div class="col-md-6">
                                     <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1">Delivery Charge
                                       </label>
                                          <input type="text" class="form-control " name="delivery_charge" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Delivery Charge " value="<?php echo $result->delivery_charge; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>
                                


                                    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1">VAT(%)
                                       </label>
                                          <input type="text" class="form-control " name="vat" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter VAT(%) " value="<?php echo $result->vat; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>


                                    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1" >Minimum Order for Checkout
                                       </label>
                                          <input type="text" class="form-control " name="minimum_order" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Minimum Order for Checkout " value="<?php echo $result->minimum_order; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>

                                 <!--    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1" >Minimum Order for Checkout(Meat Monday)
                                       </label>
                                          <input type="text" class="form-control " name="mm_minimum_order" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Minimum Order for Checkout(Meat Monday) " value="<?php echo $result->mm_minimum_order; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div> -->

                                    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1" >Minimum Order for Free Deivery
                                       </label>
                                          <input type="text" class="form-control " name="freedelivery_minimum_order" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Minimum Order for Free Deivery " value="<?php echo $result->freedelivery_minimum_order; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>

                                    

                                 </div>

                                 <div class="col-md-6">

                                  <!--   <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1" >Minimum Order for Free Deivery(Meat Monday)
                                       </label>
                                          <input type="text" class="form-control " name="mm_freedelivery_minimum_order" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Minimum Order for Free Deivery(Meat Monday) " value="<?php echo $result->mm_freedelivery_minimum_order; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>
                                     -->
                                    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1">Max Deliveries In a time Slot
                                       </label>
                                          <input type="text" class="form-control " name="max_delivery_time_deliveries" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Max Deliveries In a time Slot " value="<?php echo $result->max_delivery_time_deliveries; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>

                                    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1">Card Orders Commission
                                       </label>
                                          <input type="text" class="form-control " name="card_commission" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Card Orders Commission" value="<?php echo $result->card_commission; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>

                                    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1">Cash Orders Commission
                                       </label>
                                          <input type="text" class="form-control " name="cash_commission" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter Cash Orders Commission " value="<?php echo $result->cash_commission; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>


                                   <!--  <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1">Android Version
                                       </label>
                                          <input type="text" class="form-control " name="android_version" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter android version " value="<?php echo $result->android_version; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div>

                                    <div class="form-group has-feedback">
                                       <label for="exampleInputEmail1">Ios Version
                                       </label>
                                          <input type="text" class="form-control " name="ios_version" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-maxlength="50"   required="" placeholder="Please Enter ios version " value="<?php echo $result->ios_version; ?>">
                                          <span class="glyphicon  form-control-feedback">
                                          </span>
                                    </div> -->


                                 </div>


                                 </div>
                                 <!-- /.box-body -->
                                 <div class="box-footer">
                                    <button type="submit" class="btn btn-success">Submit
                                    </button>
                                 </div>
                                 <!-- /.box-footer -->
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="active tab-pane" id="view_settings">
                     <div class="box-body">
                        <div class="col-md-6">
                          <h3 style="color:blue;" class="profile-username text-center">Checkout Info</h3>
                           <div class="box-body box-profile">
                              <ul class="list-group list-group-unbordered">

                                  <li class="list-group-item">
                                    <b>Cash Order Commmission to Valucart
                                    </b> 
                                    <a class="pull-right">
                                    <span class="label label-primary pull-right">
                                       <?php echo $data->cash_commission; ?>%
                                    </span>
                                    </a>
                                 </li>
                                 <li class="list-group-item">
                                    <b>Card Order Commmission to Valucart
                                    </b> 
                                    <a class="pull-right">
                                    <span class="label label-primary pull-right">
                                       <?php echo $data->card_commission; ?>%
                                    </span>
                                    </a>
                                 </li>


                                 <li class="list-group-item">
                                    <b>Minimum Order for Checkout
                                    </b> 
                                    <a class="pull-right">
                                    <span class="label label-primary pull-right">
                                    <?php echo $data->minimum_order; ?>
                                    </span>
                                    </a>
                                 </li>
                                 <!-- <li class="list-group-item">
                                    <b>Minimum Order for Checkout(Meat Monday)
                                    </b> 
                                    <a class="pull-right"> 
                                    <span class="label label-success pull-right">
                                      <?php echo $data->mm_minimum_order; ?>
                                    </span>
                                    </a>
                                 </li> -->
                                 <li class="list-group-item">
                                    <b>Minimum Order for Free Deivery
                                    </b> 
                                    <a class="pull-right">
                                   <!--  <span class="label label-warning pull-right"> -->
                                     <b> <?php echo $data->freedelivery_minimum_order; ?></b>
                                    <!-- </span> -->
                                    </a>
                                 </li>
                                 <!-- <li class="list-group-item">
                                    <b>Minimum Order for Free Deivery(Meat Monday)
                                    </b> 
                                    <a class="pull-right">
                                    <span class="label label-info pull-right">
                                      <?php echo $data->mm_freedelivery_minimum_order; ?>
                                    </span>
                                    </a>
                                 </li> -->
                                 <li class="list-group-item">
                                    <b>Delivery Charge
                                    </b> 
                                    <a class="pull-right">
                                    <span class="label label-danger pull-right">
                                    <?php echo $data->delivery_charge; ?>
                                    </span>
                                    </a>
                                 </li>
                                 <li class="list-group-item">
                                    <b>VAT(%)
                                    </b> 
                                    <a class="pull-right">
                                    <span class="label label-success pull-right">
                                       <?php echo $data->vat; ?>
                                    </span>
                                    </a>
                                 </li>
                                 <li class="list-group-item">
                                    <b>Max Deliveries In a time Slot
                                    </b> 
                                    <a class="pull-right">
                                    <span class="label label-primary pull-right">
                                       <?php echo $data->max_delivery_time_deliveries; ?>
                                    </span>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <h3 style="color:blue;" class="profile-username text-center">Native Application Versions</h3>
                           <div class="box-body box-profile">
                              <ul class="list-group list-group-unbordered">
                                 <li class="list-group-item">
                                    <b>Android App Version(Current)
                                    </b> 
                                    <a class="pull-right">
                                     <b> <?php echo $data->android_version; ?></b>
                                    </a>
                                 </li>
                                 <li class="list-group-item">
                                    <b>Ios App Version(Current)
                                    </b> 
                                    <a class="pull-right"> 
                                     <b> <?php echo $data->ios_version; ?></b>
                                    </a>
                                 </li>                                 
                              </ul>
                           </div>

                           <h3 style="color:blue;" class="profile-username text-center">Website Versions</h3>
                           <div class="box-body box-profile">
                              <ul class="list-group list-group-unbordered">
                                 <li class="list-group-item">
                                    <b>Web Version(Current)
                                    </b> 
                                    <a class="pull-right">
                                    <b>  2.0</b>
                                    </a>
                                 </li>
                              </ul>
                           </div>

                        </div>
                     </div>
                  </div>
                  <!-- /.tab-pane -->
               </div>
               <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>