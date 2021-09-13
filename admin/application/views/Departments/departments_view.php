<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>Departments
         <small>All Departments</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="#">Departments</a></li>
         <li class="active">View All Departments</li>
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
                  <h3 class="box-title">Departments List</h3>
                  <div class="pull-right box-tools">
                     <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                     <i class="fa fa-minus"></i>
                     </button>
                  </div>
               </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <table class="table table-bordered table-striped dataTable-custom" data-ajax="departments/get_all_departments" data-ordering="true" id="activity_table">
                     <thead>
                        <tr>
                           <th>Id</th>
                           <th>Department</th>
                           <th>Popular Status</th>
                           <th>Status</th>
                           <th>Created Date</th>
                          <!--  <th>Action</th> -->
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                     <tfoot>
                        <tr>
                        <tr>
                           <th>Id</th>
                           <th>Department</th>
                           <th>Popular Status</th>
                           <th>Status</th>
                           <th>Created Date</th>
                         <!--   <th>Action</th> -->
                        </tr>
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