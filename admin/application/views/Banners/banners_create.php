<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Banners</a></li>
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

                  <div class="col-md-12">

                     <div class="row">
                       <div class="form-group col-md-11">
                          <label>Mode of Resource</label><br/>
                          
                         
                          <div class="col-md-2"><label><input type="radio" name="mode" value="department" class="required">&nbsp;Department</label></div>

                          <div class="col-md-2"><label><input type="radio" name="mode" value="brand" class="required">&nbsp;Brand</label></div>

                          <div class="col-md-2"><label><input type="radio" name="mode" value="category" class="required">&nbsp;Category</label></div>

                          <div class="col-md-2"><label><input type="radio" name="mode" value="subcategory" class="required">&nbsp;Sub Category</label></div>

                          <div class="col-md-2"><label><input type="radio" name="mode" value="bundle" class="required">&nbsp;Bundle</label></div>


                         <!--  <div class="col-md-2"><label><input type="radio" name="mode" value="bundlecategory" class="required">&nbsp;Bundle Category</label></div> -->

                          <div class="col-md-2"><label><input type="radio" name="mode" value="bundlecategory" class="required">&nbsp;Bundle Category</label></div>

                          <div class="col-md-2"><label><input type="radio" name="mode" value="product" class="required">&nbsp;Product</label></div>

                       </div>
                     </div>




                 




                    


                  </div>
                  

                  <div class="col-md-6">

                    
                  <div class="row mode" id="department">
                      <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Department</label>
                            <select name="dep_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Department </option>
                                   <?php
                                      foreach ($department as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                      </div>
                  </div>



                 <div class="row mode" id="brand">
                      <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Brand</label>
                            <select name="brand_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Brand </option>
                                   <?php
                                      foreach ($brand as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                      </div>
                  </div>


                   <div class="row mode" id="category">
                       <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Category</label>
                             <select name="cat_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Category </option>
                                   <?php
                                      foreach ($category as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                      </div>
                  </div>




                   <div class="row mode" id="subcategory">
                      <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Sub Category</label>
                             <select  name="sub_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Sub Category </option>
                                   <?php
                                      foreach ($subcategory as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                      </div>
                  </div>

                  
                  <div class="row mode" id="bundle">
                       <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Bundle</label>
                            <select name="bund_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Bundle </option>
                                   <?php
                                      foreach ($bundle as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                      </div>
                  </div>


                  <div class="row mode" id="bundlecategory">
                      <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Bundle Catgeory</label>
                            <select  name="subcat_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Sub Category </option>
                                   <?php
                                      foreach ($subcategory as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                      </div>
                  </div>


                  <div class="row mode" id="product">


                      <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Product Catgeory</label>
                            <select id="subcate_id" name="prod_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Category </option>
                                   <?php
                                      foreach ($category as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                      </div>


                       <div class="col-md-6" style="width:535px;">
                       <label class="exampleInputEmail1">Products</label>
                            <select id="prod" name="product_id" class="form-control select2 ">
                                <option disabled="" selected="selected">Select Product</option>
                            </select>
                      </div>




                  </div>








                           <div class="form-group has-feedback">
                          <label>Banner Position</label>
                          <select class="form-control select2 required" name="position">
                            <option value="home_banners">Home Banners</option>
                            <option value="Popular_Department_1">Popular Department 1</option>
                            <option value="Popular_Department_2">Popular Department 2</option>
                            <option value="VC_Certified">VC Certified</option>
                          </select>
                        </div>




                            <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Banner Name</label>
                                <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="50"   required="" placeholder="Enter Banner Name" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                           <!-- <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">External Link</label>
                                <input type="text" class="form-control " name="href" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="150"   required="" placeholder="Enter External Link" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div> -->


                           <div class="form-group">
                                  <label class="control-label" for="image">Landscape Image</label>
                                  <input type="file" accept=".png, .jpg, .jpeg" multiple name="landscape" id="image" class="form-control required regcom" class size="20" />
                                <div id="image_req" style="color: red"></div>
                          </div>   



                          <div class="form-group">
                                  <label class="control-label" for="image">Portrait Image</label>
                                  <input type="file" accept=".png, .jpg, .jpeg" multiple name="portrait" id="image" class="form-control required regcom" class size="20" />
                                <div id="image_req" style="color: red"></div>
                          </div>   

                        </div>
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


<script type="text/javascript">
  $(".mode").hide('slow');
  var i = 0;
  $('input[type=radio]').on('click',function(){
    var div = $(this).val();
    $(".mode").hide('slow');
    $("#"+div).show('slow');
    $('input[type=number]').removeClass("required");
    $('input[type=text]').removeClass("required");
    // if(div=='csv'){
    //    $('input[type=text]').addClass("required");
    // } else if(div=='range'){
    //   $("#start_at").addClass("required");
    //   $("#end_at").addClass("required");
    // } else {
    //   $(".unique_no").addClass("required");
    // }
  })




        $("#subcate_id").on('change', function(){
        var id = $(this).val();
        $('#prod').empty();
        prod_list();
      });



      function prod_list(){
        var subcat_id = $("#subcate_id").val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Banners/get_prod_list'); ?>',
            data: 'subcat_id='+subcat_id,
            success: function(response){
              var prod_list = JSON.parse(response);
              console.log(prod_list)
              $("#prod").append($('<option disabled selected="selected">Select Products</select>'));
              prod_list.forEach(function(element){
                $("#prod").append($('<option></option>').attr("value",element.id).text(element.name));
                //html += '<option value="'+element.id+'">'+element.name+'</option>';
              })
              //console.log(html);
              //$("#sub_cat").append(html);
            }
          });
      }








</script>