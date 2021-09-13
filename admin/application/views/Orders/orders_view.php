<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
       <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Orders</a></li>
            <li class="active">View</li>
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
            <!-- /.box -->
          <div class="box box-primary">

            <div class="box-header with-border">
               <h3 class="box-title"><?php echo $page_data->function_head; ?></h3>
               <div class="pull-right box-tools">
            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
               <div class="box-body">
                  <table class="table table-bordered table-striped datatable">
                     <thead>
                        <tr>
                           <th class="hidden">ID</th>
                             <th>Order ID</th>
                             <th>Customer</th>
                             <th>Email</th>
                             <th>Price</th>
                             <th>Status</th>
                             <th>Order Placed</th>
                             <th>Action</th>
                        </tr>
                     </thead> 
                     <tbody>
                        <?php
                           foreach($data as $orders) {
                                  // echo "<pre>";
                                  // print_r(unserialize($orders->snapshots));die;
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $orders->id; ?></td>
                           <td class="center"><?php echo $orders->order_reference; ?></td>
                           <td class="center"><?php echo get_customer_name($orders->customer_id); ?></td>
                           <td class="center"><?php echo get_customer_email($orders->customer_id); ?></td>
                           <td class="center"><?php echo $orders->price; ?></td>
                            <td><span class="center label  <?php if($orders->status == '1')
                            {
                            echo "label label-info";
                            }elseif($orders->status == '2')
                            { 
                            echo "label label-warning"; 
                            }elseif($orders->status == '3')
                            { 
                            echo "label label-success"; 
                            }else{
                            echo "label label-danger";   
                            }



                            ?>"><?php if($orders->status == '1')
                            {
                                echo "Order Created";
                            }elseif($orders->status == '2'){
                                echo "Order Placed";
                            }elseif($orders->status == '3'){
                                 echo "Shipped";
                            }
                            else
                            { 
                            echo "Delivered"; 
                            }
                            ?></span>                                                         
                         </td> 
                         <td class="center"><?php echo $orders->created_at; ?></td>                                                
                    <td class="center">

                      <a class="btn btn-xs bg-olive show-orderdetails  href="javascript:void(0);"  data-id="<?php echo $orders->id; ?>"><i class="fa fa-fw fa-eye"></i> View </a>

                    
                              <a class="btn btn-xs label-primary" href="<?php echo base_url();?>orders/shipped/<?php echo $orders->id; ?>"> <i class="fa fa-folder-open"></i> Shipped </a>           
                            
                              <a class="btn btn-xs label-success" href="<?php echo base_url();?>orders/deliverd/<?php echo $orders->id; ?>"> <i class="fa fa-folder-o"></i> Delivered </a>
                            
                             <!--  <a class="btn btn-sm label-danger" href="<?php echo base_url();?>orders/cancel_order/<?php echo $orders->id; ?>"> <i class="fa fa-folder-o"></i> Cancel Order </a> -->


           
           </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th class="hidden">ID</th>
                            <th>Order ID</th>
                             <th>Customer</th>
                              <th>Email</th>
                              <th>Price</th>
                              <th>Status</th>
                              <th>Order Placed</th>
                             <th>Action</th>

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

<div class="modal fade modal-wide" id="popup-patientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">View Order Details</h4>
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


