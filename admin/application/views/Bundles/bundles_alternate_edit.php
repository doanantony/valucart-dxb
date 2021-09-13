<style type="text/css">
  input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>
<div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Edit Bundles
          <small>Edit Bundles Alternate</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Alternate Products</a></li>
          <li class="active">Assign</li>
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
       
                         <?php
                              /*$check_id = $user_data->id;
                                if($check_id == ''){
                                    $id = 0;
                                      }else{
                                    $id = $check_id;
                            }*/
                          ?>
                          

          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                
                <h3 class="box-title">Edit Alternatives</h3>
                <div class="pull-right box-tools">
            <button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>

          <div class="box-body">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <img src="<?php echo get_bundle_image($bundle->id); ?>?w=150&h=150"  style="float: left;border: 1px solid #ccc; margin : 10px"> 
                      <h4><?php echo $bundle->name; ?></h4>
                      <h5><?php echo $bundle->cat_name; ?></h5>
                      <span style="width: 50px;overflow: hidden; text-overflow: '----';"><?php echo $out = strlen($bundle->description) > 250 ? substr($bundle->description,0,250)."..." : $bundle->description;?></span>
                    </div>
                  </div>
                </div>
              

               <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                            <th colspan="3">Bundle Products<span style="float: right;">
                            </th>
                          </tr>
                          <tr>
                            <td>No</td>
                            <td>Item</td>
                            <td>Assign</td>
                          </tr>
                        </thead>
                        <tbody id="bundle_list">
                          <?php
                          $i = 0;
                          foreach ($products as $rs) {
                          ?><tr>
                            <td><?php echo ++$i; ?></td>
                            <td><?php echo $rs->name; ?></td>
                              <td id="assign_<?php echo $rs->id; ?>">

                            
                                <table class="table table-bordered">
                                  <tbody id="alter_<?php echo $rs->id; ?>">
                                    
                                      <?php if($rs->alter_name!=''){
                                        $alter_name = explode(',', $rs->alter_name);
                                        $alter_ids = explode(',', $rs->prod_ids);
                                        $count = count($alter_ids);
                                        for ($i=0; $i < $count; $i++) { ?>
                                          <tr id="alter_item_<?php echo $alter_ids[$i]; ?>">
                                            <td><?php echo $alter_name[$i]; ?></td>
                                            <td  onclick="remove_id(<?php echo $alter_ids[$i]; ?>,<?php echo $rs->id; ?>)">X</td>
                                          </tr>
                                        <?php } 
                                      }
                                      ?>
                                  </tbody>
                                </table>
                                <button class="assign" data-id="<?php echo $rs->id; ?>">Assign</button>
                              
                              </td> 
                              
                            
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="4"><button class="btn btn-info" style="float: right;" id="post">Submit</button></td>
                          </tr>
                        </tfoot>
                          
                          
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                </div>





              </div>
               
          </div>
          
        </div>   <!-- /.row -->
      </section><!-- /.content -->
    </div>

    <div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Product List</h4>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <thead>
              <tr>
                  <td colspan="2"><input type="" placeholder= "Search Products  (Minimum 3 characters)" class="form-control" id="search_val" /></td>     
              </tr>
            </thead>
            <tbody id="prod_list">
            </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" id="add_prod" data-dismiss="modal">Assign</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <script type="text/javascript">
      var selected = '';

      var post_data = [];
      <?php

        foreach ($products as $rs) { 
          if($rs->prod_ids!=''){
            $product_ids = explode(',', $rs->prod_ids);
            ?>
            var pdt_id = <?php echo json_encode($product_ids); ?>;
          <?php } else {?> 
          var pdt_id = [];
          <?php } ?>
          post_data.push({'bundle_id':'<?php echo $rs->id; ?>', 'product_id':pdt_id, 'qty':1});
      <?php  } 
      ?>
console.log(post_data);
      $(".assign").on('click', function(ev){
        $("#search_val").val('');
        $("#prod_list").html('');
        selected = $(this).data('id');
        $('#myModal').modal({
          show: 'false'
        });
        ev.preventDefault();
      })
      $("#search_val").on('keyup', function(){
        console.log(post_data);
        var bundle_index = post_data.map(function(el) {
            return el.bundle_id
          }).indexOf(JSON.stringify(selected));
        var product_id = post_data[bundle_index].product_id;
        console.log(product_id)
        var search_val = $(this).val();
        if(search_val.length >=3){
          prod_search(product_id);
        }
      })

      function prod_search(product_id){
        var search_val = $("#search_val").val();
        
        var post_data = {'search_val':search_val,'product_id': product_id}
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Bundles/get_prod_list'); ?>',
            data: post_data,
            success: function(response){
              var html = prod_listing(JSON.parse(response));
              $('#prod_list').html(html);
            }
          });
      }

      function qty_change(event, id) {
        new_id = JSON.stringify(id);
        console.log(event.value, id);
        if(event.value > 1){
          var index = post_data.map(function(el) {
            return el.bundle_id
          }).indexOf(new_id)
          console.log(index);
          
          post_data[index].qty = event.value;
          console.log(post_data[index]);
        }
        
      }

      function prod_listing(data) {
        var html = '';
        var state = false;
        var checked = '';
        data.forEach(function(element) {
          state = false;
          html += '<tr><td colspan="2"><div class="col-md-2"><input type="checkbox" class="prod_id" value="'+element.id+'" name="product" data-text="'+element.name+'" /></div><div class="col-md-10">'+element.name+'</div></td></tr>';
        })
        return html;
      }

      $("#add_prod").on('click',function(){
        console.log(selected)
        console.log(post_data);
        var favorite = [];
        var alter_text = [];
        var html ='';
        var index = post_data.map(function(el) {
            return el.bundle_id
          }).indexOf(JSON.stringify(selected))
        $.each($("input[name='product']:checked"), function(){ 
            post_data[index].product_id.push($(this).val());
            alter_text.push($(this).data('text'));
            var pdt_html = $(this).data('text');
            var id = $(this).val();
            html +='<tr id="alter_item_'+id+'"><td>'+pdt_html+'</td><td onclick="remove_id('+id+','+selected+')">X</td></tr>';
        });
        console.log("#alter_"+selected);

        $("#alter_"+selected).append(html);
        
         /* console.log(index);
          console.log(post_data[index]);
          post_data[index].product_id
          post_data[index].product_id = favorite;*/
      })

      $("#post").on('click', function(){
        var items = post_data;
        console.log(items);
        var item_data = {"items": post_data};
        console.log(post_data);
          $.ajax({
            type: "POST",
            url: '<?php echo base_url('Bundles/save_alternate'); ?>',
            data: item_data,
            success: function(response){
              if(response == 1){
                window.location.href = '<?php echo base_url('Bundles/create'); ?>'
              }
            }
          });
      })

      function remove_id(id, selected) {
        console.log(id, selected);
        var bundle_index = post_data.map(function(el) {
            return el.bundle_id
          }).indexOf(JSON.stringify(selected))
        $("#alter_item_"+id).remove();
        var index = post_data[bundle_index].product_id.map(function(el) {
            return el
          }).indexOf(JSON.stringify(id));
         post_data[bundle_index].product_id.splice(index, 1);
         console.log(post_data[bundle_index].product_id);

      }
    </script>