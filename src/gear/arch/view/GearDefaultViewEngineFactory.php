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
    static $instance ;
    function createEngine($context)
    {
        if (self::$instance == null) {
            self::$instance = new GearDefaultViewEngine();
        }
        return self::$instance;
    }
}
/*</module>*/
?>