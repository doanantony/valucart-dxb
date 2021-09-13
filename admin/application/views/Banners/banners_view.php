<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
       <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Banners</a></li>
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
                            <th>Position</th>
                            <th>Externel Link</th>
                            <th>Resource Type</th>
                            <th>Action</th>
                        </tr>
                     </thead> 
                     <tbody>
                        <?php
                           foreach($data as $banners) {
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $banners->id; ?></td>
                           <td class="center"><?php echo $banners->name; ?></td>
                           <!-- <td class="center"><?php echo $banners->href; ?></td>  -->
                          
                           <td class="center"><?php echo $banners->position; ?></td>
                           <td class="center"><?php echo $banners->href; ?></td>
                           <td class="center"><?php echo $banners->resource_type; ?></td>
                                                        <td class="center">

                            <a class="btn btn-xs bg-olive show-bannerdetails  href="javascript:void(0);"  data-id="<?php echo $banners->id; ?>"><i class="fa fa-fw fa-eye"></i> View </a>
                            
                            <a class="btn btn-xs btn-primary" href="<?php echo base_url();?>banners/edit/<?php echo $banners->id; ?>"><i class="fa fa-fw fa-edit"></i>Edit</a>


                            <a class="btn btn-xs btn-danger" href="<?php echo base_url();?>banners/delete/<?php echo $banners->id; ?>" onClick="return doconfirm()"><i class="fa fa-fw fa-trash"></i>Delete</a> 


                          <!--   <?php if( $banners->status){?>
                              <a class="btn btn-sm label-warning" href="<?php echo base_url();?>banners/unpublish/<?php echo $banners->id; ?>"> <i class="fa fa-folder-open"></i> Unpublish </a>           
                              <?php
                                 }
                                 else
                                 {
                                 ?>
                              <a class="btn btn-sm label-success" href="<?php echo base_url();?>banners/publish/<?php echo $banners->id; ?>"> <i class="fa fa-folder-o"></i> Publish </a>
                              <?php
                                 }
                                 ?> -->



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
                            <th>Position</th>
                            <th>Externel Link</th>
                            <th>Resource Type</th>
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
            <h4 class="modal-title">View Banner Images</h4>
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


