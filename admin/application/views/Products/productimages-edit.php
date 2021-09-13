
<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
          Add Images
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-male"></i>Home</a></li>
         <li class="active"> Edit Product Images</li>
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


        <!--  form -->

         <div class="col-md-12">
              <!-- general form elements -->
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Product: <?php echo $pro_data->name; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                 <form role="form" action="" method="post" data-parsley-validate="" class="validate" enctype="multipart/form-data">
                    <div class="box-body">
                          <div class="col-md-6">
         
                        
                          <div class="form-group">
                                  <label class="control-label" for="image">Product Image</label>
                                  <input type="file" multiple name="image" id="image" class="form-control required regcom" class size="20" />
                                <div id="image_req" style="color: red"></div>
                          </div>

                          <div class="form-group has-feedback">
                            <label>Is Thumb</label>
                            <select class="form-control select2 required" name="is_thumb">
                              <option value="1">Enable</option>
                              <option value="0">Disable</option>
                            </select>
                          </div>





               
                  
                
                  </div>
          
               

                     <div class="col-md-12">
                      <div class="form-group">
                          <input type="submit" class="btn btn-primary" value="Save" id="taxiadd">                      
                       </div>
                     </div>
          
          
             
               
           
           
    
             </div><!-- /.box-body -->
  
                </form>
              </div><!-- /.box -->
            </div>
        <!-- form -->




         <div class="col-xs-12">
            <!-- /.box -->
            <div class="box">
               <div class="box-header">
                  <h3 class="box-title"> Product Details</h3>
               </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <table class="table table-bordered ">
                     <thead>
                        <tr>
                           <th class="hidden">ID</th>
                            <th>Product Image</th>
                            <th>Is Thumb</th>                     
                           <th>Action</th>
                        </tr>
                     </thead> 
                      <tbody>
                        <?php


                           foreach($data as $image) {
                          // print_r($all);
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $image->id; ?></td>
                           <td class="center"><img src="<?php echo $image->path;?>" width="100px" height="100px"  /></td>
                          
                           <td><span class="center label  <?php if($image->is_thumb == '1')
                            {
                            echo "label-success";
                            }else
                            { 
                            echo "label-warning"; 
                            }
                            ?>"><?php if($image->is_thumb == '0')
                            {
                            echo "Not Thumb";
                            }elseif($image->is_thumb == '1'){
                              echo "Thumb";
                            }else
                            { 
                            echo "Published"; 
                            }
                            ?></span>                                                         
                         </td>  




                     
                           <td class="center">                             
                            



                              <a class="btn btn-sm btn-danger" href="<?php echo base_url();?>products/delete_product_image/<?php echo $image->id; ?>" onClick="return doconfirm()">
                              <i class="fa fa-fw fa-trash"></i>Delete</a>                  
            
                           </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                  
                  </table>

               <!--    <button type="submit" class="btn btn-success btn-lg pull-right">Review & Publishs</button>

                  <button type="submit" class="btn btn-success btn-lg pull-right show-vendorsdetails"  href="javascript:void(0);"  data-id="<?php echo $vendors->id; ?>">Review & Publish</button> -->

                  <!-- <button class="btn btn-success btn pull-right bg-olive show-productsdetails  href="javascript:void(0);"  data-id="<?php echo $pro_data->sku; ?>"><i class="fa fa-fw fa-eye"></i> Review & Publish </button> -->


               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>



<div class="modal fade modal-wides" id="popup-patientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center"> Product Details</h4>
         </div>
         <div class="modal-patientbody">
         </div>
         <div class="business_info">
         </div>
         <div class="modal-footer">

            <?php 
          
            if( $pro_data->published == '0'){?>
                              <a class="btn btn-info pull-right label-warning" href="<?php echo base_url();?>products/publish/<?php echo $pro_data->id; ?>"> <i class="fa fa-fw fa-check"></i> Publish </a>           
                              <?php
                                 }
                                 else
                                 {
                                 ?>
                              <a class="btn btn-info pull-right label-success" href="<?php echo base_url();?>products/unpublish/<?php echo $pro_data->id; ?>"> <i class="fa fa-fw fa-check"></i> UnPublish </a>
                              <?php
                                 }
                                 ?>

            <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
         </div>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>