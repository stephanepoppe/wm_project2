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
        $controllers->match('/register', array($this,"registerForm"))->bind('register');
        $controllers->match('/login', array($this,"loginForm"))->bind('login');
        $controllers->match('/logout', array($this,"logout"))->bind('logout');
        $controllers->match('/profile', array($this,"profile"))->bind('profile');

        return $controllers;
    }



    public function profile(Application $app){

    	$assigned = array();
    	$done = array();
    	$posts = array();
    	$user = $app['session']->get('user');


    	if ($app['session']->get('user')){
    		$assigned = $app['services']->getAssignedTasks($user['id']);
    		$posts = $app['services']->getTasksCreatedByUser($user['id']);
    		//var_dump($assignedServices);
    		//die();
    	}
    	else{
    		return $app->redirect($app['url_generator']->generate('home'));
    	}

    	return $app['twig']->render('authentication/profile.twig', array('todos' => $assigned, 'tasks' => $posts));
    }


    public  function logout(Application $app){
    	$app['session']->clear('user');
    	return $app->redirect($app['url_generator']->generate('home'));
    }


    public  function registerForm(Application $app){
    	$registerform = $app['form.factory']->createNamedBuilder('registerform', 'form')
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

	    if ('POST' === $request->getMethod()) {
	    	$registerform->bind($request);

	    	if ($registerform->isValid()) {
            	$data = $registerform->getData();
            	if ($data['password'] === $data['passwordRepeat']){
            		$app['db']->insert('users', 
            			array('name' => $data['name'],
            				'mail' => $data['email'],
            				'password' => md5($data['password'])));

            		// store username in session
            		$app['session']->set('user', array('id' => $data['id'] ,'name' => $data['name'], 'mail' => $data['email']));
            		// redirect to home
            		return $app->redirect($app['url_generator']->generate('home'));
            	}
        	}
	    }

	    // display the form
    	return $app['twig']->render('authentication/register.twig', array('registerform' => $registerform->createView()));
    }


    public  function loginForm(Application $app){
    	
    	$loginform = $app['form.factory']->createNamedBuilder('loginform', 'form')

	        ->add('email', 'email', 
	        	array('constraints' => new Assert\Email(
	        		array('message' => 'Gelieve uw email in te vullen')), 
	        	'label' => 'Email'))
	        ->add('password', 'password', 
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve een Wachtwoord op te geven')), 
	        	'label' => 'Wachtwoord'))
	        ->getForm();

	    $request = $app['request'];
	    $errors = array();
	   	
	   	if ('POST' === $request->getMethod()) {
	    	$loginform->bind($request);

	    	if ($loginform->isValid()) {
            	$data = $loginform->getData();
            	
				$user = $app['db']->fetchAssoc('SELECT * FROM users WHERE mail = ?', array($data['email']));
				if($user){
					if ($user['password'] !== md5($data['password'])) {
						array_push($errors, 'Fout wachtwoord');
					}
					if (count($errors) === 0){
						$app['session']->set('user', array('id' => $user['id'], 'name' => $user['name']));
						// redirect to home
            			return $app->redirect($app['url_generator']->generate('home'));
					}
        		}
        		else{
        			array_push($errors, 'Er bestaat geen account met dit emailadres');
        		}
	    	}
	    }

	    // display the form
    	return $app['twig']->render('authentication/login.twig', 
    		array('loginform' => $loginform->createView(), 'errors' => $errors));
    }
}