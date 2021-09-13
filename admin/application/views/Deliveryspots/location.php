<link rel="stylesheet" href="<?php echo base_url('assets/css/morris.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/jvectormap/jquery-jvectormap.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/js/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/morris.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/raphael.min.js'); ?>"></script>
<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
<link rel="manifest" href="manifest.json">
<style type="text/css">
   .general_div {
   cursor: pointer;
   }
</style>


<div class="content-wrapper" >
   <!-- Content Header (Page header) -->
   <section class="content-header">
       <h1><?php echo $page_data->function_title; ?>
            <small><?php echo $page_data->function_small; ?></small>
          </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Manage Segment</a></li>
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
                   <section>
        <div class="col-xs-6"> 
        <div class="box-header with-border">
                        <h3 class="box-title">Delivery Spots</h3>
                    </div>              
           <div class="box box-info box-solid">
               
               <div class="box-body">
                  <div class="table-responsive">
                     <table class="table no-margin">
                        <thead>
                           <tr>
                              <th>Location</th>
                              <th>Range</th>
                               <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              foreach($location as $spots) {
                                    
                              ?>
                           <tr>
                              <td class="hidden"><?php echo $spots->id; ?></td>
                              <td class="center"><?php echo $spots->name; ?></td>
                              <td class="center"><?php echo $spots->range; ?>Km</td>

                              <td><span class="center label  <?php if($spots->published == '1')
                                 {
                                 echo "label label-info";
                                 }elseif($spots->published == '0')
                                 { 
                                 echo "label label-warning"; 
                                 }elseif($spots->published == '2')
                                 { 
                                 echo "label label-success"; 
                                 }else{
                                 echo "label label-danger";   
                                 }
                                 ?>"><?php if($spots->published == '1')
                                 {
                                     echo "Open for Delivery";
                                 }elseif($spots->published == '2'){
                                     echo "Closed";
                                 }elseif($spots->published == '3'){
                                      echo "Halted";
                                 }
                                 else
                                 { 
                                 echo "Deleted"; 
                                 }
                                 ?></span>                                                         
                              </td>
                            
                           </tr>
                           <?php
                              }
                              ?>
                        </tbody>
                     </table>
                  </div>
               </div>
               
            </div>
            </div>
                        
             <div class="col-sm-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Online Terminal Report</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="pad">

                                    <!--Map chart here-->
                                    <div id="world-map-markers" style="height: 430px;"></div>

                                    <!--Map end here-->
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>
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




<script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script>
<!-- <script src="https://adminlte.io/themes/AdminLTE/bower_components/chart.js/Chart.js"></script>
 -->
<script type="text/javascript">
  
   refresh_home();        
   
   setInterval(
       function(){ 
           refresh_home();
       }, 5000
   );
   
   function refresh_home(){
       $.ajax({
           type: "POST",
           url: '<?php echo base_url("Deliveryspots/spots"); ?>',
           success: function(response) {
              
               response = JSON.parse(response);
               called_world_map(response.terminal)
               //resolve(response)
           },
           error: function(response) {
               //reject(new Error("Script load error: " + response));
           },
           async: false
           });
       }


       function called_world_map(data){
        var makers = [];
        $("#world-map-markers").empty();
        $.each(data,function(index,value){
            var latlng = [value.latitude,value.longitude];
            console.log(latlng);
            var name = value.name;
            var scoper = {latLng: latlng, name: name};
            makers.push(scoper);
        })



        $('#world-map-markers').vectorMap({
            map              : 'world_mill_en',
            normalizeFunction: 'polynomial',
            hoverOpacity     : 0.7,
            hoverColor       : false,
            backgroundColor  : 'transparent',
            regionStyle      : {
              initial      : {
                fill            : 'rgba(210, 214, 222, 1)',
                'fill-opacity'  : 1,
                stroke          : 'none',
                'stroke-width'  : 0,
                'stroke-opacity': 1
              },
              hover        : {
                'fill-opacity': 0.7,
                cursor        : 'pointer'
              },
              selected     : {
                fill: 'yellow'
              },
              selectedHover: {}
            },
            markerStyle      : {
              initial: {
                fill  : '#00a65a',
                stroke: '#111'
              }
            },
            markers: makers
        });
    }
   




</script>