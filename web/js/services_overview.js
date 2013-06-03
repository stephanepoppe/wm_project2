		$(document).ready(function() {
			//hide spinner
			$('.spinner').hide();

			var offset = 0;
			var url = $(location).attr('href');

			var getPosts = function(empty){
				var categories = [];

				if (empty == true){
				 	$('.services').empty();
				 }

				// id's categories 
				$('.checkbox').each( function(){
					if ($(this).hasClass('checked')){
						categories.push($(this).data('id'));
					}
				});

				if (categories.length !== 0){
					$.ajax({
						type: 'GET',
						url: url + '?get=serviceposts',
						data: {offset : offset, categories : categories},
						beforeSend: function() {
				        	$('.spinner').show();
				        },
					}).done(function (data) {
						console.log(data);
						$(".spinner").hide();
					 	$.each(data, function (){
					 		$('.services').append($.parseHTML('<li class="tile service" data-id="'+ (this).id+'" data-lat="" data-lng=""><h3>'+ (this).title+'</h3>' +
					 			'<span class="span9 gift">'+ (this).reward +'</span>' +
					 			'<p>' + (this).description +'</p>' + 
					 			'<div class="row"><div class="span6">'+
					 				'<span class="fui-man-16" >' + (this).users_name +'</a></span>' +
									'<span class="fui-location-16" >' + (this).location_name +'</a></span>' +
									'<span class="fui-calendar-16">' + (this).deadline + '</span>' +
								'</div>'+
								'<div class="span2"><a href="" class="assign btn btn-block btn-success btn-large">Uitvoeren</a></div></div></li>'));
					 	});

					 	$('.assign').on('click', function(e){
						 	e.preventDefault();
						 	var id = $(this).closest('li').data('id');
						 	var title = $(this).closest('li').find('h3').text();
						 	var gift = $(this).closest('li').find('.gift').text();
						 	console.log($(this).closest('li').find('.gift').text());
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

											  	$('.services').find('li[data-id = "'+ id +'"]').remove();

											  	$.magnificPopup.close();
											});
							 			});
								    },
								}
							});
						 });
					});
				}

			}

			// Trigger ajax call first time
			getPosts(true);

			// Trigger ajax call when the usere reaches end of the screen
			$(window).scroll(function(){  
				// determine when user scrolls at the bottom
                if  ($(window).scrollTop() + 1 == $(document).height() - $(window).height()){  
                    console.log('end');
                    offset += 10;
                    getPosts(false);
                }  
            }); 

			// Trigger ajax call when categories where (un)selected
			$('.categorie').on('click', function(e) {
				e.preventDefault();
				$checkedbox = $(this).find('.checkbox');
				if($checkedbox.hasClass('checked')){
					$checkedbox.removeClass('checked').addClass('unchecked');
				}
				else{
					$checkedbox.removeClass('unchecked').addClass('checked');
				}
				getPosts(true);
			});			
		});