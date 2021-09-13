<div class="content-wrapper" >

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

 <div class="col-xs-12">
  <div class="box box" >

    <div class="box-header with-border">

     <ul class="nav nav-tabs">

      <li class="active"><a href="#activity" data-toggle="tab"><b>PRODUCTS</b></a></li>
      <li><a href="#timeline" data-toggle="tab"><b>BUNDLES</b></a></li>
      <button class="btn bg-purple btn-flat margin pull-right show-cartdetails" href="javascript:void(0);"  data-id="1" data-toggle="modal" data-target="#exampleModal" 
      onclick="javascript:opencart()" >
      <span ><i class='fa  fa-cart-plus'></i>
        Cart ( <span class="cartcount"><?php echo count($this->cart->contents());  ?> Items</span> )
      </span>
    </button>

  </ul>



</div>
<div class="box-body">

  <div class="col-md-9">
    <div class="nav-tabs-custom">

      <div class="tab-content">
        <div class="active tab-pane" id="activity">
          <!-- Post -->
          <table class="table table-bordered table-striped dataTable-custom" data-ajax="admincart/get_all_products" data-ordering="true" id="activity_table">
           <thead>

            <tr>
             <th>Id</th>
             <th>Name</th>
             <th>Valu Price</th>
             <th>Actions</th>
           </tr>
         </thead> 
         <tbody>

         </tbody>
         <tfoot>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Valu Price</th>
            <th>Actions</th>
          </tr>
        </tfoot>
      </table>

    </div>
    <div class="tab-pane" id="timeline">
     <div class="box-body">
       <table class="table table-bordered table-striped datatable">
         <thead>
          <tr>
           <th class="hidden">ID</th>
           <th>Name</th>
           <th>Category</th>
           <th>Valucart Price</th>
           
           <th>Action</th>

         </tr>
       </thead> 
       <tbody>
        <?php
        foreach($data as $bundles) {

         ?>
         <tr>
           <td class="hidden"><?php echo $bundles->id; ?></td>
           <td class="center"><?php echo $bundles->name; ?></td>
           <td class="center"><?php echo get_bundlecat_name($bundles->category_id); ?></td>   
           <td class="center"><?php echo $bundles->price; ?></td> 


           <td class="center">



             <p hidden="" class="name<?php echo $bundles->id; ?>" '="" rel="<?php echo $bundles->id; ?>"><?php echo $bundles->name; ?></p>
             <p class="price<?php echo $bundles->id; ?>" '="" rel="<?php echo $bundles->price; ?>"></p>

             <p class="pro<?php echo $bundles->id; ?>" '="" rel="bundle"></p>


             <a class="btn btn-primary" 
             onclick="javascript:addtocart(<?php echo $bundles->id; ?>)"> <i class='fa fa-fw fa-check'></i> Add to Cart</a>




           </tr>
           <?php
         }
         ?>
       </tbody>
       <tfoot>
        <tr>
         <th class="hidden">ID</th>
         <th>Name</th>
         <th>Category</th>
         <th>Valucart Price</th>

         <th>Action</th>

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
</div>

</div>

</section>

</div>


<script type="text/javascript">




  function addtocart(p_id)
  { 


    var price = $('.price'+p_id).attr('rel');
    var image = $('.image'+p_id).attr('rel');
    var name  = $('.name'+p_id).text();
    var id    = $('.name'+p_id).attr('rel');
    var pro    = $('.pro'+p_id).attr('rel');
    console.log(pro);
    $.ajax({
      type: "POST",
      url: "<?php echo site_url('admincart/add');?>",
      data: "id="+id+"&image="+image+"&name="+name+"&price="+price+"&type="+pro,
      success: function (response) {
       $(".cartcount").text(response);
      
     }
   });
  }


  function opencart()
  {
    $.ajax({
      type: "POST",
      url: "<?php echo site_url('admincart/opencart');?>",
      data: "",
      success: function (response) {
                //  $(".displaycontent").html(response);
                $('#popup-patientModal .modal-patientbody').html(response);
              }
            });
  }

</script>


<div class="modal fade modal-wides" id="popup-patientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" align="center"> Cart Details</h4>
  </div>
  <div class="modal-patientbody">
  </div>
  <div class="business_info">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
  </div>
</div>
</div>
</div>