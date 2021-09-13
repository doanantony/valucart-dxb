var date_sent = '';
var company_id = 0;
var custom_date = '';
$("#summery_output").hide();
$(".error_div").hide();
var settlementGrid = $('.dataTable-settlement').DataTable({
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

setInterval(function() { // reloads the page in every given interval
  if ($('#settlement-auto-reload:checked').length) { // Re-loads page only when the check box is checked
    settlementGrid.ajax.reload(null, false);
  }
},10000);

$('.select').on('change', function () {
    var i = $(this).attr('data-column');
    var v = $(this).val();
    company_id = $(this).val();
    settlementGrid.columns(i).search(v).draw();            
})

$('.daterange-common').daterangepicker(
	{        
	startDate: moment().subtract(29, 'days'),
	endDate  : moment()
	},
	function (start, end) {
		$('.daterange-common span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		var ajax_call = $(this).data('ajax_call');
		called_custom_date(start, end);    
	}
)

$('.trans_date').daterangepicker({ 
	autoUpdateInput: false
	},
	function (start, end) {
		var start_date = start.format('YYYY-MM-DD');
	    var end_date = end.format('YYYY-MM-DD');
	    custom_date = start_date+"_"+end_date;
	    $('#trans_date').val(start_date+" To "+end_date);
	});

function called_custom_date(start, end){
    var start_date = start.format('YYYY-MM-DD');
    var end_date = end.format('YYYY-MM-DD');
    date_sent = start_date+"_"+end_date;
    settlementGrid.columns(0).search(start_date+"_"+end_date).draw();
}

function export_data(method){
  var string = ''
  var date_string = date_sent != ''?"period="+date_sent:"";
  console.log(date_string);
  var company_string = company_id != 0?"company="+company_id:"";
  if(date_string!='' || company_string!=''){
    string = "?";
    if(date_string!=''){
      string += date_string;
      if(company_string!=''){
        string += "&"+company_string;
      }
    } else {
      string +=company_string;
    }
  }
  window.open(config_url+'settlement/'+method+string, '_blank');
}

function filer_result(){
	$(".error_div").hide();
	$("#summery_output").hide("slow");
	var company = $('#company_id').val()
	var vendor = $('#vendor_id').val()
	
	$.ajax({
	    "type": "POST",
	    "url": config_url+'settlement/settle_filter',
	    "data": {"company_id":company,"custom_date":custom_date,"vendor_id":vendor},
	    success: function(response) {
	      result = response;
	      var result = JSON.parse(result);	
	     console.log(result.sales);
	     // if(result.sales != '0'){
	    if(result){	 
	      	//console.log(result);
	      	 if(result.sales != '0'){
									$("#total_amount").html("AED "+parseInt(result.total_price).toFixed(2));
									$("#commission_amount").html("AED "+parseInt(result.commission).toFixed(2));
									$("#vendor_payback_amount").html("AED "+parseInt(result.vendor_payback).toFixed(2));
	      	 }else{
	      	 				$("#total_amount").html("AED "+parseInt(0).toFixed(2));
									$("#commission_amount").html("AED "+parseInt(0).toFixed(2));
									$("#vendor_payback_amount").html("AED "+parseInt(0).toFixed(2));
	      	 }

			$("#count").html(result.sales);
			$("#period").html(result.period);
			$("#summery_output").show("slow");
		} else {
			console.log("Called");
			$(".error_div").show();
		}
	      
	    },
	    error: function(response) {
	      result = 'error';
	    },
  })
}

function reset_result(){
	$(".error_div").hide();
	$("#summery_output").hide();
	$('#trans_date').val("");
}