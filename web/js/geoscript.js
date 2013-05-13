$(document).ready(function() {
	if (navigator.geolocation) {
  		navigator.geolocation.getCurrentPosition(success);
	} else {
		 alert('not supported');
	}

	function success(position) {
		
		alert(position.coords.latitude);
	}
});