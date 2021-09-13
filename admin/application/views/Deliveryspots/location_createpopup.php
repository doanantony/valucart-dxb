<section class="content">
   <div class="box box-info box-solid">
      <!-- /.box-header -->
      <div class="box-body no-padding">
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
                      
                       
                     </div>
                     <div class="col-md-6">
                        
                         <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">Vendor Name</label>
                           <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder="Enter Vendor Name" value="">
                           <span class="glyphicon  form-control-feedback"></span>
                        </div>

                        <div class="form-group has-feedback">
                           <label for="exampleInputEmail1">Vendor Name</label>
                           <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder="Enter Vendor Name" value="">
                           <span class="glyphicon  form-control-feedback"></span>
                        </div>
                     </div>
                  </div>
                  <div class="box-footer text-center">
                     <button type="submit" class="btn btn-success">Submit</button>
                  </div>
               </form>
      </div>
      <!-- /.box-body -->
   </div>
</section>