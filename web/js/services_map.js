		var map;

		function initialize(){
			if (navigator.geolocation) {
			  	navigator.geolocation.getCurrentPosition(loadMap);
			}
			else {
				loadMap();
			} 
			
		}

		var loadMap = function(position){
			var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		    var myOptions = {
		        zoom: 18,
		        center: latlng,
		        mapTypeId: google.maps.MapTypeId.ROADMAP,
		        mapTypeControl: false
		    };		    

			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

			// user positon
			marker = new google.maps.Marker({
			    position: latlng,
			    map: map,
			    icon: '../img/user_pin.png'
			});



			loadMarkers();
		}

		var loadMarkers = function(){
			var url = $(location).attr('href');

			function infoCallback(infowindow, marker) { 
	            return function() { infowindow.open(map, marker) };
	        }

			$.ajax({
              	url: url,
              	data: {maps: 'maps'},
              	success: function(data) {
              		console.log(data);
                	$.each(data, function (){
                    	marker = new google.maps.Marker({
			            	position: new google.maps.LatLng((this).location_latitude, (this).location_longitude),
			            	map: map,
			            	icon: '../img/map_pin.png'
			        	});

                    	var contentString = '<div><h3>'+ (this).title+'</h3>' +
					 			'<span class="span9 gift">'+ (this).reward +'</span>' +
					 			'<p>' + (this).description +'</p>' + 
					 			'<div class="row"><div class="span6">'+
									'<span class="fui-location-16" >' +
										'<a class="location" href="">' + (this).location_name +'</a></span>' +
								'</div>'+
								'<div class="span2"><a href="#" class=" btn btn-block btn-success btn-large">Uitvoeren</a></div></div>'

			        	var infowindow = new google.maps.InfoWindow({
						    content: contentString
						});

						google.maps.event.addListener(marker, 'click',  infoCallback(infowindow, marker));
			        });
             	}
            }); 
		}


		$(window).load(function(){
		    initialize()
		});
