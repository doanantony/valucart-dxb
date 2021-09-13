          
		  <section class="content">
          <div class="box box-info box-solid">
            <div class="box-body no-padding">
              <table class="table table-striped">
                <tr>
                  <td>1.</td>
                  <td>Name</td>
                  <td><span> <?php echo $data['name']; ?></span></td>
                </tr>
                <tr>
                  <td>2.</td>
                  <td>SKU</td>
                  <td><span> <?php echo $data['sku']; ?></span></td>
                </tr>
                <tr>
                  <td>3.</td>
                  <td>Department</td>
                  <td><span> <?php echo $data['department']['name']; ?></span></td>
                </tr>
                <tr>
                  <td>4.</td>
                  <td>Category</td>
                  <td><span> <?php echo $data['category']['name']; ?></span></td>
                </tr>
                <tr>
                  <td>5.</td>
                  <td>Sub Category</td>
                  <td><span> <?php echo $data['subcategory']['name']; ?></span></td>
                </tr>
                <tr>
                  <td>6.</td>
                  <td>Description</td>
                  <td><span> <?php echo $data['description']; ?></span></td>
                </tr>
                <tr>
                  <td>7.</td>
                  <td>Pcg Qty</td>
                  <td><span> <?php echo $data['packaging_quantity']; ?></span></td>
                </tr>
                <tr>
                  <td>8.</td>
                  <td>MSP</td>
                  <td><span> <?php echo $data['maximum_selling_price']; ?></span></td>
                </tr>
                <tr>
                  <td>9.</td>
                  <td>Valucart Price</td>
                  <td><span> <?php echo $data['valucart_price']; ?></span></td>
                </tr>
                <tr>
                  <td>10.</td>
                  <td>Inventory</td>
                  <td><span> <?php echo $data['inventory']; ?></span></td>
                </tr>
                <tr>
                  <td>11.</td>
                  <td>Pkg Qty Unit</td>
                  <td><span> <?php echo $data['packaging_quantity_unit']['name']; ?></span></td>
                </tr>
               	<tr>
                  <td>12.</td>
                  <td>Thumb Image</td>
                  <?php if( $data['thumbnail']){?>
                        <td><img src=" <?php echo $data['thumbnail']; ?>" width="100px" height="100px"  />
                        </td>        
                        <?php
                          }else
                              {
                          ?>
                        <td><img src="<?php echo base_url(); ?>assets/images/noimage.png" width="100px" height="100px"  /></td>
                        <?php
                            }
                        ?>
                </tr>


                <tr>
                  <td>13.</td>
                  <td>Images</td>
                  <td><?php if( $data['images']){?>
                              <?php
                           foreach($data['images'] as $images) {
                           ?>
                  <img src=" <?php echo $images; ?>" width="100px" height="100px"  />
                  
                    <?php
                          }
                    ?>
                    </td>   
                    <?php
                        }
                      else
                        {
                    ?>
                  <td><img src="<?php echo base_url(); ?>assets/images/noimage.png" width="100px" height="100px"  /></td>
                              <?php
                                 }
                                 ?>
                </tr>





              </table>
              
            </div>

            <!-- /.box-body -->
          </div>

      </section>