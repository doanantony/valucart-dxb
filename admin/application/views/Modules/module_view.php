<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        <?php echo $page_data->function_title; ?>
         <small><?php echo $page_data->function_small; ?></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-male"></i>Home</a></li>
         <li class="active">  Modules</li>
         <li class="active"> View Modules</li>
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
                
                <h3 class="box-title"><?php echo $page_data->function_head; ?></h3>
                <div class="pull-right box-tools">
            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>




              </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <table class="table table-bordered table-striped datatable">
                     <thead>
                        <tr>
                           <th class="hidden">ID</th>
                           <th>Module Name</th>
                           <th>Module Control</th>
                           <th>Module Menu</th>                       
                           <th>Module Class</th>
                           <th>Action</th>
                        </tr>
                     </thead> 
                     <tbody>
                        <?php
                           foreach($data as $module) {
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $module->id; ?></td>
                           <td class="center"><?php echo $module->module_name; ?></td>                         
                           <td class="center"><?php echo $module->module_control; ?></td>
                           <td class="center"><?php echo $module->module_menu; ?></td>                                      
                           <td class="center"><?php echo $module->module_class; ?></td>                         
                           <td class="center"><a class="btn bg-purple btn-block btn-sm" href="<?php echo base_url(); ?>Module/edit/<?php echo $module->id; ?>">
                <i class="glyphicon glyphicon-edit icon-white"></i>
                UPDATE
            </a> </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th class="hidden">ID</th>
                           <th>Module Name</th>
                           <th>Module Control</th>
                           <th>Module Menu</th>   
                           <th>Module Class</th>                    
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
