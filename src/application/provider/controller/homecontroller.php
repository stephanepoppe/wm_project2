<?php

namespace Application\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class HomeController implements ControllerProviderInterface
{


    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
        	return $app['twig']->render('index/home.twig');
        })->bind('home');

        return $controllers;
    }
}