  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    

    <section class="content-header">
      <h1>
          Mailbox
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i>Home</a></li>
         <li><a href="#"> Bulk Emails</a></li>
         <li class="active">Send</li>
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

        <div class="col-md-3">
         <!--  <a href="mailbox.html" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a> -->

         <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="mailbox.html"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">0</span></a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> Sent<span class="label label-info pull-right">0</span></a></li>
                <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts<span class="label label-warning pull-right">0</span></a></li>
                <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-danger pull-right">0</span></a>
                </li>
                <li><a href="#"><i class="fa fa-trash-o"></i> Trash<span class="label label-primary pull-right">0</span></a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
         <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9" style="color: #6610f2">
          <div class="box box-info box-solid" >
           <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->

            <form role="form" action="" method="post"  data-parsley-validate="" class="validate"  enctype="multipart/form-data">



            <div class="box-body">
                  
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
                            <option value="<?php echo $rs->email; ?>"><?php echo $rs->email; ?></option>
                              <?php } ?>
                             </select>
                        </div> 


                        <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Subject</label>
                                <input type="text" class="form-control " name="subject" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-pattern="^[a-zA-Z\  \/]+$"  required="" placeholder="Please Enter Email Subject" value="">
                             <span class="glyphicon  form-control-feedback"></span>
                  </div>

                <div class="form-group has-feedback">
               <label for="exampleInputEmail1">Message</label>
                    <textarea id="compose-textarea" class="form-control" name="message" data-parsley-trigger="change" required="" style="height: 300px"></textarea>
              </div> 

              
                
              <!-- <div class="form-group">
                <fieldset>
                   <label for="exampleInputEmail1">Attachments</label>
                    <input class="files" name="user_files[]" accept=".png, .jpg, .jpeg,.pdf" type="file" ><span><a href="javascript:void(0);" class="add" >Add More</a></span>
                  <div class="contents"></div>
                </fieldset>
              </div> -->

              



              <div class="box-footer">
              <div class="pull-right">
                <!-- <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button> -->
                <button type="submit" class="btn btn-success"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              <button type="reset" class="btn btn-danger"><i class="fa fa-times"></i> Discard</button>
            </div>



            </form> 


            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
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
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });


            $(document).ready(function() {
                $(".add").click(function() {
                    $('<div><input class="files" name="user_files[]" type="file" ><span class="rem" ><a href="javascript:void(0);" >Remove</span></div>').appendTo(".contents");
                });
                $('.contents').on('click', '.rem', function() {
                    $(this).parent("div").remove();
                });

            });
  

</script>