<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
          Notifications
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i>Home</a></li>
         <li><a href="#">Notifications</a></li>
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
              <h3 class="box-title">Send Push Notifications</h3>

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


                 <!-- <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Title</label>
                              <p class="emoji-picker-container">
                              <textarea class="input-field" data-emojiable="true"
                                data-emoji-input="unicode" type="text" name="title"
                                id="textarea" required="" style="height: 100px" placeholder="Enter Title">  </textarea>
                            </p>
                             <span class="glyphicon  form-control-feedback"></span>
                  </div> -->

                  <div class="form-group has-feedback">
                   <label for="exampleInputEmail1">Title</label>
                    <p class="emoji-picker-container">
                    <textarea data-emojiable="true" data-emoji-input="unicode" id="textarea" class="form-control" name="title" data-parsley-trigger="change" required="" style="height: 100px"></textarea>
                  </p>
                   </div> 


                 <div class="form-group has-feedback">
                   <label for="exampleInputEmail1">Message</label>
                    <p class="emoji-picker-container">
                    <textarea data-emojiable="true" data-emoji-input="unicode" id="textarea" class="form-control" name="message" data-parsley-trigger="change" required="" style="height: 100px"></textarea>
                  </p>
                   </div> 

                   


                   <div class="form-group">
                                  <label class="control-label" for="image">Image</label>
                                  <input type="file" accept=".png, .jpg, .jpeg" multiple name="image" id="image" class="form-control  regcom" class size="20" />
                                <div id="image_req" style="color: red"></div>
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
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
  });




           
            function postReply(commentId) {
                $('#commentId').val(commentId);
                $("#name").focus();
            }

            $("#submitButton").click(function () {
                $("#comment-message").css('display', 'none');
                var str = $("#frm-comment").serialize();

                $.ajax({
                    url: "comment-add.php",
                    data: str,
                    type: 'post',
                    success: function (response)
                    {
                        $("#comment-message").css('display', 'inline-block');
                        $("#name").val("");
                        $("#comment").val("");
                        $("#commentId").val("");
                        listComment();
                    }
                });
            });

            $(document).ready(function () {
                listComment();
            });

            $(function () {
                // Initializes and creates emoji set from sprite sheet
                window.emojiPicker = new EmojiPicker({
                    emojiable_selector: '[data-emojiable=true]',
                    assetsPath: 'assets/vendor/emoji-picker/lib/img/',
                    popupButtonClasses: 'icon-smile'
                });

                window.emojiPicker.discover();
            });


            function listComment() {
                $.post("comment-list.php",
                        function (data) {
                            var data = JSON.parse(data);

                            var comments = "";
                            var replies = "";
                            var item = "";
                            var parent = -1;
                            var results = new Array();

                            var list = $("<ul class='outer-comment'>");
                            var item = $("<li>").html(comments);

                            for (var i = 0; (i < data.length); i++)
                            {
                                var commentId = data[i]['comment_id'];
                                parent = data[i]['parent_comment_id'];

                                if (parent == "0")
                                {
                                    comments =  "<div class='comment-row'>"+
                                    "<div class='comment-info'><span class='posted-by'>" + data[i]['comment_sender_name'] + "</span></div>" + 
                                    "<div class='comment-text'>" + data[i]['comment'] + "</div>"+
                                    "<div><a class='btn-reply' onClick='postReply(" + commentId + ")'>Reply</a></div>"+
                                    "</div>";
                                    var item = $("<li>").html(comments);
                                    list.append(item);
                                    var reply_list = $('<ul>');
                                    item.append(reply_list);
                                    listReplies(commentId, data, reply_list);
                                }
                            }
                            $("#output").html(list);
                        });
            }

            function listReplies(commentId, data, list) {

                for (var i = 0; (i < data.length); i++)
                {
                    if (commentId == data[i].parent_comment_id)
                    {
                        var comments = "<div class='comment-row'>"+
                        " <div class='comment-info'><span class='posted-by'>" + data[i]['comment_sender_name'] + " </span></div>" + 
                        "<div class='comment-text'>" + data[i]['comment'] + "</div>"+
                        "<div><a class='btn-reply' onClick='postReply(" + data[i]['comment_id'] + ")'>Reply</a></div>"+
                        "</div>";
                        var item = $("<li>").html(comments);
                        var reply_list = $('<ul>');
                        list.append(item);
                        item.append(reply_list);
                        listReplies(data[i].comment_id, data, reply_list);

                    }
                }
            }

         

       </script>