          
<section class="content">
  <div class="box box-info box-solid">

    <div class="box-body no-padding">

      <table class="table table-striped">
        <tbody>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Delete</th>
          </tr>
          <?php 
          if(isset($cart) && is_array($cart) && count($cart)){
          // echo "<pre>";
          // print_r($cart);
            $i=1;
            foreach ($cart as $key => $data) { 
              ?>
              <tr class="item first rowid<?php echo $data['rowid'] ?>">

                <td class="name"><?php echo $data['name'] ?></td>
                <td class="price">AED 
                  <span class="price<?php echo $data['rowid'] ?>"><?php echo $data['price'] ?>
                </span>
              </td>
              <td class="qnt-count">
                <input style="height:24px;width:58px;" class="quantity qty<?php echo $data['rowid'] ?> form-control" 
                type="number" min="1" value="<?php echo $data['qty'] ?>">
                <a><span class="Update" 
                onclick="javascript:updateproduct('<?php echo $data['rowid'] ?>')">Update</span></a>
              </td>
              <td class="total">AED <span class="subtotal subtotal<?php echo $data['rowid'] ?>">
                <?php echo $data['subtotal'] ?></span></td>
                <td class="delete"><i class="fa fa-fw fa-trash" style="color:red;"
                 onclick="javascript:deleteproduct('<?php echo $data['rowid'] ?>')"></i></td>
               </tr>

               <?php
               $i++;
             } }
             ?>

             <tr class="item">
              <td class="thumb" colspan="4" align="right">&nbsp;</td>
              <td class="">AED <span class="grandtotal">0</span> </td>
              <td>&nbsp;</td>
            </tr>
          </tbody></table>

          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="javascript:deleteproduct('all')">
            Clear Cart</button>
            <a href="<?php echo site_url('admincart/billing_view') ?>">
              <button type="button" class="btn btn-primary">Place Order</button>
            </a>
          </div>



        </div>
      </div>
    </section>





    <script type="text/javascript">
      function deleteproduct(rowid)
      {
        var answer = confirm ("Are you sure you want to delete?");
        if (answer)
        {
          $.ajax({
            type: "POST",
            url: "<?php echo site_url('admincart/remove');?>",
            data: "rowid="+rowid,
            success: function (response) {
              $(".rowid"+rowid).remove(".rowid"+rowid); 
              $(".cartcount").text(response);  
              var total = 0;
              $('.subtotal').each(function(){
                total += parseInt($(this).text());
                $('.grandtotal').text(total);
              });              
            }
          });
        }
      }

      var total = 0;
      $('.subtotal').each(function(){
        total += parseInt($(this).text());
        $('.grandtotal').text(total);
      });


      function updateproduct(rowid)
      {
        var qty = $('.qty'+rowid).val();
        var price = $('.price'+rowid).text();
        var subtotal = $('.subtotal'+rowid).text();
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('admincart/update_cart');?>",
          data: "rowid="+rowid+"&qty="+qty+"&price="+price+"&subtotal="+subtotal,
          success: function (response) {
            $('.subtotal'+rowid).text(response);
            var total = 0;
            $('.subtotal').each(function(){
              total += parseInt($(this).text());
              $('.grandtotal').text(total);
            });     
          }
        });
      }


    </script>