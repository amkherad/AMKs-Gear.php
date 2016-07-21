<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearKunststubeRouteServiceMigration
/*</requires>*/

/*<namespace.current>*/
namespace gear\_3rdparty\kunststube;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearEngineFactory;
use gear\_3rdparty\kunststube\GearKunststubeRouteServiceMigration;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearKunststubeRouterFactory implements IGearEngineFactory
{
    function createEngine($context)
    {
        return new GearKunststubeRouteServiceMigration($context);
    }
}
/*</module>*/
?>