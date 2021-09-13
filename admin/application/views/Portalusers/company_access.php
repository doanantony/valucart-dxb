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
        <h4 class="modal-title">Company Access</h4>
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
                        <tr><td colspan="3"><input type="checkbox" name="all" id="all" onclick="check_fun($(this))">&nbsp;All</td></tr>
                        <?php
                        $i=0;
                        $k=0;
                        foreach ($company_list as $rs) {

                          if($i%3==0){?>
                            <tr>                        
                          <?php  } ?>
                          
                          <td>
                            <div class="box-body" style="padding-left: 100px">
                             <label><input type="checkbox" id="companie_<?php echo $rs->id; ?>" class="company" name="companies[]" value="<?php echo $rs->id; ?>">&nbsp;<?php echo ucwords($rs->company_name); ?></label>
                            </div>  
                          </td>

                          <?php 
                          ++$i;
                          if($i%3==0){                           
                            ?>
                            </tr>                        
                          <?php  } 



                        }

                        if($i%3!=0){?>
                          </tr> 
                        <?php } ?>
                         
                        
                      </tbody>
                    </table>
                 <input type="hidden" value="<?php echo $user->id; ?>" id="user_id" name="user_id" /> 

                <div class="box-footer col-sm-offset-1">
                  <button type="submit" class="btn btn-info" style="background-color: #3333ff;color: #fff;">Assign</button>
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


        <?php foreach($companies as $key => $val){ ?>
            permision.push('<?php echo $val; ?>');
        <?php } ?>

        $.each(permision, function(i, val){

          $("input[value='" + val + "']").prop('checked', true);
          
        });
        verify();

        function check_fun(control){
            if(control.prop("checked") == true){ 
              $('.company').prop("checked", true);
            }

            else if(control.prop("checked") == false){
              $('.company').prop("checked", false);

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
              url: base_url+"portalusers/comp_assign",
              data: data
            })
            .done(function( msg ) {
              //alert("Permission Assigned");
                  /*var data = JSON.parse(msg);*/
                  duplicate =  data['duplicate'];
                    $('.modal-body').html('<p>Companies assigned successfully</p>');
                    $('#myModal').modal('toggle');
                    $('#myModal').modal('show');   
            });

    
           e.preventDefault(); 
        });



        
        
       
      </script>