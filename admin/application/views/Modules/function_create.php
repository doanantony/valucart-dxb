<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
        <?php echo $page_data->function_title; ?>
         <small><?php echo $page_data->function_small; ?></small>
      </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Function</a></li>
            <li class="active">Create Function</li>
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
                <form role="form" action="" method="post"  data-parsley-validate="" class="validate"  enctype="multipart/form-data">
                 <div class="box-body">


                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Module Name</label>
                                    <select name="module_id" class="form-control select2 required">
                                        <option value="" selected="selected">Select Module </option>
                                           <?php
                                              foreach ($module as $rs) {?>
                                      <option value="<?php echo $rs->id; ?>"><?php echo $rs->module_name; ?></option>
                                            <?php } ?>
                                    </select>
                            </div>

                            <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Function Name</label>
                                    <input type="text" class="form-control " name="function_name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Function Name" value="">
                            </div> 

                            <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Path</label>
                                    <input type="text" class="form-control " name="function_path" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Function Path" value="">
                            </div>

                            <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Menu name</label>
                                    <input type="text" class="form-control " name="function_menu" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Function Menu" value="">

                            </div> 
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Page Title</label>
                                     <input type="text" class="form-control " name="function_title" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Function Title" value="">
                            </div> 

                            <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Page Small Text</label>
                                    <input type="text" class="form-control " name="function_small" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Function Title" value="">
                            </div> 

                             <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Page Caption</label>
                                    <input type="text" class="form-control " name="function_head" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z_\  \/]+$"  required="" placeholder=" Enter Function Title" value="">
                            </div> 

                            <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Function Class</label>
                                    
                                           <input type="text" class="form-control " name="function_class" data-parsley-trigger="change" data-parsley-minlength="3"   required="" placeholder=" Enter Function Title" value="">
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