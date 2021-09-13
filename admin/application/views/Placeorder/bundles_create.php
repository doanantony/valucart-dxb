<div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><?php echo $page_data->function_title; ?>
          <small><?php echo $page_data->function_small; ?></small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Bundles</a></li>
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
                              /*$check_id = $user_data->id;
                                if($check_id == ''){
                                    $id = 0;
                                      }else{
                                    $id = $check_id;
                            }*/
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
             
                          <div class="col-md-6">
                        

                        
                              <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Bundle Name</label>
                                <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder="Enter Bundle Name" value="<?php echo $result->name; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                          <div class="form-group has-feedback">
                                    <label class="exampleInputEmail1">Category</label>
                                    <select name="category_id" class="form-control select2 required">
                                        <option value="" selected="selected">Select Category </option>
                                           <?php
                                              foreach ($categories as $rs) {?>
                                      <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                            <?php } ?>
                                    </select>
                            </div>  
                            <div class="form-group">
                                  <label class="control-label" for="image">Bundle Image</label>
                                  <input type="file" multiple name="image" id="image" class="form-control required regcom" class size="20" />
                                <div id="image_req" style="color: red"></div>
                          </div>                        



                        </div>
                        <div class="col-md-6">
                          

                         
                          


                          <div class="form-group">
                          <label>Description</label>
                          <textarea class="form-control" rows="5" placeholder="Enter bundle description..." name="description"></textarea>
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