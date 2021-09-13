<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
       <h1>
        <?php echo $page_data->function_title; ?>
         <small><?php echo $page_data->function_small; ?></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-male"></i>Home</a></li>
          <li><a href="#">Permission</a></li>
         <li class="active">View Permission</li>
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
               <!-- /.box-header -->
               <div class="box-body">
                  <table class="table table-bordered table-striped datatable">
                     <thead>
                        <tr>
                           <th class="hidden">ID</th>
                            <th>Valucart Roles</th>
                            <th>Action</th>
                        </tr>
                     </thead> 
                     <tbody>
                        <?php
                           foreach($data as $roles) {
                                 
                           ?>
                        <tr>
                           <td class="hidden"><?php echo $roles->id; ?></td>
                           <td class="center"><?php echo $roles->group_name; ?></h3></td>                                                
                           <td class="center">

                           <!--  <button class="fa fa-lock btn" href="<?php echo base_url(); ?>permission/asign_permission/<?php echo $roles->id; ?>" style="background-color:#FDCC0F;color: #000" >&nbsp;Perimission</button> -->

                            <a class="fa fa-lock btn" href="<?php echo base_url(); ?>permission/permission_assign/<?php echo $roles->id; ?>"style="background-color:#FDCC0F;color: #000" >&nbsp;
                            
                            Perimission
                        </a>

                    

                          </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th class="hidden">ID</th>
                            <th>Valucart Roles</th>                  
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
