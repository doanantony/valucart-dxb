<div class="content-wrapper">

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><?php echo $page_data->function_title; ?>
          <small><?php echo $page_data->function_small; ?></small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Portalusers</a></li>
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
                                <label for="exampleInputEmail1">Display Name</label>
                                <input type="text" class="form-control " name="display_name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder=" Enter Display Name" value="<?php echo $result->display_name; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                        <div class="form-group has-feedback">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control required" data-parsley-trigger="change"  data-parsley-minlength="5"   required="" name="email_id"  placeholder="Enetr Email" value="<?php echo $result->email_id; ?>">
                          <span class="glyphicon  form-control-feedback"></span>
                        </div>

                        </div>
                        <div class="col-md-6">
                          

                          <?php
                              $check_id = $user_data->id;
                                if($check_id == ''){
                                    $id = 0;
                                      }else{
                                    $id = $check_id;
                            }
                          ?>
                          
                          <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">User Name</label>
                              <input type="text" class="form-control " name="username" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="15"   required="" placeholder="Enter User Name" value="<?php echo $user_data->username; ?>" onblur="user_availability(this.value,<?php echo $id; ?>)">
                            <span class="glyphicon  form-control-feedback"></span>
                            <div style="color:red" id="username_error"></div>

                          </div>

                          

                          <?php if($sub=='menu_portal_create'){?>

                          <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Password</label>
                              <input type="password" class="form-control required" name="password" placeholder="Password" data-parsley-trigger="change"  data-parsley-minlength="4" data-parsley-maxlength="10"  required="" value="<?php echo $user_data->password; ?>">
                            <span class="glyphicon  form-control-feedback"></span>
                          </div>
                          <?php } ?>

                           

                          <?php if($sub=='create'){?>
                          <div class="form-group">
                                  <label class="control-label" for="image">Profile Picture</label>
                                  <input type="file" multiple name="image" id="image" class="form-control required regcom" class size="20" />
                                <div id="image_req" style="color: red"></div>
                          </div>
                          <?php }else{ ?>
                            <div class="form-group">
                                  <label class="control-label" for="image">Profile Picture</label>
                                  <input type="file" multiple name="image" id="image" class="form-control " class size="20" />
                                <div id="image_req" style="color: red"></div>
                          </div>
                           <?php } ?>  

                           <div class="form-group has-feedback">
                            <label>Status</label>
                            <select class="form-control select2 required" name="status">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                          </div>

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