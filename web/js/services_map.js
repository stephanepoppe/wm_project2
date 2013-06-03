		

		var map;
		var url = $(location).attr('href');

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
			function infoCallback(infowindow, marker) { 
	            return function() { 
	            	infowindow.open(map, marker);

					$('.assign').on('click', function(e){
						e.preventDefault();
							var id = $(this).closest('.infowindow').data('id');
						 	var title = $(this).closest('.infowindow').find('h3').text();
						 	var gift = $(this).closest('infowindow').find('.gift').text();
						 	//console.log($(this).closest('.infowindow').data('id'));
							$.magnificPopup.open({
								preloader: true,
							  	items: {
							    	src: '<div class="white-popup"><h3>'+ title +'</h3>'+
							    		'<p>Voor deze dienst uit in ruil voor: '+ gift +'!</p>' +
							    		'<a href="" class="cancel_popup btn btn-danger">Annuleer</a><a href="" data-id="'+ id +'" class="send btn">Verstuur</a></div>', // can be a HTML string, jQuery object, or CSS selector
							    	type: 'inline'
							  	},
							  	callbacks: {
								    open: function() {
										$('.cancel_popup').on('click', function(e){
							 				e.preventDefault();
							 				$.magnificPopup.close();
							 			});

							 			$('.send').on('click', function(e){
							 				e.preventDefault();
							 				var id = $(this).data('id');
							 				$.post(url,{id:id}, function(data, textStatus, jqXHR) {
							 					console.log(data);
											  	if(jqXHR.status == "203"){
											  		$('#alerts').append('<div class="alert">'+
					  								'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
					  								'Hiervoor moet je een account hebben!</div>')
											  	}
											  	else {
											  		$('#alerts').append('<div class="alert alert-success">' + 
											  			'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
											  			'<strong>Proficiat! </strong>Deze opdracht is aan jou toegewezen, de opdrachtgever wordt verwittigd</div>');
											  	}
											  	$.magnificPopup.close();
											});
							 			});
								    },
								}
							});
					});

	            };
	        }


	        $.when(call()).then(function(data) {
	        	console.log(data);

	        	$.each(data, function (){
                    	marker = new google.maps.Marker({
			            	position: new google.maps.LatLng((this).location_latitude, (this).location_longitude),
			            	map: map,
			            	icon: '../img/map_pin.png'
			        	});

                    	var contentString = '<div class="infowindow" data-id="'+ (this).id+'"><h3>'+ (this).title+'</h3>' +
					 			'<span class="span9 gift">'+ (this).reward +'</span>' +
					 			'<p>' + (this).description +'</p>' + 
					 			'<div class="row"><div class="span6">'+
					 				'<span class="fui-man-16" >' + (this).users_name +'</a></span>' +
									'<span class="fui-location-16" >' + (this).location_name +'</a></span>' +
									'<span class="fui-calendar-16">' + (this).deadline + '</span>' +
								'</div>'+
								'<div class="span2"><a href="" class="assign btn btn-block btn-success btn-large">Uitvoeren</a></div></div>'

			        	var infowindow = new google.maps.InfoWindow({
						    content: contentString
						});

						google.maps.event.addListener(marker, 'click',  infoCallback(infowindow, marker));

			    });
	        });

		}

		var call = function(){
			return $.ajax({
              	url: url,
              	data: {maps: 'maps'},
			});
		}


		$(window).load(function(){
		    initialize()
		});
