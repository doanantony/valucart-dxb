<div class="content-wrapper">
  <section class="content-header">
    <h1>Bank Offers
      <small>Create Bank Offers</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Manage Bank Offers</a></li>
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
            <h3 class="box-title">Bank Offers</h3>
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
                  <label for="exampleInputEmail1">Promocode</label>
                  <input type="text" class="form-control " name="code" data-parsley-trigger="change" required="" placeholder="Enter Promocode" value="">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                  <label for="exampleInputEmail1">Color Code</label>
                  <input type="text" class="form-control my-colorpicker1 " name="color_code" data-parsley-trigger="change" required="" placeholder="Enter Color Code" value="">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                  <label for="exampleInputEmail1">Title</label>
                  <input type="text" class="form-control " name="title" data-parsley-trigger="change" required="" placeholder="Enter Title" value="">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>

                <div class="form-group">
                  <label class="control-label" for="image">Bank Icon</label>
                    <input type="file" multiple name="image" id="image" class="form-control required regcom" class size="20" />
                  <div id="image_req" style="color: red"></div>
                </div>  


              </div>

              <div class="col-md-6">
                <!-- <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control required" rows="5" placeholder="Enter bundle description..." name="description"></textarea>
                </div> -->
                <div class="form-group has-feedback">
               <label for="exampleInputEmail1">Description</label>
                    <textarea id="compose-textarea" class="form-control" name="description" data-parsley-trigger="change" required="" style="height: 100px"></textarea>
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

<script>
    $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });
</script>