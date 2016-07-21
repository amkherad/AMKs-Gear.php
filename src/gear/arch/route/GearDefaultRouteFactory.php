<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\app\GearAppEngine;
use gear\arch\core\GearInvalidOperationException;
use gear\arch\core\IGearEngineFactory;
use gear\arch\route\GearDefaultRouteService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultRouteFactory implements IGearEngineFactory
{
    public function createEngine($context)
    {
        $config = GearAppEngine::$GearConfigCache;
        if($config == null) {
            throw new GearInvalidOperationException();
        }
        return new GearDefaultRouteService($config);
    }
}
/*</module>*/
?>