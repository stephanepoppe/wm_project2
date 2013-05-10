<?php

namespace Application\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class AuthenticationController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', function (Application $app) {
        	return $app['twig']->render('register/register.twig');
        });

        return $controllers;
    }
}