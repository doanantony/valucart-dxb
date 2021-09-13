<div class="content-wrapper">
        <!-- Content Header (Page header) -->
            <section class="content-header">
        <h1>
          Assign Items to <?php echo urldecode($couponname); ?>
      </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-male"></i>Home</a></li>
            <li class="active"> Add Items</li>
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
                
                 <h3 class="box-title">Coupon: <?php echo urldecode($couponname); ?></h3>
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
                            <select  name="bund_cat_id" class="form-control select2 ">
                                <option value="" selected="selected">Select Bundle Category </option>
                                   <?php
                                      foreach ($bundlecategory as $rs) {?>
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












                             

                        </div>
                        </div>



                   <div class="box-footer text">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
                </form> 


                


              </div><!-- /.box -->
            </div>
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                       <h3 class="box-title"><?php echo urldecode($couponname); ?></h3> <h4>Coupon Items List</h4>
                        <div class="pull-right box-tools">
                            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th class="hidden">ID</th>
                                    <th>Item Type</th>
                                    <th>Item</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                           foreach($data as $couponitems) {
                          // print_r($all);

                           ?>
                                    <tr>
                                        <td class="hidden">
                                            <?php echo $couponitems->id; ?>
                                        </td>
                                        <td class="center">
                                          <?php echo $couponitems->item_type; ?>
                                           <!--  <?php echo get_customer_name($coupon->user_identifier); ?> -->
                                        </td>
                                        <td class="center">
                                           <?php echo get_coupon_itemname($couponitems->item_id,$couponitems->item_type); ?>
                                         
                                        </td>
                                        <td class="center">
                                           <?php echo $couponitems->created_at; ?>
                                        </td>

                                        <td class="center">

                                            <a class="btn btn-xs btn-danger" href="<?php echo base_url();?>coupons/remove_couponitems/<?php echo urlencode($couponitems->coupon); ?>/<?php echo $couponitems->id; ?>" onClick="return doconfirm_coupon()"><i class="fa fa-fw fa-close"></i>Remove Item</a>

                                        </td>
                                    </tr>
                                    <?php
                           }
                           ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col ends-->



        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade modal-wide" id="popup-patientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">View Cars Details</h4>
            </div>
            <div class="modal-patientbody">
            </div>
            <div class="business_info">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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