<div class="content-wrapper">

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><?php echo $page_data->function_title; ?>
          <small><?php echo $page_data->function_small; ?></small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Products</a></li>
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
                              <label for="exampleInputEmail1">Product Name</label>
                              <input type="text" class="form-control " name="name" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="150"   required="" placeholder="Enter Product Name" >
                            <span class="glyphicon  form-control-feedback"></span>
                          </div>


                          <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Product SKU</label>
                              <input type="text" class="form-control " name="sku" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="20"   required="" placeholder="Enter Product Sku" >
                            <span class="glyphicon  form-control-feedback"></span>

                          </div>


                          <div class="form-group has-feedback">
                            <label class="exampleInputEmail1">Department</label>
                            <select name="department_id" class="form-control select2 required">
                                <option value="" selected="selected">Select Department </option>
                                   <?php
                                      foreach ($department as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                         </div>

                         <div class="form-group has-feedback">
                            <label class="exampleInputEmail1">Category</label>
                            <select id="cate_id" name="category_id" class="form-control select2 required">
                                <option value="" selected="selected">Select Category </option>
                                   <?php
                                      foreach ($category as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                         </div>


                         <div class="form-group has-feedback">
                            <label class="exampleInputEmail1">SubCategory</label>
                            <select id="sub_cat" name="subcategory_id" class="form-control select2 required">
                                <option disabled="" selected="selected">Select Subcategory</option>
                            </select>
                         </div>

                         <div class="form-group has-feedback">
                            <label class="exampleInputEmail1">Brand</label>
                            <select name="brand_id" class="form-control select2 required">
                                <option value="" selected="selected">Select Brand </option>
                                   <?php
                                      foreach ($brand as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                         </div>

                          <div class="form-group has-feedback">
                              <label for="exampleInputEmail1">Description</label>
                              <input type="text" class="form-control " name="description" data-parsley-trigger="change" data-parsley-minlength="5" data-parsley-maxlength="500"   required="" placeholder="Enter Product Description" >
                            <span class="glyphicon  form-control-feedback"></span>
                          </div>



                         <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Packaging Quantity</label>
                                <input type="text" class="form-control " name="packaging_quantity" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="50"   required="" placeholder=" Enter Packaging Quantity" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                          
                          

                         

                        </div>
                        <div class="col-md-6">
                          
                          
                          <div class="form-group has-feedback">
                            <label class="exampleInputEmail1">Package Quantity Unit</label>
                            <select name="packaging_quantity_unit_id" class="form-control select2 required">
                                <option value="" selected="selected">Select Package Quantity Unit </option>
                                   <?php
                                      foreach ($unit as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                         </div>
                         
                         <!--  <div class="form-group has-feedback">
                            <label class="exampleInputEmail1">Vendor</label>
                            <select name="vendor_id" class="form-control select2 required">
                                <option value="" selected="selected">Select Vendor </option>
                                   <?php
                                      foreach ($vendor as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                         </div> -->



                          <div class="form-group has-feedback">
                            <label class="exampleInputEmail1">Community</label>
                            <select name="community_id" class="form-control select2 required">
                                <option value="" selected="selected">Select Community </option>
                                   <?php
                                      foreach ($community as $rs) {?>
                              <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                    <?php } ?>
                            </select>
                         </div>



                         <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Cost Price</label>
                                <input type="text" class="form-control " name="price" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"  required="" placeholder=" Enter Cost Price" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>
                          
                          <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Maximum Selling Price</label>
                                <input type="text" class="form-control " name="maximum_selling_price" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"  required="" placeholder=" Enter Maximum Selling Price" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                          
                         <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Valucart Price</label>
                                <input type="text" class="form-control " name="valucart_price" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"  required="" placeholder=" Enter Valucart Price" >
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>
                        <div class="form-group has-feedback"></div>


                        <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Minimum Inventory</label>
                                <input type="text" class="form-control " name="minimum_inventory" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10" data-parsley-type="digits"   required="" placeholder=" Enter Minimum Inventory" >
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>

                       <!-- <div class="form-group has-feedback">
                            <label>Admin Bundelable</label>
                            <select class="form-control select2 required" name="is_admin_bundlable">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                      </div>


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Admin Bundle Discount</label>
                                <input type="text" class="form-control " name="admin_bundle_discount" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"  placeholder=" Enter Admin Bundle Discount" >
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>


                       <div class="form-group has-feedback">
                            <label>Customer Bundelable</label>
                            <select class="form-control select2 required" name="is_customer_bundlable">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                      </div>


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Customer Bundle Discount</label>
                                <input type="text" class="form-control " name="customer_bundle_discount" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"    placeholder=" Enter Customer Bundle Discount" >
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>
 

                        <div class="form-group has-feedback">
                            <label>Bulk</label>
                            <select class="form-control select2 required" name="is_bulk">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                      </div>


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Bulk Quantity</label>
                                <input type="text" class="form-control " name="bulk_quantity" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"   data-parsley-type="digits"   placeholder=" Enter bulk quantity" >
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>
                  -->

                       <div class="form-group has-feedback">
                            <label>Featued Product</label>
                            <select class="form-control select2 required" name="is_featured">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                          </div>


                      <!--  <div class="form-group has-feedback">
                          <label>Offer</label>
                          <select class="form-control select2 required" name="is_offer">
                            <option value="1">Enable</option>
                            <option value="0">Disable</option>
                          </select>
                        </div> -->







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



    <script type="text/javascript">


        $(".mode").hide('slow');
  var i = 0;
  $('input[type=radio]').on('click',function(){
    var div = $(this).val();
    $(".mode").hide('slow');
    $("#"+div).show('slow');
    $('input[type=number]').removeClass("required");
    $('input[type=file]').removeClass("required");
    if(div=='csv'){
       $('input[type=file]').addClass("required");
    } else if(div=='range'){
      $("#start_at").addClass("required");
      $("#end_at").addClass("required");
    } else {
      $(".unique_no").addClass("required");
    }
  })



      $("#cate_id").on('change', function(){
        var id = $(this).val();
        $('#sub_cat').empty();
        sub_cat_list();
      });



      function sub_cat_list(){
        var cat_id = $("#cate_id").val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Bundles/get_sub_list'); ?>',
            data: 'cat_id='+cat_id,
            success: function(response){
              var sub_list = JSON.parse(response);
              console.log(sub_list)
              $("#sub_cat").append($('<option disabled selected="selected">Select Subcategory</select>'));
              sub_list.forEach(function(element){
                $("#sub_cat").append($('<option></option>').attr("value",element.id).text(element.name));
                //html += '<option value="'+element.id+'">'+element.name+'</option>';
              })
              //console.log(html);
              //$("#sub_cat").append(html);
            }
          });
      }
    </script>



