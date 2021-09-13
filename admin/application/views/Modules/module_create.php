<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
         <h1>
        <?php echo $page_data->function_title; ?>
         <small><?php echo $page_data->function_small; ?></small>
      </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Module</a></li>
            <li class="active">Create Module</li>
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
               <div class="box box-primary">
              <div class="box-header with-border">
                
                <h3 class="box-title"><?php echo $page_data->function_head; ?></h3>
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
                                <label for="exampleInputEmail1">Module Name</label>
                                <input type="text" class="form-control " name="module_name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder=" Enter Module Name" value="">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>


                          <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Module Control</label>
                                <input type="text" class="form-control " name="module_control" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Module Control" value="">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                   


                            <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Module Menu</label>
                                <input type="text" class="form-control " name="module_menu" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Module Menu" value="">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                        </div>
                        <div class="col-md-6">
                           <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Module Object</label>
                                    <select name="module_menu" class="form-control required">
                                        <option value="" selected="selected">Select Module Object</option>
                                           <?php
                                          
                                              foreach ($menu as $rs) {?>
                                      <option value="<?php echo $rs->id; ?>"><?php echo $rs->type; ?></option>
                                            <?php } ?>
                                    </select>
                            </div>


               


                            <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Module Class</label>
                                <input type="text" class="form-control " name="module_class" data-parsley-trigger="change" data-parsley-minlength="3"   required="" placeholder=" Enter Module Class" value="">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                       


                            <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Module Priority</label>
                                <input type="nuumber" class="form-control " name="module_priority" data-parsley-trigger="change" data-parsley-minlength="1"   required="" placeholder=" Enter Module Priority" value="">
                             <span class="glyphicon  form-control-feedback"></span>
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