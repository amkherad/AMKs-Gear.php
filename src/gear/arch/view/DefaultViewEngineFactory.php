<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\view;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IEngineFactory;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class DefaultViewEngineFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultViewEngine();
    }
}
/*</module>*/
?>