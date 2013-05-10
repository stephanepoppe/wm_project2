<?php

	// Require Composer Autoloader
	require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

	// Create new Silex App
	$app = new Silex\Application();

	// App Configuration
	$app['debug'] = true;


	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__ .  '/../' . 'src' . '/views'
	));