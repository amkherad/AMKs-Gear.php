<?php

define('Cms_Section_Config', 'Config');
define('Cms_Sql_DateFormat', 'Y-m-d H:i:s');

class Application extends GearApplication
{
    public function appCreate($context, $engine)
    {
        $context->getResponse()->setHeader('X-Powered-CMS', 'AMK\'s CMS.php');

        set_include_path(__DIR__.'/includes/redbean/');
        GearBundle::registerLocator(new BundleLocator());
    }

    public function configRoute($context, $routeService, $routeConfig)
    {
        $routeConfig->add('/services/hypo/:entity/:id', array('area' => 'services', 'controller' => 'hypo', 'action' => 'getAll'));
        $routeConfig->add('/services/hypo/:entity', array('area' => 'services', 'controller' => 'hypo', 'action' => 'getAll'));
        //$routeConfig->add('/services/:controller', array('area' => 'services', 'action' => 'getAll'));

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