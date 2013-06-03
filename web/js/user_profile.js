

$(document).ready(function(){

	$('.task').on('click', function(){
		var id = $(this).closest('li').data('id');
		console.log(id);

		$.magnificPopup.open({
			preloader: true,
			items: {
				src: '<div class="white-popup">'+
				'<p>Wijzig de status van deze opdracht naar "uitgevoerd"</p>' +
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
						$.post(window.location.origin + '/services/status',{id:id}, function(data, textStatus, jqXHR) {
							console.log(data);

							$('#alerts').append('<div class="alert alert-success">' + 
								'<button type="button" class="close" data-dismiss="alert">&times;</button>' +
								'<strong>De opdracht is voltooid!</div>');
						
							$('#uTask').find('li[data-id = "'+ id +'"]').remove();
							$.magnificPopup.close();
						});
					});
				},
			}
		});
	});
});