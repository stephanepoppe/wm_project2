$(document).ready(function() {


		$('.locationtext').hide();
		$('.locfound').hide();

		$('.selectLoc').change(function() {
			var locationOption = ($('select.selectLoc option:selected').val());
			if (locationOption === 'no_location'){
				$('.locationtext').hide();
			}
			else{
				if (locationOption === 'actual_location'){
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
					        	if (data.status === 'OK'){
					        		$('.inputLoc').val(data.results[0]['formatted_address']);
					        		$('.locationtext').show();
					        		$('#addform_lat').val(data.results[0]['geometry']['location']['lat']);
									$('#addform_lng').val(data.results[0]['geometry']['location']['lng']);
									
					        	}
					        },
					        error : function(jqXHR, textStatus, errorThrown) {
					            console.log('error');
					        }
					    });
		        	}
				}
				else{
					$('.locationtext').show();
				}
			}
		});


		$('.inputLoc').keyup(function(event) {
			event.preventDefault();
			var inputsearch = $('.inputLoc').val();
			//https://maps.googleapis.com/maps/api/geocode/output?parameters
			
			$.ajax({
				url: 'http://maps.googleapis.com/maps/api/geocode/json?'+ 
					'address='+ inputsearch + '&sensor=false',
				type: 'get',
				dataType: 'json',
				success: function(data, textStatus, jqXHR) {
					console.log(data);
					if (data.status === 'OK'){
						$('#addform_lat').val(data.results[0]['geometry']['location']['lat']);
						$('#addform_lng').val(data.results[0]['geometry']['location']['lng']);
						//console.log(data.results[0]['geometry']['location']['lng']);
						$('.locfound').text('Locatie Gevonden!').show();
					}
					else{
						$('.locfound').text('Locatie niet gevonden!').show();
					}
				},
				error : function(jqXHR, textStatus, errorThrown) {
					console.log('error');
					
				}
			});
			/**/
		});

});