<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
       <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Subcategories</a></li>
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
                            <th>Sub Category</th>
                            <th>Category</th>
                            <th>Status</th>
                          <th>Created Date</th>
                                <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                     </thead> 
                     <tbody>
                        <?php
                           foreach($data as $subcategories) {
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $subcategories->id; ?></td>
                           <td class="center"><?php echo $subcategories->name; ?></td>
                           <td class="center"><?php echo get_category_name($subcategories->category_id); ?></td>
                           <td><span class="center label  <?php if($subcategories->status == '1')
                            {
                            echo "label-success";
                            }else
                            { 
                            echo "label-warning"; 
                            }
                            ?>"><?php if($subcategories->status == '0')
                            {
                            echo "Not Published";
                            }elseif($subcategories->status == '1'){
                              echo "Published";
                            }else
                            { 
                            echo "Deleted"; 
                            }
                            ?></span>   
                                                                                  
                         </td> 
                           <td class="center"><?php echo $subcategories->created_at; ?></td>
                           <td class="center"><?php echo $subcategories->updated_at; ?></td>                                           
                              <td class="center">

                                <a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>Subcategories/edit/<?php echo $subcategories->id; ?>">
                                  <i class="glyphicon glyphicon-edit icon-white"></i>
                                  Edit
                              </a> &nbsp;

                              <?php if( $subcategories->status){?>
                              <a class="btn btn-xs label-warning" href="<?php echo base_url();?>subcategories/unpublish/<?php echo $subcategories->id; ?>"> <i class="fa fa-folder-open"></i> Unpublish </a>           
                              <?php
                                 }
                                 else
                                 {
                                 ?>
                              <a class="btn btn-xs label-success" href="<?php echo base_url();?>subcategories/publish/<?php echo $subcategories->id; ?>"> <i class="fa fa-folder-o"></i> Publish </a>
                              <?php
                                 }
                                 ?>



           
           </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th class="hidden">ID</th>
                             <th>Sub Category</th>
                            <th>Category</th>
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
