<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1><?php echo $page_data->function_title; ?>
         <small><?php echo $page_data->function_small; ?></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="#">Manage Vendors</a></li>
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
                     <button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                     <i class="fa fa-minus"></i>
                     </button>
                  </div>
               </div>
               <!-- /.box-header -->
               <!-- form start -->
               <form role="form" action="" method="post"  data-parsley-validate="" class="validate"  enctype="multipart/form-data">
                  <div class="box-body">
                     <div class="col-md-6">
                        <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">Vendor Name</label>
                           <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder="Enter Vendor Name" value="">
                           <span class="glyphicon  form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                           <label class="control-label" for="name">Location</label>
                           <input type="text" name="location" id="location" class="form-control required" placeholder="Please Enter Location">
                        </div>
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                        <input type="hidden" name="currency" id="currency">
                        <div class="form-group has-feedback">
                           <label class="exampleInputEmail1">Category</label>
                           <select name="category_id" class="form-control select2 required">
                              <option value="" selected="selected">Select Category </option>
                              <?php
                                 foreach ($category as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                              <?php } ?>
                           </select>
                        </div>
                        <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">Email</label>
                           <input type="text" class="form-control " name="email" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="52"   required="" placeholder="Enter Email" value="" onblur="">
                           <span class="glyphicon  form-control-feedback"></span>
                           <div style="color:red" id="email_error"></div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">User Name</label>
                           <input type="text" class="form-control " name="username" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="50"   required="" placeholder="Enter User Name" value="<?php echo $user_data->username; ?>" onblur="user_availability(this.value,<?php echo $id; ?>)">
                           <span class="glyphicon  form-control-feedback"></span>
                           <div style="color:red" id="username_error"></div>
                        </div>
                        <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">Password</label>
                           <input type="password" class="form-control required" name="password" placeholder="Password" data-parsley-trigger="change"  data-parsley-minlength="4"   data-parsley-maxlength="10" required="" value="<?php echo $user_data->passwd; ?>">
                           <span class="glyphicon  form-control-feedback"></span>
                        </div>
                        <div class="form-group">
                           <label class="control-label" for="image"> Icon</label>
                           <input type="file" multiple name="image" id="image" class="form-control required regcom" class size="20" />
                           <div id="image_req" style="color: red"></div>
                        </div>
                        <div class="form-group has-feedback">
                           <label>Publish</label>
                           <select class="form-control select2 required" name="status">
                              <option value="1">Publish</option>
                              <option value="0">UnPublish</option>
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
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>