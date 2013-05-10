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
	        'email' => 'email',
	        'password',
	        'passwordRepeat'
	    );

    	$registerform = $app['form.factory']->createBuilder('form', $data)
	        ->add('name', 'text', 
	        	array('constraints' => new Assert\NotBlank()))
	        ->add('email', 'email', 
	        	array('constraints' => new Assert\Email()))
	        ->add('password', 'password', 
	        	array('constraints' => new Assert\NotBlank()))
	        ->add('passwordRepeat', 'password',  
	        	array('constraints' => new Assert\NotBlank()),
	        	array('label' => 'test'))
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
            	} else{
            		var_dump('test');
            	}

           		// redirect to home
            	return $app->redirect($app['url_generator']->generate('home'));
        	}
        	
	    }


	    // display the form
    	return $app['twig']->render('register/register.twig', array('registerform' => $registerform->createView()));
    }
}