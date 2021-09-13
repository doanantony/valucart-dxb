
<div class="row">


		<div class="col-md-6">
	  <div class="box box-primary">
	   <div class="box-header with-border">
		 <h3 class="box-title"> Landscape Banner Image</h3>
		 <div class="box-tools pull-right">
			<button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
			<i class="fa fa-minus"></i>
			</button>
		 </div>
	  </div>
		<div class="box-body">
		  <dl>	
			 
			 <dt></dt>

			 <div style="text-align:center;width:100%">
			 	
			  <img src="<?php echo get_banner_landimage($data->id); ?>" width="400px" height="200px" alt="Picture Not Found" />
			 </div>

			 
		  </dl>
		</div><!-- /.box-body -->
	  </div><!-- /.box -->
	</div><!-- ./col -->


	<div class="col-md-6">
	  <div class="box box-primary">
	   <div class="box-header with-border">
		 <h3 class="box-title"> Portrait Banner Image</h3>
		 <div class="box-tools pull-right">
			<button class="btn btn-info btn-sm" title="" data-toggle="tooltip" data-widget="collapse" data-original-title="Collapse">
			<i class="fa fa-minus"></i>
			</button>
		 </div>
	  </div>
		<div class="box-body">
		  <dl>	
			 
			 <dt></dt>

			 <div style="text-align:center;width:100%">
			  <img src="<?php echo get_banner_portimage($data->id); ?>" width="400px" height="200px" alt="Picture Not Found" />
			 </div>



		  </dl>
		</div><!-- /.box-body -->
	  </div><!-- /.box -->
	</div><!-- ./col -->
</div>  