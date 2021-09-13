$(document).ajaxStart(function() { Pace.restart(); });
$(function() {
	var flag = false;
	$( "form.validate" ).submit(function( event ) {

	var access = true;
	$(this).find('.required').each(function() {
		var v = $(this).val();

		if(v == null) v='';
		if((v.replace(/\s+/g, '')) == '') {
			//alert('e');
			access = false;
			$(this).parents(".form-group").addClass("has-error");
		}
		else {
			//alert('s');
			$(this).parents(".form-group").removeClass("has-error");
		}
	});
	if(access) {
		return;
	}
	else {
		$("html, body").animate({ scrollTop: $('.has-error').offset().top - 50 }, "slow");
	}
	event.preventDefault();
	
	});
});




$('.show-merchant').on("click", function() {
	//var loader = '<p class="text-center"><img src="'+config_url+'/assets/images/ajax-loader-4.gif" /></p>';
	//$('#myModal .modal-body').html(loader);
	$('#myModal').modal({show:true});
	var id = $(this).data('id');
	var data = {id:id};
	//var url = config_url+'';
	//var result = post_ajax(url, data);
	//$('#myModal .modal-body').html(result);
	//reload_gallery();
});


/*######################################################*/

	function post_ajax(url, data) {
		return new Promise(function(resolve, reject) {
			$.ajax({
		        type: "POST",
		        url: url,
				data: data,
				success: function(response) {
					resolve(response)
				},
				error: function(response) {
					reject(new Error("Script load error: " + response));
				},
				async: false
				});
			});
	}

	//check user availability

	function user_availability(value){
		var data = {"user_name":value};
		//var id = {"id":$id};
		
		var url = config_url+'common_service/check_username';
		let promise = post_ajax(url, data);
		promise.then(
		  result => {
		  	if(result==1){
		  		$('#username_error').html("Username already taken");
		  		flag = true;
		  		console.log(flag);
		  	} else {
		  		$("#username_error").html("");
		  	}
		  },
		  error => console.log(error)
		);
	}

	function check_error(){
		return flag;
	}


	 


	

	// check email availability
	function email_availability(element,$id){
		var value = element.context.value;
		var data = {"email":value,"id":$id};
		//var id = {"id":$id};
		
		var url = config_url+'common_service/check_email';
		let promise = post_ajax(url, data);
		promise.then(
		  result => {
		  	if(result==1){
		  		$('#email_error').html("Email already Exists");
		  		flag = true;
		  	} else {
		  		$("#email_error").html("");
		  	}
		  },
		  error => console.log(error)
		);
	}


	// check phone availability

	function phone_availability(element,$id){
		var value = element.context.value;
		var data = {"phone_no":value,"id":$id};
		//var id = {"id":$id};
		
		var url = config_url+'common_service/check_phone';
		let promise = post_ajax(url, data);
		promise.then(
		  result => {
		  	if(result==1){
		  		$('#phone_error').html("Phone Number already Exists");
		  		flag = true;
		  	} else {
		  		$("#phone_error").html("");
		  	}
		  },
		  error => console.log(error)
		);
	}