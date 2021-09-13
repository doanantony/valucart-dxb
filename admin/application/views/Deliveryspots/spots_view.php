<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Delivery Spots
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-male"></i>Home</a></li>
         <li class="active"> Delivery Spots</li>
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
                  <h3 class="box-title">Create a delivery spot</h3>
                  <div class="pull-right box-tools">
                     <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                     <i class="fa fa-minus"></i>
                     </button>
                  </div>
               </div>
               <!-- form start -->
               <form role="form" action="" method="post"  data-parsley-validate="" class="validate"  enctype="multipart/form-data">
                  <div class="box-body">
                     <div class="col-md-6">
                        <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">Location Name</label>
                           <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3"  required="" placeholder="Enter Location Name" value="">
                           <span class="glyphicon  form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">Range(Enter the range in Kms for delivery in this spot)</label>
                           <input type="text" class="form-control " name="range" data-parsley-trigger="change" data-parsley-minlength="1"  required="" placeholder="Enter Range in kms" value="">
                           <span class="glyphicon  form-control-feedback"></span>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group has-feedback">
                           <label class="control-label" for="name">Location</label>
                           <input type="text" name="location" id="location" class="form-control required" placeholder="Please Enter Location">
                        </div>
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                        <input type="hidden" name="currency" id="currency">
                        <div class="form-group has-feedback">
                            <label>Publish Status</label>
                            <select class="form-control select2 required" name="status">
                              <option value="1">Publish</option>
                              <option value="0">Unpublish</option>
                            </select>
                          </div>
                     </div>
                  </div>
                  <div class="box-footer text-center">
                     <button type="submit" class="btn btn-success">Submit</button>
                  </div>
               </form>
            </div>
            <!-- /.box -->
         </div>
         <!-- form -->
         <div class="col-xs-12">
            <!-- /.box -->
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">List of Delivery Spots</h3>
                  <div class="pull-right box-tools">
                     <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                     <i class="fa fa-minus"></i>
                     </button>
                  </div>
               </div>
               <div class="box-body">
               </div>
               <div class="box-body">
                  <table class="table table-bordered table-striped datatable">
                     <thead>
                        <tr>
                           <th class="hidden">ID</th>
                           <th>Vendor Location</th>
                           <th>Precise Location</th>
                           <th>Range</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           foreach($data as $spots) {
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $spots->id; ?></td>
                           <td class="center"><?php echo $spots->short_name; ?></td>
                           <td class="center"><?php echo $spots->name; ?></td>
                           <td class="center"><?php echo $spots->range; ?>KM</td>
                           <td><span class="center label  <?php if($spots->published == '1')
                              {
                              echo "label-success";
                              }else
                              { 
                              echo "label-warning"; 
                              }
                              ?>"><?php if($spots->published == '0')
                              {
                              echo "Closed for delivery";
                              }elseif($spots->published == '1'){
                                echo "Open for delivery";
                              }else
                              { 
                              echo "Deleted"; 
                              }
                              ?></span>   
                           </td>
                           <td class="center">
                              <?php if( $spots->published){?>
                              <a class="btn btn-xs label-warning" href="<?php echo base_url();?>Deliveryspots/unpublish/<?php echo $spots->id; ?>"> <i class="fa fa-folder-open"></i> Unpublish </a>           
                              <?php
                                 }
                                 else
                                 {
                                 ?>
                              <a class="btn btn-xs label-success" href="<?php echo base_url();?>Deliveryspots/publish/<?php echo $spots->id; ?>"> <i class="fa fa-folder-o"></i> Publish </a>
                              <?php
                                 }
                                 ?>
                           </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th class="hidden">ID</th>
                           <th>Vendor Location</th>
                           <th>Precise Location</th>
                           <th>Range</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </tfoot>
                  </table>
               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>