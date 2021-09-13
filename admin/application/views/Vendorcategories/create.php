<div class="content-wrapper">
  <section class="content-header">
    <h1>Vendor Categories
      <small>Create Vendor Category</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Manage Vendor Categories</a></li>
      <li class="active">Create</li>
    </ol>
  </section>
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
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Vendor Categories</h3>
            <div class="pull-right box-tools">
              <button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                <i class="fa fa-minus"></i>
              </button>
            </div>
          </div>

          <form role="form" action="" method="post" data-parsley-validate="" class="validate" enctype="multipart/form-data">
            <div class="box-body">
              <div class="col-md-6">

                <div class="form-group has-feedback">
                  <label for="exampleInputEmail1">Category Name</label>
                  <input type="text" class="form-control " name="name" data-parsley-trigger="change" required="" placeholder="Enter Vendor Category" value="">
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
