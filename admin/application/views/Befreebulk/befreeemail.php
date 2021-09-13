<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
          Bulk Email Template
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i>Home</a></li>
         <li><a href="#">Bulk Email</a></li>
         <li class="active">Send</li>
      </ol>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <!-- left column -->
         <div class="col-md-12">
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
            <!-- general form elements -->

        <div class="box box-success ">
               <div class="box-header with-border">
              <h3 class="box-title">Send Email</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
               <!-- /.box-header -->
               <!-- form start -->
                          <form role="form" action="" method="post"  data-parsley-validate="" class="validate"  enctype="multipart/form-data">
                 <div class="box-body">
                  <div class="col-md-6">

                      <!-- <div class="form-group has-feedback">
                          <label></label>
                      </div> -->
                            

                      <div class="form-group has-feedback">
                          <label>Send  To</label>
                          <select class="form-control select2 required" name="recipients" id="recipient" onchange="ShowHideDiv()">
                            <option value="N">All Customers</option>
                            <option value="Y">Specific Customers</option>
                          </select>
                      </div>


                      <div class="form-group has-feedback" id="recipientcustomers" style="display: none">
                         <label class="exampleInputEmail1">Customers</label>
                            <select  name="customers[]" class="form-control select2" multiple="multiple"
                                style="width: 100%;">
                            <?php
                                      foreach ($customers as $rs) {?>
                            <option value="<?php echo $rs->id; ?>"><?php echo $rs->email; ?></option>
                              <?php } ?>
                             </select>
                        </div> 




                  <div class="form-group has-feedback">
                   <label for="exampleInputEmail1">Title</label>
                    <p class="emoji-picker-container">
                    <textarea data-emojiable="true" data-emoji-input="unicode" id="textarea" class="form-control" name="title" data-parsley-trigger="change" required="" style="height: 100px"></textarea>
                  </p>
                   </div> 


                          <div class="form-group">
                                  <label class="control-label" for="image">Zip File</label>
                                  <input type="file" accept=" .zip" multiple name="file" id="file" class="form-control  regcom" class size="20" />
                                <div id="zip_file_req" style="color: red"></div>
                          </div> 

                   


                  


                   <div class="box-footer text">
              <button type="submit" class="btn btn-success">Send</button>
            </div>
                </form> 
            </div>
            <!-- /.box -->
         </div>
      </div>
     </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>

<script>

   function ShowHideDiv() {
            var recipient = document.getElementById("recipient");
            var recipientcustomers = document.getElementById("recipientcustomers");
            recipientcustomers.style.display = recipient.value == "Y" ? "block" : "none";
        }
  
</script>