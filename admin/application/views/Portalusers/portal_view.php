<div class="content-wrapper" >
 <!-- Content Header (Page header) -->
 <section class="content-header">
     <h1><?php echo $page_data->function_title; ?>
          <small><?php echo $page_data->function_small; ?></small>
        </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Cards</a></li>
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
                <a class="btn bg-purple btn-block btn-sm" href="<?php echo base_url("portalusers/create"); ?>">
                          <i class="glyphicon glyphicon-plus icon-white"></i>
                          CREATE
                          </a>
              </div>

             <div class="box-body">
                <table class="table table-bordered table-striped datatable">
                   <thead>
                      <tr>
                         <th class="hidden">ID</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Display Name</th>
                          <th>Email Id</th>
                          <th>Status</th>
                          <th>Portal Activity</th>
                          <th>Profile Picture</th>
                          <th>Action</th>
                      </tr>
                   </thead> 
                   <tbody>
                      <?php
                         foreach($data as $portal) {
                               
                         ?>
                      <tr>
                         <td class="hidden"><?php echo $portal->id; ?></td>
                         <td class="center"><?php echo $portal->first_name; ?></td>
                         <td class="center"><?php echo $portal->last_name; ?></td>
                         <td class="center"><?php echo $portal->display_name; ?></td>  
                         <td class="center"><?php echo $portal->email_id; ?></td>
                         <td><span class="center label  <?php if($portal->status == '1')
                         {
                          echo "label-success";
                          }else
                          { 
                            echo "label-warning"; 
                          }
                          ?>"><?php if($portal->status == '0')
                          {
                            echo "Suspended";
                          }else
                          { 
                            echo "Active"; 
                          }
                          ?></span>                                                         
                        </td> 
                        

                        <?php if($portal->portal_activity == '1'){?>
                          <td class="center"><a href="#" style="color: green"><i class="fa fa-circle text-success"></i> Online</a></td>
                          <?php }else{ ?>
                            <td class="center"><a href="#" style="color: red"><i class="fa fa-circle text-danger"></i> Offline</a></i></td>
                           <?php } ?> 




                         <td class="center"><img src="<?php echo $portal->profile_pic; ?>" width="100px" height="100px"  /></td>  
                         <td class="center"><a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>portalusers/edit/<?php echo $portal->id; ?>">
              <i class="glyphicon glyphicon-edit icon-white"></i>
              Edit
          </a> &nbsp;
            <a class="btn btn-xs btn-success" href="<?php echo base_url(); ?>portalusers/company_access/<?php echo $portal->id; ?>">
              <i class="glyphicon fa fa-check icon-white"></i>
              Assign
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
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Display Name</th>
                          <th>Email Id</th>
                          <th>Status</th>
                          <th>Portal Activity</th>
                          <th>Action</th>
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
