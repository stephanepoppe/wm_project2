<?php

	namespace Application\Provider\Controller;

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Silex\ControllerCollection;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;

	class ServicesController implements ControllerProviderInterface
	{
		
		public function connect(Application $app)
    	{
	        $controllers = $app['controllers_factory'];
	        $controllers->match('/add', array($this,"add"))->bind('addservice');

	        return $controllers;
    	}


    	public function add(Application $app){

    		$addform = $app['form.factory']->createNamedBuilder('addform', 'form')
	        ->add('name', 'text', 
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve een gebruikersnaam in te vullen')), 
	        	'label' => 'Gebruikersnaam'))
	        ->add('email', 'email',  
	        	array('constraints' => new Assert\Email(
	        		array('message' => 'Gelieve uw email in te vullen')), 
	        	'label' => 'Email'))
	        ->add('password', 'password', 
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve een Wachtwoord op te geven')), 
	        	'label' => 'Wachtwoord'))
	        ->add('passwordRepeat', 'password',  
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve het wachtwoord te herhalen')), 
	        	'label' => 'Wachtwoord herhalen'))
	        ->getForm();

	    	$request = $app['request'];

	    	// display the form
    		return $app['twig']->render('services/add.twig', array('addform' => $addform->createView()));
    	}

	}