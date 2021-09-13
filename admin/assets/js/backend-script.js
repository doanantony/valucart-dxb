
$(function(){
$('.show-productsdetails').on("click", function(){
	
	var patientdetailsval = $(this).attr("data-id");
	var loader = '<p class="text-center"><img src="'+base_url+'assets/images/ajax-loader-4.gif" /></p>';
    $('#popup-patientModal .modal-patientbody').html(loader);
    $('#popup-patientModal').modal({show:true});
	$.ajax({	        
				type: "POST",
				url: base_url+'Products/products_viewpopup',
                
				data: {'patientdetailsval':patientdetailsval},
				cache: false,
				success: function(result)
				{
					$('#popup-patientModal .modal-patientbody').html(result);
						

				}
	});
})
});



$(function(){
$('.show-bannerdetails').on("click", function(){
	var patientdetailsval = $(this).attr("data-id");
	var loader = '<p class="text-center"><img src="'+base_url+'assets/images/ajax-loader-4.gif" /></p>';
    $('#popup-patientModal .modal-patientbody').html(loader);
    $('#popup-patientModal').modal({show:true});
	$.ajax({	        
				type: "POST",
				url: base_url+'Banners/banner_viewpopup',
                
				data: {'patientdetailsval':patientdetailsval},
				cache: false,
				success: function(result)
				{
					$('#popup-patientModal .modal-patientbody').html(result);
						

				}
	});
})
});




$(function(){
$('.show-vendorsdetails').on("click", function(){
	console.log('d');
	var patientdetailsval = $(this).attr("data-id");
	var loader = '<p class="text-center"><img src="'+base_url+'assets/images/ajax-loader-4.gif" /></p>';
    $('#popup-patientModal .modal-patientbody').html(loader);
    $('#popup-patientModal').modal({show:true});
	$.ajax({	        
				type: "POST",
				url: base_url+'Vendors/vendors_viewpopup',
                
				data: {'patientdetailsval':patientdetailsval},
				cache: false,
				success: function(result)
				{
					$('#popup-patientModal .modal-patientbody').html(result);
						

				}
	});
})
});


$(function(){
$('.show-locationcreate').on("click", function(){
	var patientdetailsval = $(this).attr("data-id");
	var loader = '<p class="text-center"><img src="'+base_url+'assets/images/ajax-loader-4.gif" /></p>';
    $('#popup-patientModal .modal-patientbody').html(loader);
    $('#popup-patientModal').modal({show:true});
	$.ajax({	        
				type: "POST",
				url: base_url+'Deliveryspots/location_createpopup',
                
				data: {'patientdetailsval':patientdetailsval},
				cache: false,
				success: function(result)
				{
					$('#popup-patientModal .modal-patientbody').html(result);
						

				}
	});
})
});




$(function(){
$('.show-cartdetails').on("click", function(){
	console.log('d');
	var patientdetailsval = $(this).attr("data-id");
	var loader = '<p class="text-center"><img src="'+base_url+'assets/images/ajax-loader-4.gif" /></p>';
    $('#popup-patientModal .modal-patientbody').html(loader);
    $('#popup-patientModal').modal({show:true});
	$.ajax({	        
				type: "POST",
				url: base_url+'admincart/opencart',
                
				data: {'patientdetailsval':patientdetailsval},
				cache: false,
				success: function(result)
				{
					$('#popup-patientModal .modal-patientbody').html(result);
						

				}
	});
})
});





$(function(){
$('.show-orderdetails').on("click", function(){
	var patientdetailsval = $(this).attr("data-id");
	var loader = '<p class="text-center"><img src="'+base_url+'assets/images/ajax-loader-4.gif" /></p>';
    $('#popup-patientModal .modal-patientbody').html(loader);
    $('#popup-patientModal').modal({show:true});
	$.ajax({	        
				type: "POST",
				url: base_url+'Orders/order_viewpopup',
                
				data: {'patientdetailsval':patientdetailsval},
				cache: false,
				success: function(result)
				{
					$('#popup-patientModal .modal-patientbody').html(result);
						

				}
	});
})
});


$(function() {
$('#pick-map').click(function (e) {
        e.preventDefault();
        $('#myModalmapbmd').modal('show');
    });	
$('#myModalmapbmd').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitude').val($('#pick-lat').val());
	$('#longitude').val($('#pick-lng').val());
	$('#myModalmapbmd').modal('hide');
});



function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lat');
	var longitude = document.getElementById('pick-lng');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}
});



// Edit business information

$(function() {
$('#pick-maps').click(function (e) {
        e.preventDefault();
        $('#myModalmaping').modal('show');
    });	
$('#myModalmaping').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitudes').val($('#pick-lats').val());
	$('#longitudes').val($('#pick-lngs').val());
	$('#myModalmaping').modal('hide');
});

function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvasing'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lats');
	var longitude = document.getElementById('pick-lngs');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}

});


/////////////////////////////////////////////////////////
/////////************MAP CLINIC ADD************//////////

$(function() {
$('#pick-map').click(function (e) {
        e.preventDefault();
        $('#myModalmapclinic').modal('show');
    });	
$('#myModalmapclinic').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitude').val($('#pick-lat').val());
	$('#longitude').val($('#pick-lng').val());
	$('#myModalmapclinic').modal('hide');
});



function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lat');
	var longitude = document.getElementById('pick-lng');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}
});


//////////////////////////////////////////////////////////
/////////************MAP CLINIC EDIT************//////////

$(function() {
$('#pick-maps').click(function (e) {
        e.preventDefault();
        $('#myModalmapingedit').modal('show');
    });	
$('#myModalmapingedit').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitudes').val($('#pick-lats').val());
	$('#longitudes').val($('#pick-lngs').val());
	$('#myModalmapingedit').modal('hide');
});

function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvasing'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lats');
	var longitude = document.getElementById('pick-lngs');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}

});




/////////////////////////////////////////////////////////
/////////************MAP MEDICAL ADD************//////////

$(function() {
$('#pick-map').click(function (e) {
        e.preventDefault();
        $('#myModalmapmedical').modal('show');
    });	
$('#myModalmapmedical').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitude').val($('#pick-lat').val());
	$('#longitude').val($('#pick-lng').val());
	$('#myModalmapmedical').modal('hide');
});



function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lat');
	var longitude = document.getElementById('pick-lng');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}
});


//////////////////////////////////////////////////////////
/////////************MAP MEDICAL EDIT************//////////

$(function() {
$('#pick-maps').click(function (e) {
        e.preventDefault();
        $('#myModalmapingmedicaledit').modal('show');
    });	
$('#myModalmapingmedicaledit').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitudes').val($('#pick-lats').val());
	$('#longitudes').val($('#pick-lngs').val());
	$('#myModalmapingmedicaledit').modal('hide');
});

function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvasing'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lats');
	var longitude = document.getElementById('pick-lngs');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}

});


/////////////////////////////////////////////////////////
/////////************MAP HOSPITAL ADD************//////////

$(function() {
$('#pick-map').click(function (e) {
        e.preventDefault();
        $('#myModalmaphospitaladd').modal('show');
    });	
$('#myModalmaphospitaladd').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitude').val($('#pick-lat').val());
	$('#longitude').val($('#pick-lng').val());
	$('#myModalmaphospitaladd').modal('hide');
});



function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lat');
	var longitude = document.getElementById('pick-lng');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}
});


//////////////////////////////////////////////////////////
/////////************MAP HOSPITAL EDIT************//////////

$(function() {
$('#pick-maps').click(function (e) {
        e.preventDefault();
        $('#myModalmapinghospitaledit').modal('show');
    });	
$('#myModalmapinghospitaledit').on('shown.bs.modal', function () {
	load_map();
	//google.maps.event.trigger(map, 'resize')
});


$('.select-location').click(function (e) {
	$('#latitudes').val($('#pick-lats').val());
	$('#longitudes').val($('#pick-lngs').val());
	$('#myModalmapinghospitaledit').modal('hide');
});

function load_map() {
	
	var map = new google.maps.Map(document.getElementById('map_canvasing'), {
						zoom: 7,
						center: new google.maps.LatLng(35.137879, -82.836914),
						mapTypeId: google.maps.MapTypeId.HYBRID
					});
					
	var myMarker = new google.maps.Marker({
		position: new google.maps.LatLng(9.369, 76.803),
		draggable: true
	});
	
    var latitude = document.getElementById('pick-lats');
	var longitude = document.getElementById('pick-lngs');
	
	google.maps.event.addListener(myMarker, 'dragend', function (evt) {
		document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
		latitude.value = evt.latLng.lat().toFixed(3);
		longitude.value = evt.latLng.lng().toFixed(3);
	});
	
	google.maps.event.addListener(myMarker, 'dragstart', function (evt) {
		document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
	});
	
	map.setCenter(myMarker.position);
	myMarker.setMap(map);
}


$("#hospital_name").change(function()
			{
				//alert("hai");
				var id=$("#hospital_name").val();
				//alert(id);
				$.ajax({
					type:"post",				
                    url: base_url+'HospitalAppoinment_ctrl/viewdoctor',					
					data: { id:id},
					success:function(data){
						$("#doctorappend").html(data);
					}
				});
			});

});

$("#clinic_name").change(function()
			{
				//alert("hai");
				var id=$("#clinic_name").val();
				//alert(id);
				$.ajax({
					type:"post",				
                    url: base_url+'ClinicAppoinment_ctrl/viewdoctor',					
					data: { id:id},
					success:function(data){
						$("#doctor").html(data);
					}
				});
			});
$("#medical_name").change(function()
			{
				//alert("hai");
				var id=$("#medical_name").val();
				//alert(id);
				$.ajax({
					type:"post",				
                    url: base_url+'MedicalAppoinment_ctrl/viewdoctor',					
					data: { id:id},
					success:function(data){
						$("#mdoctor").html(data);
					}
				});
			});

//Multi Select Box 				   
$(document).ready(function() {			
				 
$(".js-example-basic-multiple").select2();   
$(".my-colorpicker1").colorpicker();
				   
});	
  /**time for alert messages**/
  $(window).bind("load", function() {
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);
});

//  $(function() {
//     $(".datepicker1").datepicker({
// 		autoclose: true,
// 		format: 'dd-mm-yyyy'
		
// 		});

// });

