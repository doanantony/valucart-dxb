

<div class="row">
	<div class="col-md-6">
		  <div class="box box-primary">
			<div class="box-header with-border">
			 <h3 class="box-title">View Driver Details</h3>
			 <div class="box-tools pull-right">
				<button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
				<i class="fa fa-minus"></i>
				</button>
			 </div>
			</div>
		   <div class="box-body">
			  <dl> 
				 
				 	<dt>
                    Driver:  <span style="font-weight: normal !important"><?php echo $data->first_name; ?></span>
                    </dt>
                    <br/>

                    <dt>
                    Email:  <span style="font-weight: normal !important"><?php echo $data->email; ?></span>
                    </dt>
                    <br/>




                    <dt>
                    Gender:  <span style="font-weight: normal !important"><?php echo $data->gender; ?></span>
                    </dt>
                    <br/>


                     <dt>
                    Date Of Birth:  <span style="font-weight: normal !important"><?php echo $data->date_of_birth; ?></span>
                    </dt>
                    <br/>

         


	

			  </dl>
			</div><!-- /.box-body -->
		  </div><!-- /.box -->
      </div><!-- ./col -->         
	<div class="col-md-6">
	  <div class="box box-primary">
	   <div class="box-header with-border">
		 <h3 class="box-title">View Driver Details</h3>
		 <div class="box-tools pull-right">
			<button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
			<i class="fa fa-minus"></i>
			</button>
		 </div>
	  </div>
		<div class="box-body">
		  <dl>	
			 <!-- <dt>Gender</dt>
			 <dd><?php echo $data->patient_sex; ?></dd>
			 <dt>Terms</dt>
			 <dd><?php echo $data->terms;?></dd>
			 <dt>Language</dt>
			 <dd><?php echo $data->language_name;?></dd>					 
			  <dt>Insurance</dt>
			  <dd><?php echo $data->insurance_name; ?></dd>  -->				 
			 <dt>Image</dt>

			   <!-- <ul class="thumbnails gallery">
                        <li class="thumbnail" data-id="<?php echo $data->profile_photo; ?>">
                            <a style="background:url(<?php echo $data->profile_photo; ?>) no-repeat; background-size:200px; width:190px; height:190px;"
                                    href="<?php echo $data->profile_photo; ?>"></a>
                                    
                        </li>
                    </ul> -->


			  <?php if($data->profile_photo != NULL){ ?>
			  <ul class="thumbnails gallery">
                        <li class="thumbnail" data-id="<?php echo $data->profile_photo; ?>">
                            <a style="background:url(<?php echo $data->profile_photo; ?>) no-repeat; background-size:200px; width:190px; height:190px;"
                                    href="<?php echo $data->profile_photo; ?>"></a>
                                    
                        </li>
                    </ul>
			<!-- <ul class="thumbnails gallery">
                        <li class="thumbnail" data-id="<?php echo $data->profile_photo; ?>">
                            <a style="background:url(<?php echo $data->profile_photo; ?>) no-repeat; background-size:200px; width:190px; height:190px;"
                                    href="<?php echo $data->profile_photo; ?>"></a>
                                    
                        </li>
                    </ul> -->
			  <?php }
					else{
				 ?>		
				 <img src="<?php echo base_url();?>assets/images/user_avatar.jpg" width="100px" height="100px" alt="Picture Not Found" />
			  <?php } ?>
		  </dl>
		</div><!-- /.box-body -->
	  </div><!-- /.box -->
	</div><!-- ./col -->
</div>  