<?php

define('Cms_Section_Config', 'Config');
define('Cms_Sql_DateFormat', 'Y-m-d H:i:s');

class Application extends GearApplication
{
    public function appCreate($context, $engine)
    {
        $context->getResponse()->setHeader('X-Powered-CMS', 'AMK\'s CMS.php');

        //GearBundle::registerLocator(new BundleLocator());
    }

    public function configRoute($context, $routeService, $routeConfig)
    {
        $routeConfig->add('/search', array('area' => 'cp', 'controller' => 'search', 'action' => 'query'));

        $routeConfig->add('/cp/:controller/:action', array('area' => 'cp'));
        $routeConfig->add('/cp/:controller', array('area' => 'cp', 'action' => 'index'));

        $routeConfig->add('/:controller/:action', array('area' => 'cp'));
        $routeConfig->add('/:controller', array('area' => 'cp', 'action' => 'index'));

        $routeConfig->add('/', array('area' => 'cp', 'controller' => 'home', 'action' => 'index'));

        $routeService->enableCache();
    }

    public function onExceptionOccurred($ex)
    {

    }
}