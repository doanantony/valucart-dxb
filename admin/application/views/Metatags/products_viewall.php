<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>View Products
            <small>View All Products</small>
          </h1>
      <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Metatags</a></li>
            <li class="active"> All Products</li>
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
         <div class="col-xs-12">
            <div class="box box-primary">
                 <div class="box-header with-border">
                
                <h3 class="box-title">Products List</h3>
                <div class="pull-right box-tools">
            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>




              </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <table class="table table-bordered table-striped dataTable-custom" data-ajax="metatags/get_all_products" data-ordering="true" id="activity_table">
                     <thead>
                         <!-- <tr>
                            <th>ID</th>

                             <th><input type="text" class="form-control" name="name" id="name" data-column="0" /></th>

                             <th><input type="text" name="sku" class="form-control " style="width:110px;" id="sku" data-column="1" /></th>


                            
                            <th>
                              <select name="department_id" id="department_id"  style="width:90px;" class="form-control select" data-column="2">
                                <option value="" selected="selected">Select Department</option>
                                <?php
                                  foreach ($departments as $rs) { ?>
                                    <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                  <?php } ?>
                              </select>
                            </th>

                            

                            <th>
                              <select name="category_id" id="category_id"  style="width:90px;" class="form-control select" data-column="3">
                                <option value="" selected="selected">Select Category</option>
                                <?php
                                  foreach ($category as $rs) { ?>
                                    <option value="<?php echo $rs->id; ?>"><?php echo $rs->name; ?></option>
                                  <?php } ?>
                              </select>
                            </th>


                            
                        </tr> -->
                        <tr>
                         <!--   <th>ID</th> -->
                            <th>Id</th>
                            <th>Name</th>
                            <th>Sku</th>
                            <th>Department</th>
                            <th>Category</th>
                            <th>Valu Price</th>
                            <th>Actions</th>
                        </tr>
                     </thead> 
                     <tbody>
                       
                     </tbody>
                     <tfoot>
                        <tr>
                          <!--  <th>ID</th> -->
                            <th>Id</th>
                            <th>Name</th>
                            <th>Sku</th>
                            <th>Department</th>
                            <th>Category</th>
                            <th>Valu Price</th>
                            <th>Actions</th>
                        </tr>
                     </tfoot>
                  </table>
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


