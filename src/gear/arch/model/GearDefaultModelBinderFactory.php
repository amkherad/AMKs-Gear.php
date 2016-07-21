<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\model;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearEngineFactory;
use gear\arch\core\GearDefaultModelBinder;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearDefaultModelBinderFactory implements IGearEngineFactory
{
    function createEngine($context)
    {
        return new GearDefaultModelBinder();
    }
}
/*</module>*/
?>