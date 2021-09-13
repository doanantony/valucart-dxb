<div class="content-wrapper">

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><?php echo $page_data->function_title; ?>
          <small><?php echo $page_data->function_small; ?></small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Products</a></li>
          <li class="active">Update</li>
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
                
                <h3 class="box-title"></h3>


                <a class="btn btn-primary btn-sm" href="<?php echo base_url("metatags/addtags/$pro_id"); ?>"><i class="fa fa-fw fa-tags"></i>Add Metatags</a>

                <a class="btn btn-success btn-sm" href="<?php echo base_url("products/editimages/$pro_id"); ?>"><i class="fa fa-fw fa-edit"></i>Edit Images</a>



                <div class="pull-right box-tools">
            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
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
                                <input type="text" class="form-control" name="name" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="150"  required="" placeholder="Enter Product Name" value="<?php echo $result->name; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>

                          <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Product SKU</label>
                                <input type="text" class="form-control" name="sku" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="15"  required="" placeholder="Enter Product Sku" value="<?php echo $result->sku; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                          </div>




                       <!--  <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Department</label>
                        <select name="department_id" class="form-control select2 required">
                            <?php
                            foreach ($department as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($result->department_id == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                        </div> -->

                        <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Brand</label>
                        <select name="brand_id" class="form-control select2 required">
                            <?php
                            foreach ($brand as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($result->brand_id == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                        </div>


                        <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Category</label>
                        <select name="category_id" class="form-control select2 required">
                            <?php
                            foreach ($category as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($result->category_id == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                        </div>


                        <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Sub Category</label>
                        <select name="subcategory_id" class="form-control select2 required">
                            <?php
                            foreach ($subcategory as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($result->subcategory_id == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                        </div>


                      <!-- <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Vendor</label>
                        <select name="vendor_id" class="form-control select2 required">
                            <?php
                            foreach ($vendors as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($provendor->vendor_id == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                        </div>
 -->


                       <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Community</label>
                        <select name="community_id" class="form-control select2 required">
                            <?php
                            foreach ($community as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($procommunity->community_id == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                        </div> 

                       <!--  <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Product Type</label>
                        <select name="type" class="form-control select2 required">
                            <?php
                            foreach ($type as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($result->type == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                        </div> -->

                          <div class="form-group has-feedback">
                        <label for="exampleInputEmail1">Description</label>
                            <input type="text" class="form-control" name="description" data-parsley-trigger="change" data-parsley-minlength="3" data-parsley-maxlength="500"  required="" placeholder="Enter Product Description" value="<?php echo $result->description; ?>">
                         <span class="glyphicon  form-control-feedback"></span>
                      </div>
                     




                    </div>

                    <div class="col-md-6">

                     


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Packaging Quantity</label>
                                <input type="text" class="form-control " name="packaging_quantity" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="50" required="" placeholder=" Enter Packaging Quantity" value="<?php echo $result->packaging_quantity; ?>" >
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>


                      <div class="form-group has-feedback">
                        <label class="exampleInputEmail1">Packaging Quantity Unit</label>
                        <select name="packaging_quantity_unit_id" class="form-control select2 required">
                            <?php
                            foreach ($matricunits as $rs) {
                                ?>
                            <option value="<?php echo $rs->id; ?>" <?php if($result->packaging_quantity_unit_id == $rs->id) echo 'selected="SELECTED"'; ?>><?php echo $rs->name; ?></option>
                            <?php } ?>
                        </select>
                      </div>

                       <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Cost Price</label>
                                <input type="text" class="form-control " name="price" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"   required="" placeholder=" Enter Cost Price" value="<?php echo $provendor->price; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>

                      
                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Minimum Inventory</label>
                                <input type="text" class="form-control " name="minimum_inventory" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10" data-parsley-type="digits"   required="" placeholder=" Enter Minimum Inventory" value="<?php echo $result->minimum_inventory; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Maximum Selling Price</label>
                                <input type="text" class="form-control " name="maximum_selling_price" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"   required="" placeholder=" Enter Maximum Selling Price" value="<?php echo $result->maximum_selling_price; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Valucart Price</label>
                                <input type="text" class="form-control " name="valucart_price" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"   required="" placeholder=" Enter Valucart Selling Price" value="<?php echo $result->valucart_price; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>


                      <!-- <div class="form-group has-feedback">
                            <label>Admin Bundelable</label>
                            <select class="form-control select2 required" name="is_admin_bundlable">
                              <option value="1" <?php if($result->is_admin_bundlable==1){?> selected="selected" <?php } ?>>Enable</option>
                              <option value="0" <?php if($result->is_admin_bundlable==0){?> selected="selected" <?php } ?>>Disable</option>
                            </select>
                      </div> -->


                      <!-- <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Admin Bundle Discount</label>
                                <input type="text" class="form-control " name="admin_bundle_discount" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"   required="" placeholder=" Enter Admin Bundle Discount" value="<?php echo $result->admin_bundle_discount; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>

                      <div class="form-group has-feedback">
                            <label>Customer Bundelable</label>
                            <select class="form-control select2 required" name="is_customer_bundlable">
                              <option value="1" <?php if($result->is_customer_bundlable==1){?> selected="selected" <?php } ?>>Enable</option>
                              <option value="0" <?php if($result->is_customer_bundlable==0){?> selected="selected" <?php } ?>>Disable</option>
                            </select>
                      </div>


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Customer Bundle Discount</label>
                                <input type="text" class="form-control " name="customer_bundle_discount" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"   required="" placeholder=" Enter Customer Bundle Discount" value="<?php echo $result->customer_bundle_discount; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                      </div>


                      <div class="form-group has-feedback">
                            <label>Bulk Product</label>
                            <select class="form-control select2 required" name="is_bulk">
                              <option value="1" <?php if($result->is_bulk==1){?> selected="selected" <?php } ?>>Enable</option>
                              <option value="0" <?php if($result->is_bulk==0){?> selected="selected" <?php } ?>>Disable</option>
                            </select>
                      </div>


                      <div class="form-group has-feedback">
                                <label for="exampleInputEmail1">Bulk Quantity</label>
                                <input type="text" class="form-control " name="bulk_quantity" data-parsley-trigger="change" data-parsley-minlength="1"  data-parsley-maxlength="10"   required="" placeholder=" Enter Bulk Quantity" value="<?php echo $result->bulk_quantity; ?>">
                             <span class="glyphicon  form-control-feedback"></span>
                      </div> -->


                      <div class="form-group has-feedback">
                            <label>Featured Product</label>
                            <select class="form-control select2 required" name="is_featured">
                              <option value="1" <?php if($result->is_featured==1){?> selected="selected" <?php } ?>>Enable</option>
                              <option value="0" <?php if($result->is_featured==0){?> selected="selected" <?php } ?>>Disable</option>
                            </select>
                      </div>


                      <!-- <div class="is_offer">
                            <label>Offer Product</label>
                            <select class="form-control select2 required" name="is_offer">
                              <option value="1" <?php if($result->is_offer==1){?> selected="selected" <?php } ?>>Enable</option>
                              <option value="0" <?php if($result->is_offer==0){?> selected="selected" <?php } ?>>Disable</option>
                            </select>
                      </div> -->


                      <!-- <div class="is_offer">
                            <label>Publish Status</label>
                            <select class="form-control select2 required" name="published">
                              <option value="1" <?php if($result->published==1){?> selected="selected" <?php } ?>>Publish</option>
                              <option value="0" <?php if($result->published==0){?> selected="selected" <?php } ?>>UnPublish</option>
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