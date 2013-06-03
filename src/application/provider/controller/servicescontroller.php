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
	        $controllers->match('/overview', array($this,"overview"))->bind('servicesoverview');
	        $controllers->match('/map', array($this,"map"))->bind('map');
	        $controllers->match('/status', array($this,"status"));

	        return $controllers;
    	}


    	public function add(Application $app){
    		$catarr;
    		$catarr[0] = "Selecteer een categorie";
    		$categories = $app['categories']->findAll();
    		foreach ($categories as $categorie => $value) {
    			$catarr[$value['id']] = $value['name'];
    		}

    		$addform = $app['form.factory']->createNamedBuilder('addform', 'form')
	        ->add('title', 'text', 
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve een titel in te vullen')), 
	        	'label' => 'Titel'))
	        ->add('description', 'textarea',
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve een omschrijving in te vullen')),    
	        	'label' => 'Omschrijving'))
	        ->add('reward', 'text', 
	        	array('constraints' => new Assert\NotBlank(
	        		array('message' => 'Gelieve een belonning in te vullen')), 
	        	'label' => 'Beloning'))
	        ->add('categorie', 'choice', 
	        	array('choices' => $catarr,
	        	'label' => 'Categorie'))
	        ->add('location', 'choice', 
	        	array('choices'   => array(
			    	'no_location' => 'Geen locatie',
			        'actual_location'   => 'Huidige locatie',
			        'manual_location'   => 'Locatie ingeven',
			    ),
			    'multiple'  => false,
			    //'expanded' => true,
			    'label' => 'Locatie',
			    'attr' => array('class' => 'selectLoc')))
	        ->add('locationText', 'text', array('label' => 'Locatie', 'attr' => array('class' => 'inputLoc')))
			->add('deadline', 'text',   
	        	array('attr' => array('class' => 'calendar'),
	        	'label' => 'Dag van uitvoering',
	        	'constraints' => array( new Assert\NotBlank(array('message' => 'Gelieve een datum in te vullen')),
	        		new Assert\Date(array('message' => 'Gelieve een correcte datum in te vullen')))))
			->add('lat', 'hidden')
			->add('lng', 'hidden')
	        
	        ->getForm();

	    	$request = $app['request'];

	    	if ('POST' === $request->getMethod()) {
	    		$addform->bind($request);
	    		if($addform->isValid()){
	    			$data = $addform->getdata();
	    			$user = $app['session']->get('user');
		    		$app['services']->insert(array(
		    			'title' => $data['title'],
		    			'description' => $data['description'],
		    			'reward' => $data['reward'],
 		    			'location_name' => $data['locationText'],
		    			'location_latitude' => $data['lat'],
		    			'location_longitude' => $data['lng'],
		    			'author_id' => $user['id'],
		    			'added' => date('Y-m-d H:i:s', time()),
		    			'deadline' => date('Y-m-d H:i:s', strtotime($data['deadline'])),
		    			'categories_id' => $data['categorie'],
		    			'status' => 'pending',	
		    		));
		    		// @todo redirect to profile dashboard
            		return $app->redirect($app['url_generator']->generate('home'));
	    		}
	    	}

	    	// display the form
    		return $app['twig']->render('services/add.twig', array('addform' => $addform->createView()));
    	}

    	public function overview(Application $app){
    		$categories = $app['categories']->findAll();


    		if('GET' === $app['request']->getMethod() && $app['request']->query->get('get')){
    			$offset = $app['request']->query->get('offset');
    			$categories = $app['request']->query->get('categories');

    			$services = $app['services']->getAllServicesByCategories($categories, $offset);

    			return $app->json($services, 201);
    		}

    		if('POST' === $app['request']->getMethod()){
    			$postData = $app['request']->request->all();
    			$user = $app['session']->get('user');
    			if(!empty($user)){
    				
    				$service = $app['services']->getServiceById($postData['id']);

    				$output[] = $app['services']->assignService($postData['id'], $user['id']);

    				$message = 'De opdracht:' . $service['title'] . 
					    		'zal uitgevoerd worden door ' . $user['name'] . '.' .
					    		' Locatie: ' . $service['location_name'];
    				
    				$output[] = $app['messages']->insert(array(
					    'sender_id' => $user['id'],
					    'receiver_id' => $app['services']->getAuthorId($postData['id']),
					    'message' => $message,
					    //'data' => time(),
					    'status' => 'unread',
					));

					$message = \Swift_Message::newInstance()
				        ->setSubject('Swappy: ' . $service['title'])
				        ->setFrom(array('stephanepoppe@gmail.com'))
				        ->setTo(array('stephanepoppe@gmail.com'))
				        ->setBody($message);
					
					
					return $app->json($output, 201);
    			}
    			else {
    				return $app->json(null, 203);
    			}
    		}


    		return $app['twig']->render('services/overview.twig', 
    			array('categories' => $categories));

    	}

    	public function map(Application $app){

    		if('GET' === $app['request']->getMethod() && $app['request']->query->get('maps')){
    			$services = $app['services']->getAllServices();
    			return $app->json($services, 201);
    		}

    		if('POST' === $app['request']->getMethod()){
    			$postData = $app['request']->request->all();
    			$user = $app['session']->get('user');
    			if(!empty($user)){
    				
    				$service = $app['services']->getServiceById($postData['id']);

    				$output[] = $app['services']->assignService($postData['id'], $user['id']);

    				$message = 'De opdracht:' . $service['title'] . 
					    		'zal uitgevoerd worden door ' . $user['name'] . '.' .
					    		' Locatie: ' . $service['location_name'];
    				
    				$output[] = $app['messages']->insert(array(
					    'sender_id' => $user['id'],
					    'receiver_id' => $app['services']->getAuthorId($postData['id']),
					    'message' => $message,
					    //'data' => time(),
					    'status' => 'unread',
					));

    				
					$message = \Swift_Message::newInstance()
				        ->setSubject('Swappy: ' . $service['title'])
				        ->setFrom(array('noreply@yoursite.com'))
				        ->setTo(array('feedback@yoursite.com'))
				        ->setBody($request->get($message));
					
					$output = $app['services']->getServiceById($postData['id']);
					return $app->json($output, 201);
    			}
    			else {
    				return $app->json(null, 203);
    			}
    		}

    		return $app['twig']->render('services/map.twig');
    	}


    	public function status(Application $app){
    		if('POST' === $app['request']->getMethod()){
    			$postData = $app['request']->request->all();
    			$user = $app['session']->get('user');

    			$service = $app['services']->getServiceById($postData['id']);
    			$output = $app['services']->updateStatusToDone($postData['id']);

    			$message = 'Proficiat! de opdracht ' . $service['title'] . 
					    		'werd succesvol afgerond!' . '.';


    			$app['messages']->insert(array(
					    'sender_id' => $user['id'],
					    'receiver_id' => $app['services']->getExecutorId($postData['id']),
					    'message' => $message,
					    //'data' => time(),
					    'status' => 'unread',
					));

    			return $app->json($output, 201);
    		}
    		return $app->json(null, 203);
    	}
	}