<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
       <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Bundles</a></li>
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
                            <th>Category</th>
                            <th>Valucart Price</th>
                            <th>Status</th>
                            <th>Created Date</th>
                           <th>Action</th>

                        </tr>
                     </thead> 
                     <tbody>
                        <?php
                           foreach($data as $bundles) {
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $bundles->id; ?></td>
                           <td class="center"><?php echo $bundles->name; ?></td>
                           <td class="center"><?php echo get_bundlecat_name($bundles->category_id); ?></td>   
                           <td class="center"><?php echo $bundles->valucart_price; ?></td> 
                           
                           <td><span class="center label  <?php if($bundles->status == '1')
                            {
                            echo "label-success";
                            }else
                            { 
                            echo "label-warning"; 
                            }
                            ?>"><?php if($bundles->status == '0')
                            {
                            echo "Not Published";
                            }elseif($bundles->status == '1'){
                              echo "Published";
                            }else
                            { 
                            echo "Deleted"; 
                            }
                            ?></span>   
                            <td class="center"><?php echo $bundles->created_at; ?></td>                                                      
                         </td>
                            <td class="center">

                              <a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>Bundles/edit/<?php echo $bundles->id; ?>">
                                <i class="glyphicon glyphicon-edit icon-white"></i>
                                Edit
                             </a> &nbsp;

                              <a class="btn btn-xs btn-danger" href="<?php echo base_url();?>bundles/delete/<?php echo $bundles->id; ?>" onClick="return doconfirm()"><i class="fa fa-fw fa-trash"></i>Delete</a> 


                            <?php if( $bundles->status){?>
                              <a class="btn btn-xs label-warning" href="<?php echo base_url();?>bundles/unpublish/<?php echo $bundles->id; ?>"> <i class="fa fa-folder-open"></i> Unpublish </a>           
                              <?php
                                 }
                                 else
                                 {
                                 ?>
                              <a class="btn btn-xs label-success" href="<?php echo base_url();?>bundles/publish/<?php echo $bundles->id; ?>"> <i class="fa fa-folder-o"></i> Publish </a>
                              <?php
                                 }
                                 ?>


           
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th class="hidden">ID</th>
                           <th>Name</th>
                            <th>Category</th>
                            <th>Valucart Price</th>
                            <th>Status</th>
                            <th>Created Date</th>
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
