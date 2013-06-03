

$(document).ready(function(){


	setInterval(function() {
    	$.ajax({
			type: 'GET',
			url: window.location.origin + '/messages/user',
			data: {ajax : 1},
		}).done(function (data) {
			console.log(data);
			if (!$.isEmptyObject(data)){
				$('#alerts').append('<div class="notification">'+
					'<div class="message"><h2>'+data[0]['name']+'</h2>'+
    					'<p>'+ data[0]['message'] +'</p></div>'+
  						'<div class="buttons"><a class="closeNote" href="">Sluiten</a></div></div>');

				$.post(window.location.origin + '/messages/user', {id: data[0]['id']}, function(data, textStatus, jqXHR) {
					//console.log(data);
				});

				$('.closeNote').on('click', function(e){
					e.preventDefault();
					$('#alerts').empty();
				});
			}
		});
	}, 10000);


});