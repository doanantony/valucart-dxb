<link rel="stylesheet" href="<?php echo base_url('assets/css/morris.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-datepicker.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/daterangepicker.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/daterangepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/morris.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/raphael.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.min.js'); ?>"></script>
<div class="content-wrapper" >
<!-- Content Header (Page header) -->
<section class="content-header">
   <h1>View Orders
      <small>All Orders</small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Orders</a></li>
      <li class="active">View All Orders</li>
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
               <h3 class="box-title">Orders List</h3>
               <div class="pull-right box-tools">
                  <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
                  <i class="fa fa-minus"></i>
                  </button>
               </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <table class="table table-bordered table-striped dataTable-custom" data-ajax="allorders/get_all_orders" data-ordering="true" id="activity_table">
                  <thead>
                     <tr>
                        <!--   <th>ID</th> -->
                        <th>Id</th>
                        <th>Order Id</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Order Placed</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                     <tr>
                        <!--  <th>ID</th> -->
                        <th>Id</th>
                        <th>Order Id</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Order Placed</th>
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

</div>
