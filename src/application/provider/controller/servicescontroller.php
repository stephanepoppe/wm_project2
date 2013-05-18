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
	        	array('label' => 'Omschrijving'))
	        ->add('categorie', 'choice', 
	        	array(
                	'choices' => $catarr
                ))
	        ->add('location', 'choice', array(
			    'choices'   => array(
			        'actual_location'   => 'Huidige locatie',
			        'manual_location'   => 'Locatie opgeven',
			    ),
			    'multiple'  => false,
			    'expanded' => true,
			    'label' => 'Locatie'))
	        ->add('locationText', 'text',   
	        	array('label' => 'Locatie'))
			->add('deadline', 'text',   
	        	array('attr' => array('class' => 'calendar'),
	        		'label' => 'Dag van uitvoering',
	        		'constraints' => new Assert\NotBlank(
	        			array('message' => 'Gelieve een titel in te vullen'))))
	        ->getForm();

	    	$request = $app['request'];

	    	// display the form
    		return $app['twig']->render('services/add.twig', array('addform' => $addform->createView()));
    	}

	}