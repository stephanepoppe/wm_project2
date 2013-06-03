<?php

	namespace Application\Provider\Controller;

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Silex\ControllerCollection;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;

	class MessagesController implements ControllerProviderInterface
	{

		public function connect(Application $app)
    	{
	        $controllers = $app['controllers_factory'];
	        $controllers->match('/user', array($this,"user"));


	        return $controllers;
    	}


    	public function user(Application $app){

    		if('GET' === $app['request']->getMethod() && $app['request']->query->get('ajax')){
    			$user = $app['session']->get('user');
    			$userId = $user['id'];

    			$services = $app['messages']->getMessagesByUserId($userId);

    			return $app->json($services, 201);
    		}

    		if('POST' === $app['request']->getMethod()){
    			$postData = $app['request']->request->all();
    			$id = $postData['id'];

    			$services = $app['messages']->setMessageStatus($id, 'read');

    			return $app->json($postData, 201);
    		}
    	}
	}