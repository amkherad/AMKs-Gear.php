<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\view;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearEngineFactory;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultViewEngineFactory implements IGearEngineFactory
{
    function createEngine($context)
    {
        return new GearDefaultViewEngine();
    }
}
/*</module>*/
?>