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

	$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    	'translator.messages' => array(),
	));

	$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	    'db.options' => array(
	        'driver' => 'pdo_mysql',
	        'host' => 'localhost',
	        'dbname' => 'wm_project2',
	        'user' => 'root',
	        'password' => 'root'
	    )
	));

	$app->register(new Silex\Provider\FormServiceProvider());

	$app->register(new Silex\Provider\ValidatorServiceProvider());

	$app->register(new Silex\Provider\UrlGeneratorServiceProvider());