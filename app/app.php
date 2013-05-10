<?php

	require __DIR__ . '/bootstrap.php';
	


	$app->mount('/', new Application\Provider\Controller\HomeController());
	$app->mount('/register', new Application\Provider\Controller\AuthenticationController());


		/*
	$app->error(function (\Exception $e, $code) {
	    if ($code == 404) {
	        return 'Oops something went wrong';
	    } else {
	        return 'Something went horribly wrong';
	    }
	});
	*/




