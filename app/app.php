<?php

	require __DIR__ . '/bootstrap.php';
	


	$app->mount('/', new Application\Provider\Controller\HomeController());
	$app->mount('/user', new Application\Provider\Controller\AuthenticationController());
	$app->mount('/services', new Application\Provider\Controller\ServicesController());
	$app->mount('/messages', new Application\Provider\Controller\MessagesController());

		/*
	$app->error(function (\Exception $e, $code) {
	    if ($code == 404) {
	        return 'Oops something went wrong';
	    } else {
	        return 'Something went horribly wrong';
	    }
	});
	*/




