



$(window).ready(function(){
	var visible = false;

	$('.hamburger').on('click', function(e){
		e.preventDefault();
		console.log('test');
		if (visible === false){
			$('.nav-collapse').show();
			visible = true;
			console.log('show');
		}
		else{
			$('.nav-collapse').hide();
			visible = false;
			console.log('hide');
		}
		
	});
});