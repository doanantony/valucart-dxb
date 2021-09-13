<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>Brands
            <small>All Brands</small>
          </h1>
      <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">All Brands</a></li>
            <li class="active">View Brands</li>
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
                
                <h3 class="box-title">View All Brands</h3>
                <div class="pull-right box-tools">
            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>




              </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <table class="table table-bordered table-striped dataTable-custom" data-ajax="brands/get_all_brands" data-ordering="true" id="activity_table">
                     <thead>
                        
                        <tr>
                            <th>ID</th>
                            <th>Brand</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                     </thead> 
                     <tbody></tbody>
                     <tfoot>
                        <tr>
                           <th>ID</th>
                            <th>Brand</th>
                            <th>Created At</th>
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
