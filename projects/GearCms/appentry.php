<?php

class Application extends GearApplication
{
    public function appCreate($context, $engine)
    {

    }

    public function configRoute($context, $routeService, $routeConfig)
    {
        $routeConfig->add('/:area/:controller/:action', array());

        $routeConfig->add('/api/:controller/:action', array('area' => 'api'));
        $routeConfig->add('/api/:controller', array('area' => 'api', 'action' => 'index'));

        $routeConfig->add('/panel/:controller/:action', array('area' => 'panel'));
        $routeConfig->add('/panel/:controller', array('area' => 'panel', 'action' => 'index'));

        $routeConfig->add('/:controller/:action', array('area' => 'web'));
        $routeConfig->add('/:controller', array('area' => 'web', 'action' => 'index'));
        $routeConfig->add('/', array('area' => 'web', 'controller' => 'home', 'action' => 'index'));

        $routeService->enableCache();
    }

    public function onExceptionOccurred($ex)
    {

    }
}