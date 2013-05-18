$(document).ready(function() {

	$('#locationText').css("display", "none");

	$('#addform_location').on('click', function(){
		var locationOpton = ($("input[type='radio']:checked").val());
		if (locationOpton == 'actual_location'){
			geolocation();
			$('#locationText').css("display", "none");
		}
		else{
			// todo: make textfield visibled
			$('#locationText').css("display", "block");
		}
	});	
});


function geolocation(){
	if (navigator.geolocation) {
  		navigator.geolocation.getCurrentPosition(success);
	} else {
		 alert('not supported');
	}
	function success(position) {
		// Reverse Geocoding (Address Lookup)
		// source: https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
		$.ajax({
        url: 'http://maps.googleapis.com/maps/api/geocode/json?'+ 
        'latlng='+ position.coords.latitude +',' + position.coords.longitude + '&sensor=true',
        type: 'get',
        dataType: 'json',
        success: function(data, textStatus, jqXHR) {
        	console.log(data);
        	//alert(data['results'][0]['formatted_address']);
        },
        error : function(jqXHR, textStatus, errorThrown) {
            console.log('error');
        }
    });
	}
}