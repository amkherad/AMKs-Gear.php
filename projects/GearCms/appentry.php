<?php

class Application extends GearApplication
{
    public function appCreate($context, $engine)
    {

    }

    public function configRoute($context, $routeService, $routeConfig)
    {
        $routeConfig->add('/services/:controller/:entity/:id', array('area' => 'services', 'action' => 'getAll'));
        $routeConfig->add('/services/:controller/:entity', array('area' => 'services', 'action' => 'getAll'));
        $routeConfig->add('/services/:controller', array('area' => 'services', 'action' => 'getAll'));

        $routeConfig->add('/panel/:controller/:action', array('area' => 'panel'));
        $routeConfig->add('/panel/:controller', array('area' => 'panel', 'action' => 'index'));

        $routeConfig->add('/:controller/:action', array('area' => 'web'));
        $routeConfig->add('/:controller', array('area' => 'web', 'action' => 'index'));

        $routeConfig->add('/:area/:controller/:action', array());
        $routeConfig->add('/', array('area' => 'web', 'controller' => 'home', 'action' => 'index'));

        $routeService->enableCache();
    }

    public function onExceptionOccurred($ex)
    {

    }
}