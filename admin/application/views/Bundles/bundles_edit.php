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
          <small>Edit Existing Bundles</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Manage Bundles</a></li>
          <li class="active">Create</li>
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
                
                <h3 class="box-title">Edit Bundles Products</h3>
                <div class="pull-right box-tools">
            <button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
            <i class="fa fa-minus"></i>
            </button>
          </div>




              </div>

              <div class="box-body">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <img src="<?php echo get_bundle_image($bundle->id); ?>?w=150&h=150" style="float: left;border: 1px solid #ccc; margin : 10px"> 
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
                            <th colspan="4">Bundle Items<span style="float: right;"><button class="btn btn-info" type="button" id="add_item">ADD ITEM</button></span>
<input type="hidden" name="bundle_id" id="bundle_id" value="<?php echo $id; ?>">
                            </th>
                          </tr>
                          <tr>
                            <td>Item Name</td>
                            <td>Price</td>
                            <td>Qty</td>
                            <td>Action</td>
                          </tr>
                        </thead>
                        <tbody id="bundle_list">
                          <?php
                            $value_total = 0;
                            $bundle_price = 0;
                            foreach($bundle_prod as $rs) { ?>
                              <tr id="item_<?php echo $rs->product_id; ?>">
                                <td><?php echo $rs->name; ?></td>
                                <td><?php echo $rs->valucart_price; ?></td>
                                <td>
                                  <input type="number" name="qty[]" value="<?php echo $rs->quantity; ?>" min="1" max="10" onblur="qty_change(this,<?php echo $rs->product_id; ?>)" />
                                </td>
                                <td><button class="remove" data-id="<?php echo $rs->product_id; ?>">&times;</button></td>
                                <input type="hidden" value="<?php echo $rs->product_id; ?>" name="ids[]" />
                                <input type="hidden" value="<?php echo $rs->id; ?>" name="bundle_ids[]" />
                              </tr>                              
                           <?php $value_total += ($rs->quantity*$rs->valucart_price);} ?>
                           <tr>
                                <td colspan="3" style="text-align:right">Grand Total</td><td id="total_charge">AED <?php echo $value_total; ?></td></tr><tr><td colspan="3" style="text-align:right">Bundle Price</td><td><input type="text" id="bundle_price" name="bundle_price" value="<?php echo $bundle_price; ?>" /><br/><span id="bundle_price_error" style="color: red; display:none">Please input a valid bundle price</span></td></tr><tr><td colspan="4" style="text-align:right"><button type="button" id="save" class="btn btn-info">Save</button></td></tr>
                          
                        </tbody>
                          
                          
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                </div>



              


              


            </div><!-- /.box -->
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
                  <td>
                    <select class="form-control" id="cate_id">
                      <option disabled="" selected="selected">Select Category</option>
                      <?php 
                      foreach ($categories as $cat) { ?>
                        <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td>
                    <select class="form-control" id="sub_cat">
                      <option disabled="" selected="selected">Select Subcategory</option>
                    </select>
                  </td>                  
              </tr>
              <tr>
                  <td colspan="2"><input type="" class="form-control" id="search_val" /></td>     
              </tr>
            </thead>
            <tbody id="prod_list">
              <?php foreach ($products as $rs) { ?>
              <tr>
                  <td colspan="2"><div class="col-md-2"><input type="checkbox" class="prod_id" value="<?php echo $rs->id; ?>" /></div><div class="col-md-10"><?php echo $rs->name; ?></div></td>     
              </tr>
            <?php } ?>
            </tbody>
              
              
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" id="add_prod" data-dismiss="modal">Post</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <script type="text/javascript">
      var bundle_item = [];
      var item_list = [];
      var prod = '<?php echo json_encode($products); ?>';
      click_process();

      var bundprod = '<?php echo json_encode($bundle_prod); ?>';
      console.log(bundprod);
      bundprod = JSON.parse(bundprod);
      bundprod.forEach(function(element){
         bundle_item.push(element.product_id);
      })



      prod = JSON.parse(prod);
      console.log(prod);
      $("#add_item").on('click', function(ev){
        var html = prod_listing(prod);
        $("#cate_id").prop('selectedIndex', 0);
        $("#sub_cat").prop('selectedIndex', 0);
        $('#prod_list').html(html);

        $('#myModal').modal({
          show: 'false'
      }); 

        prod_list();


      /*var data = $(this).serializeObject();
      json_data = JSON.stringify(data);
      $("#results").text(json_data); 
      $(".modal-body").text(json_data); */

      // $("#results").text(data);

      ev.preventDefault();
      })


      function prod_listing(data) {
        console.log(data);
        var html = '';
        var state = false;
        var checked = '';
        data.forEach(function(element) {
          state = false;
              state = isInArray(element.id, bundle_item);
              if(state === true){
                checked = 'checked';
              } else {
                checked = '';
              }             
              
              html += '<tr><td colspan="2"><div class="col-md-2"><input type="checkbox" class="prod_id" value="'+element.id+'" '+checked+' /></div><div class="col-md-10">'+element.name+'</div></td></tr>';


        })
        return html;
      }


      function add(id) {
        return index = bundprod.findIndex(function(person) {
          return person.product_id == id
        });
      }

      function isInArray(value, array) {
        return array.indexOf(value) > -1;
      }

      function prod_list(){
        $(".prod_id").on('click', function(){
          item = $(this).val();
          if($(this).is(':checked')) {
            bundle_item.push(item);
          } else {
            var index = bundle_item.map(function(el) {
              return el
            }).indexOf(item)
            bundle_item.splice(index, 1);
          }
          console.log(bundle_item);
        })
        
      }

      $("#add_prod").on('click', function(){
        var prod_list_bundle = '';
        var value_total = 0;
        if(bundle_item.length > 0){
          var ids = JSON.stringify(bundle_item);
           $.ajax({
            type: "POST",
            url: '<?php echo base_url('Bundles/get_products'); ?>',
            data: 'ids='+ids,
            success: function(response){
              var obj = JSON.parse(response);
              bundle_add_list(obj);
              item_list = obj;

                //$( "#result" ).empty().append( response );
            }
          });
        }        
      })

      function bundle_add_list(items){
        var prod_list_bundle = '';
        var value_total = 0;
        items.forEach(function(element) {
         state = isInArray(element.id, bundle_item);
         if(state === true){
          checked = 'checked';
        } else {
          checked = '';
        }
        var qty = 1;
        var new_attach = '';

         index = add(element.id);
            if(index >= 0){
              extra = bundprod[index];
              qty = extra.quantity;
              new_attach = '<input type="hidden" value="'+extra.id+'" name="bundle_ids[]" />';
            } else {
               new_attach = '<input type="hidden" value="" name="bundle_ids[]" />';
            }

        prod_list_bundle += '<tr id="item_'+element.id+'"><td>'+element.name+'</td><td>'+element.valucart_price+'</td><td><input type="number" name="qty[]" value="'+qty+'" min="1" max="10" onblur="qty_change(this,'+element.id+')" /></td><td><button class="remove" data-id="'+element.id+'">&times;</button></td><input type="hidden" value="'+element.id+'" name="ids[]" />'+new_attach+'</tr>';


        value_total += parseInt(element.valucart_price);
      });

      

      prod_list_bundle +='<tr><td colspan="3" style="text-align:right">Grand Total</td><td id="total_charge">AED '+value_total+'</td></tr><tr><td colspan="3" style="text-align:right">Bundle Price</td><td><input type="text" id="bundle_price" name="bundle_price" /><br/><span id="bundle_price_error" style="color: red; display:none">Please input a valid bundle price</span></td></tr><tr><td colspan="4" style="text-align:right"><button type="button" id="save" class="btn btn-info">Save</button></td></tr>';

      $('#bundle_list').html(prod_list_bundle);
      click_process();
      //cal_total();
    }

    function click_process() {

      $('.remove').on('click', function(){
        var id = $(this).data('id');
        console.log(id);
        $('#item_'+id).remove();

        new_id = JSON.stringify(id);
        var index = bundle_item.indexOf(new_id);
        if(index !==-1){
          bundle_item.splice(index, 1);
          console.log(bundle_item);
        }

        console.log(item_list);

        var index = item_list.map(function(el) {
          return el.id
        }).indexOf(id)
        item_list.splice(index, 1);
        cal_total();
      })

      $("#save").on('click', function(){
        var items = item_list;
        var bundle_id = $("#bundle_id").val();
        var bundle_price = $("#bundle_price").val();
        var bundle_ids = $("input[name='bundle_ids[]']").map(function(){return $(this).val();}).get();
        var product_ids = $("input[name='ids[]']").map(function(){return $(this).val();}).get();
        var qty = $("input[name='qty[]']").map(function(){return $(this).val();}).get();
       
        var post_data = {"bundle_ids": bundle_ids,"product_ids": product_ids,"qty": qty, "bundle_id": bundle_id, "bundle_price": bundle_price};

        if(bundle_price <= 0 ){
          $("#bundle_price_error").show();
        } else {
          $("#bundle_price_error").hide();
          $.ajax({
            type: "POST",
            url: '<?php echo base_url('Bundles/save_bundle'); ?>',
            data: post_data,
            success: function(response){
              if(response == 1){
                window.location.href = '<?php echo base_url('Bundles/edit_alternate/'); ?>'+bundle_id
              }
            }
          });        
        }
      })

      
    }

      function qty_change(event, id) {
        console.log(item_list);
        new_id = JSON.stringify(id);
        console.log(event.value, id);
        if(event.value > 1){
          var index = item_list.map(function(el) {
            return el.id
          }).indexOf(new_id)
          console.log(index);
          console.log(item_list[index]);
          item_list[index].qty = event.value;
          cal_total()
        }
        
      }

      function cal_total(){
        var total_price = 0;
        var price = 0;
        item_list.forEach(function(element) {
          console.log(element.valucart_price,element.qty);
          price = (element.valucart_price * element.qty);
          console.log(price);
          total_price +=parseInt(price);
        })
        console.log(total_price);
        $("#total_charge").html('AED '+total_price);
      }

      $("#cate_id").on('change', function(){
        var id = $(this).val();
        $('#sub_cat').empty();
        sub_cat_list();
        prod_search();
      });

      $("#sub_cat").on('change', function(){
        prod_search();
      });

      $("#search_val").on('keyup', function(){
        var search_val = $(this).val();
        if(search_val.length >=3){
          prod_search();
        }
      })

      function prod_search(){
        var cat_id = $("#cate_id").val();
        var sub_cat = $("#sub_cat").val();
        var search_val = $("#search_val").val();
        var post_data = {'cat_id':cat_id, 'sub_id':sub_cat,'search_val':search_val}
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Bundles/get_prod_list'); ?>',
            data: post_data,
            success: function(response){
              var html = prod_listing(JSON.parse(response));
              $('#prod_list').html(html);
              prod_list();
            }
          });
      }

      function sub_cat_list(){
        var cat_id = $("#cate_id").val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Bundles/get_sub_list'); ?>',
            data: 'cat_id='+cat_id,
            success: function(response){
              var sub_list = JSON.parse(response);
              console.log(sub_list)
              $("#sub_cat").append($('<option disabled selected="selected">Select Subcategory</select>'));
              sub_list.forEach(function(element){
                $("#sub_cat").append($('<option></option>').attr("value",element.id).text(element.name));
                //html += '<option value="'+element.id+'">'+element.name+'</option>';
              })
              //console.log(html);
              //$("#sub_cat").append(html);
            }
          });
      }
    </script>