<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1><?php echo $page_data->function_title; ?>
         <small><?php echo $page_data->function_small; ?></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="#">Manage Vendors</a></li>
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
                           <th>Name</th>
                           <th>Email Id</th>
                           <th>Created Date</th>
                           <th>Updated Date</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           foreach($data as $vendors) {
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $vendors->id; ?></td>
                           <td class="center"><?php echo $vendors->name; ?></td>
                           <td class="center"><?php echo $vendors->email; ?></td>
                           <td class="center"><?php echo $vendors->created_at; ?></td>
                           <td class="center"><?php echo $vendors->updated_at; ?></td>
                           <td class="center">
                              <a class="btn btn-xs bg-olive show-vendorsdetails  href="javascript:void(0);"  data-id="<?php echo $vendors->id; ?>"><i class="fa fa-fw fa-eye"></i> View </a>
                                <a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>vendors/settlement/<?php echo $vendors->id; ?>">
                                 <i class="glyphicon glyphicon-credit-card icon-white"></i>
                                 Settlements
                                 </a> &nbsp;
                           </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th class="hidden">ID</th>
                           <th>Name</th>
                           <th>Email Id</th>
                           <th>Created Date</th>
                           <th>Updated Date</th>
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
<div class="modal fade modal-wides" id="popup-patientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center"> Vendor Details</h4>
         </div>
         <div class="modal-patientbody">
         </div>
         <div class="business_info">
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
         </div>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>