<section class="content">
   <div class="box box-info box-solid">
      <!-- /.box-header -->
      <div class="box-body no-padding">
         <table class="table table-striped">
            <tr>
               <th style="width: 10px"></th>
               <th>Vendor</th>
               <th style="width: 40px"><?php echo "Description. \n";  ?></th>
            </tr>
            <tr>
               <td>1.</td>
               <td>Name</td>
               <td><span> <?php echo $data->name; ?></span></td>
            </tr>
            <tr>
               <td>2.</td>
               <td>Email</td>
               <td><span> <?php echo $data->email; ?></span></td>
            </tr>
            <tr>
               <td>3.</td>
               <td>Total Products</td>
               <td><span class="badge bg-green"><?php echo total_vendor_products($data->id); ?></span></td>
            </tr>
            <tr>
               <td>4.</td>
               <td>Image</td>
               <?php if( $data->image){?>
               <td><img src="<?php echo $data->image; ?>" width="100px" height="100px"  /></span></td>
               <?php
                  }
                  else
                  {
                  ?>
               <td><img src="<?php echo base_url(); ?>assets/images/noimage.png" width="100px" height="100px"  /></span></td>
               <?php
                  }
                  ?>
            </tr>
         </table>
      </div>
      <!-- /.box-body -->
   </div>
</section>