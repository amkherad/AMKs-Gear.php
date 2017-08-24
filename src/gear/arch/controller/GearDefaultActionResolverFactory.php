<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearEngineFactory;
use gear\arch\core\GearDefaultActionResolver;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultActionResolverFactory implements IGearEngineFactory
{
    function createEngine($context)
    {
        return new GearDefaultActionResolver();
    }
}
/*</module>*/
?>