<style type="text/css">
  .fa{
    font-family: Verdana,FONTAWESOMe;
    font-size: 13px;
  }
  .checkbox{
    margin-top: 0;
    margin-bottom: 5px;
  }
  /*.form-group {
    margin-bottom: 0px;

}*/
</style>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Permission</h4>
      </div>
      <div class="modal-body">      
          Permission
      </div>
      <div class="modal-footer">
      <!-- <button type="button" class="btn btn-default" onclick="window.location.href='<?php //echo base_url('program_center/create_center'); ?>'">New Group</button> -->
        <button type="button" class="btn btn-default" onclick="location.reload();" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <?php echo $page_data->function_title; ?>
         <small><?php echo $page_data->function_small; ?></small>
      </h1>
          <ol class="breadcrumb">
            <li><a href="super_dashboard.html"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="#">Manage Permission</a></li>
            <li class="active">Assign Permission</li>
          </ol>
        </section>
        <section class="content">
          <div class="row">
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
                <form id="permission" method="post">
                    <table class="table table-bordered table-striped">
                      <tbody>
                        <?php
                        $i=0;
                        $k=0;
                        foreach ($module as $rs) {
                          $control = $rs->module_control;
                          if($i%3==0){?>
                            <tr>                        
                          <?php  } ?>
                          
                          <td>
                            <div class="box-body" style="padding-left: 100px">
                             <label><input type="checkbox" id="<?php echo $rs->module_control; ?>" onclick="check_fun($(this));"><?php echo $rs->module_name; ?></label> 
                                <?php 
                                  foreach ($function[$control] as $row) {
                                    ++$k;
                                    ?>

                                    <div class="form-group">
                                      <div class="col-sm-10">
                                        <div class="checkbox">

                                          <label>
                                            <input type="checkbox" id="<?php echo $rs->module_control.$k; ?>" class="<?php echo $rs->module_control; ?>" name="<?php echo $rs->module_control; ?>[]" value="<?php echo $row->id; ?>" onclick="check_sub($(this));" ><?php echo $row->function_name; ?>
                                          </label>

                                          
                                        </div>
                                      </div>
                                    </div>
                                    
                                 <?php  } 
                                 ++$i;
                                ?>
                            </div>  
                          </td>

                          <?php if($i%3==0){
                           $rem = $i%3;
                           for($j=$rem;$j<3;$j++){?>
                            <td>&nbsp;</td>
                           <?php }
                            ?>
                            </tr>                        
                          <?php  } 

                        }

                        if($i%3!=0){?>
                          </tr> 
                        <?php } ?>
                         
                        
                      </tbody>
                    </table>
                 <input type="hidden" value="<?php echo $group_id; ?>" id="group_id" name="group_id" /> 

                <div class="box-footer col-sm-offset-1">
                  <button type="submit" class="btn btn-info" style="background-color: #3333ff;color: #fff;">Assign Permission</button>
                    <button type="reset" class="btn btn-default" onclick="window.history.back();">Cancel</button>
                    
                  </div><!-- /.box-footer -->
                </form>
                    
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
      
      <script type="text/javascript">
        var permision = new Array();


        <?php foreach($permission as $key => $val){ ?>
            permision.push('<?php echo $val; ?>');
        <?php } ?>

        $.each(permision, function(i, val){

          $("input[value='" + val + "']").prop('checked', true);
          
        });
        verify();

        function check_fun(control){
            if(control.prop("checked") == true){               
              var id = control.attr("id");
              $('.'+id).prop("checked", true);
            }

            else if(control.prop("checked") == false){

                var id = control.attr("id");
              $('.'+id).prop("checked", false);

            }
        }

        function check_sub(sub){
          var all = 0;

          if(sub.prop("checked") == true){

              var id = sub.attr("class");
              $('.'+id).each(function() {
                  if($(this).prop("checked") == false){
                    all = 1;
                  }

              });

              if(all==0){
                $('#'+id).prop("checked", true);
              }
              
          } else {
              var id = sub.attr("class");
              $('#'+id).prop("checked", false);
          }
          

        }

        function verify(){
$('input[type=checkbox]').each(function () {
  all = 0;
  var id =  $(this).attr("class");
              $('.'+id).each(function() {
                  if($(this).prop("checked") == false){
                    all = 1;
                  }

              });

              if(all==0){
                $('#'+id).prop("checked", true);
              }
});
}


        $('#permission').on('submit',function(e){
            var data = $(this).serialize();

            $.ajax({
              method: "POST",
              url: base_url+"permission/perm_assign",
              data: data
            })
            .done(function( msg ) {
              //alert("Permission Assigned");
                  /*var data = JSON.parse(msg);*/
                  duplicate =  data['duplicate'];
                    $('.modal-body').html('<p>Permission Assigned Successfully.</p>');
                    $('#myModal').modal('toggle');
                    $('#myModal').modal('show');   
            });

    
           e.preventDefault(); 
        });



        
        
       
      </script>