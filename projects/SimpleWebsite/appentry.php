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
        $routeConfig->add('/:controller/:action', array());
        $routeConfig->add('/:controller', array('action' => 'index'));
        $routeConfig->add('/', array('controller' => 'home', 'action' => 'index'));

        $routeService->enableCache();
    }

    public function onExceptionOccurred($ex)
    {

    }
}