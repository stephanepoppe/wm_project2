<?php

namespace Application\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Silex\Provider\FormServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class AuthenticationController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->match('/', array($this,"registerForm"))->bind('register');;

        return $controllers;
    }


    public  function registerForm(Application $app){
    	// defaults
    	$data = array(
	        'name' => 'naam',
	        'email' => 'gebruiker@mail.com',
	        'password',
	        'passwordRepeat'
	    );

    	$registerform = $app['form.factory']->createNamedBuilder('registerform', 'form', $data)
	        ->add('name', 'text', 
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve een gebruikersnaam in te vullen')), 
	        	'label' => 'Gebruikersnaam'))
	        ->add('email', 'email', 
	        	array('constraints' => new Assert\Email(
	        		array('message' => 'Gelieve uw email op in te vullen')), 
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


	    if ('POST' === $request->getMethod()) {
	    	$registerform->bind($request);

	    	if ($registerform->isValid()) {
            	$data = $registerform->getData();
            	if ($data['password'] === $data['passwordRepeat']){
            		$app['db']->insert('users', 
            			array('name' => $data['name'],
            				'mail' => $data['mail'],
            				'password' => md5($data['password'])));
            		// redirect to home
            		return $app->redirect($app['url_generator']->generate('home'));
            	} else{
            		var_dump('Error');
            	}


        	}
	    }

	    // display the form
    	return $app['twig']->render('register/register.twig', array('registerform' => $registerform->createView()));
    }
}