<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>Portal Logs
            <small>Logs</small>
          </h1>
      <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Portal Logs</a></li>
            <li class="active">View Logs</li>
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
                
                <h3 class="box-title">All Logs</h3>
                <div class="pull-right box-tools">
            <button class="btn bg-purple btn-block btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>
              </div>
               <div class="box-body">
                  <table class="table table-bordered table-striped dataTable-custom" data-ajax="activity/get_all_activity" data-ordering="true" id="activity_table">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>
                              <select name="user_type_id" id="user_type_id" class="form-control select" data-column="0">
                                <option value="" selected="selected">Department</option>
                                <?php
                                  foreach ($usertype as $rs) { ?>
                                    <option value="<?php echo $rs->id; ?>"><?php echo $rs->type_name; ?></option>
                                  <?php } ?>
                              </select>
                            </th>
                            <th>
                              <select name="user_id" id="user_id" class="form-control select" data-column="1">
                                <option value="" selected="selected">Name</option>
                                <?php
                                  foreach ($users as $rs) { ?>
                                    <option value="<?php echo $rs['id']; ?>"><?php echo $rs['name']; ?></option>
                                  <?php } ?>
                              </select>
                            </th>
                            <th><input type="text" class="form-control " style="width:240px;" name="log" id="log" data-column="2" /></th>
                            <th><input type="text" class="form-control " style="width:240px;" name="ip_adress" id="ip_adress" data-column="3" /></th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                           <th>ID</th>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Activity</th>
                            <th>IP Address</th>
                            <th>Activity Date</th>
                        </tr>
                     </thead> 
                     <tbody></tbody>
                     <tfoot>
                        <tr>
                           <th>ID</th>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Activity</th>
                            <th>IP Address</th>
                            <th>Activity Date</th>
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
