
<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Products Metatags
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a>
      </li>
      <li><a href="#">Manage Metatags</a>
      </li>
      <li class="active">Add Tags</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <?php if($this->session->flashdata('message')) { $message = $this->session->flashdata('message'); ?>
        <div class="alert alert-<?php echo $message['class']; ?>">
          <button class="close" data-dismiss="alert" type="button">×</button>
          <?php echo $message[ 'message']; ?>
        </div>
        <?php } ?>
      </div>
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
       <div class="box box-info box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">Add Metatags  </h3>
            <div class="pull-right box-tools">
              <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse"> <i class="fa fa-minus"></i>
              </button>
            </div>
          </div>

          <form role="form" action="" method="post" data-parsley-validate="" class="validate" enctype="multipart/form-data">

            <div class="box-body">
              <div class="col-md-6">

                <div class="form-group">
                  <label>Product</label>
                  <input type="text" class="form-control" value="<?php echo $product_data->name; ?>" disabled>
                </div>

                <div class="form-group">
                  <label>Product Sku</label>
                  <input type="text" class="form-control" value="<?php echo $product_data->sku; ?>" disabled>
                </div>

                <div class="form-group has-feedback">
                  <label for="exampleInputEmail1">Page Title</label>
                  <input type="text" class="form-control " name="page_title" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="110"   required="" placeholder="Enter Page Title"> <span class="glyphicon  form-control-feedback"></span>
                </div>

                <div class="form-group">
                  <label>Description  </label>
                  <textarea name="description" class="form-control" rows="3" placeholder="Enter product description..."></textarea>
                </div>

                <div class="form-group">
                  <label>Keywords  </label>
                  <textarea name="keywords" class="form-control" rows="3" placeholder="Enter product keywords..."></textarea>
                </div>


                <div class="box-footer text">
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