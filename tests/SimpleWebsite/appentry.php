<?php

class Application extends GearApplication
{
    public function appCreate($context, $engine)
    {

    }

    public function configRoute($context, $routeService, $routeConfig)
    {
        $routeConfig->defaultCallback(function (Route $route) {
            print_r($route);
        });

        $routeConfig->add('/', array('controller' => 'test', 'action' => 'index'));
        //$routeConfig->add('/:controller', array('action' => 'index'));
        //$routeConfig->add('/:controller/:action/*', array());
        $routeConfig->add('/foo/:action/\d+:id', array('controller' => 'foos'));
        $routeService->enableCache();
    }

    public function onExceptionOccurred($ex)
    {

    }
}