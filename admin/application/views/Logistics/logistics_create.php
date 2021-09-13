<div class="content-wrapper">

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><?php echo $page_data->function_title; ?>
          <small><?php echo $page_data->function_small; ?></small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Logistics Team</a></li>
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
       
                         <?php
                              $check_id = $user_data->id;
                                if($check_id == ''){
                                    $id = 0;
                                      }else{
                                    $id = $check_id;
                            }
                          ?>
                          

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
               <!--  <div class="col-md-6">
-->
                          <!-- <div class="form-group">
                                  <label class="intrate">First Name</label>
                                  <input class="form-control required regcom" type="text" name="first_name" placeholder="Enter First Name"  required="" id="first_name" value="<?php echo $result->first_name; ?>">
                          </div>  -->
                          <div class="col-md-6">
                          <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">First Name</label>
                                <input type="text" class="form-control" name="first_name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder="Enter First Name" value="<?php echo $result->first_name; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                          <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Last Name</label>
                                <input type="text" class="form-control " name="last_name" data-parsley-trigger="change" data-parsley-minlength="1" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder="Enter Last Name" value="<?php echo $result->last_name; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>
                           <div class="form-group has-feedback">
                            <label>Status</label>
                            <select class="form-control select2 required" name="status">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                          </div>
                         <!--  <div class="form-group has-feedback">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control required" data-parsley-trigger="change"  data-parsley-minlength="5"   required="" name="email"  placeholder="Enter Email" value="<?php echo $result->email; ?>">
                          <span class="glyphicon  form-control-feedback"></span>
                        </div> -->

                         <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Email</label>
                              <input type="text" class="form-control " name="email" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="52"   required="" placeholder="Enter Email" value="<?php echo $result->email; ?>" onblur="email_availability($(this),<?php echo $id; ?>)">
                            <span class="glyphicon  form-control-feedback"></span>
                            <div style="color:red" id="email_error"></div>
                          </div>                        
                        </div>
                        <div class="col-md-6">
                          

                           <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Phone</label>
                              <input type="text" class="form-control required" data-parsley-trigger="keyup" data-parsley-type="digits" data-parsley-minlength="10" data-parsley-maxlength="15" data-parsley-pattern="^[0-9]+$" required="" name="phone_no"  placeholder="Enter Phone" value="<?php echo $result->phone_no; ?>" onblur="phone_availability($(this),<?php echo $id; ?>)">
                            <span class="glyphicon  form-control-feedback"></span>
                            <div style="color:red" id="phone_error"></div>
                          </div>

                          
                          <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">User Name</label>
                              <input type="text" class="form-control " name="username" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="50"   required="" placeholder="Enter User Name" value="<?php echo $user_data->username; ?>" onblur="user_availability(this.value,<?php echo $id; ?>)">
                            <span class="glyphicon  form-control-feedback"></span>
                            <div style="color:red" id="username_error"></div>

                          </div>

                          <?php if($sub=='create'){?>

                          <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Password</label>
                              <input type="password" class="form-control required" name="password" placeholder="Password" data-parsley-trigger="change"  data-parsley-minlength="4"   data-parsley-maxlength="10" required="" value="<?php echo $user_data->passwd; ?>">
                            <span class="glyphicon  form-control-feedback"></span>
                          </div>
                          <?php } ?>

                            <?php if($sub=='edit'){?>
                           <div class="form-group has-feedback">
                            <label>Status</label>
                            <select class="form-control select2 required" name="status">
                              <option value="1" <?php if($result->status==1){?> selected="selected" <?php } ?>>Enable</option>
                              <option value="0" <?php if($result->status==0){?> selected="selected" <?php } ?>>Disable</option>
                            </select>
                          </div>
                          <?php } ?>



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