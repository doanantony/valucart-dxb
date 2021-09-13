    <script>
    
    base_url = "<?php echo base_url(); ?>";
    
    </script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-timepicker.min.css">
    <?php 
    if(strtolower($this->router->fetch_class())=='Bulknotifications'){?>
        <script src="<?php echo base_url('assets/vendor/jquery/jquery-3.2.1.min.js'); ?>"></script>
        
       
    <?php } ?>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.js"></script>

    <!-- jQuery 2.1.4 -->
    
    <!-- Bootstrap 3.3.5 -->
    
    <script src="<?php echo base_url(); ?>assets/js/pace.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url(); ?>assets/js/select2.full.min.js"></script>
    
    <!-- DataTables -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.min.js"></script>
    
    <!-- FastClick 
    <script src="../../plugins/fastclick/fastclick.min.js"></script>-->
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/parsley.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/custom-script.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/backend-script.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/vendor/emoji-picker/lib/js/config.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/emoji-picker/lib/js/util.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/emoji-picker/lib/js/jquery.emojiarea.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/emoji-picker/lib/js/emoji-picker.js"></script>


    <!-----location ---->
    <script src="http://maps.googleapis.com/maps/api/js?key=<?php echo get_key(); ?>&libraries=places" type="text/javascript"></script> 



    <script>
      $(function() {
        $(".select2").select2({
            placeholder: 'Select'
        });

         $('.datatable').DataTable({
            "ordering" : $(this).data("ordering"),
            "order": [[ 0, "desc" ]]
        });

         
        var schedulingGrid = $('.dataTable-custom').DataTable({
            /*"ordering" : $(this).data("ordering"),
            "order": [[ 0, "desc" ]],*/
            "processing": true,
            "serverSide": true,
            "ajax": "<?php echo base_url(); ?>" + $(this).data("ajax"),
            "iDisplayLength": 10,
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }]
        });

        var table = $('#activity_table').DataTable();
        var data = new Array();

        $('.select').on('change', function () {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            schedulingGrid.columns(i).search(v).draw();            
        })

        $('input[type=text]').on('keyup', function () {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            schedulingGrid.columns(i).search(v).draw();            
        })


        $(".datatable").DataTable();     


       


        /*$("#activity_table").DataTable().columns().every( function () {
            console.log("called");
            var that = this; 
            $('input').on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            });

            /*$('select').on( 'change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            });
        });*/

        


    });

</script>
<!--     function doconfirm() {
        var a = confirm("Are sure to delete this record?");
        return a;
    } -->
    </script>

    <?php if(strtolower($this->router->fetch_class())=='transaction_bck' && strtolower($this->router->fetch_method())=='transaction_bck')
    {?>
        <script type="text/javascript" src=""></script>
    <?php }

    if(strtolower($this->router->fetch_class())=='transaction'){?>
        <script type="text/javascript" src="<?php echo base_url('assets/js/transaction.js'); ?>"></script>
    <?php }

    if(strtolower($this->router->fetch_class())=='vendorsettlements'){?>
        <script type="text/javascript" src="<?php echo base_url('assets/js/settlement.js'); ?>"></script>
    <?php }

    if(strtolower($this->router->fetch_class())=='settlement'){?>
        <script type="text/javascript" src="<?php echo base_url('assets/js/settlement.js'); ?>"></script>
    <?php }

    if(strtolower($this->router->fetch_class())=='vendors'){?>
        <script type="text/javascript" src="<?php echo base_url('assets/js/settlement.js'); ?>"></script>
    <?php }

    
   /* if(strtolower($this->router->fetch_class())=='route' && strtolower($this->router->fetch_method())=='create'){?>
        <script src="<?php echo base_url('assets/js/jquery-bundle.js'); ?>"></script>
        <script src="https://maps.googleapis.com/maps/api/js?libraries=drawing,places"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/map.js'); ?>"></script>
    <?php }*/

    ?>
    <script type="text/javascript">
    $(".timepicker").timepicker();


    function doconfirm()
    {
        job=confirm("Are you sure to delete permanently?");
         if(job!=true)
        {
            return false;
        }
    }

    function productstatus_confirm($status)
    {   
        if($status == 1){

            job=confirm("This product will get cleared from all the customers cart! Are you sure to unpublish this product?");

        }else{

            job=confirm("Are you sure to publish this product?");
        }
        
         if(job!=true)
        {
            return false;
        }
    }



    function doconfirm_coupon()
    {
        job=confirm("Are you sure to remove this user permanently?");
         if(job!=true)
        {
            return false;
        }
    }



    
</script>
   

 <script>
            var autocomplete = new google.maps.places.Autocomplete($("#location")[0], {});

            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();

                var label_address  = place.adr_address;

                //label_address.find('span').attr('class="country-name"', '');

var lat = place.geometry.location.lat();
var lng = place.geometry.location.lng();
$('#lat').val(lat);
$('#lng').val(lng);
var latlng;
latlng = new google.maps.LatLng(lat, lng);

new google.maps.Geocoder().geocode({'latLng' : latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {
            var country = null, countryCode = null, city = null, cityAlt = null;
            var c, lc, component;
            for (var r = 0, rl = results.length; r < rl; r += 1) {
                var result = results[r];
                if (!country && result.types[0] === 'country') {
                    country = result.address_components[0].long_name;
                    countryCode = result.address_components[0].short_name;
                }

                if (country) {
                    break;
                }
            }

        }
    }
    var data = {county:country};
    var url = config_url+'Pattern/get_currency';
    var result = post_ajax(url, data);
    $('#currency').val(result);
});















            });




        </script>


    

