<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Categories</a></li>
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
                                <label for="exampleInputEmail1">Category Name</label>
                                <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3"   required="" placeholder="Enter Category Name" value="<?php echo $result->name; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                          <!-- <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Department</label>
                                    <select name="department_id" class="form-control select2 required">
                                        <option value="" selected="selected">Select Department </option>
                                           <?php
                                              foreach ($department as $rs) {?>
                                      <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                            <?php } ?>
                                    </select>
                            </div> -->


                        <div class="form-group">
                          <label>Description</label>
                          <textarea class="form-control" rows="5" placeholder="Enter category description..." name="description"></textarea>
                        </div>





                   <div class="box-footer text">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
                </form> 


                


              </div><!-- /.box -->
            </div>
            
          </div>   <!-- /.row -->
        </section><!-- /.content -->
      </div>