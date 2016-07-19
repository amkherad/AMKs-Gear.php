<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class DefaultRouteFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultRouter();
    }
}

/*</module>*/
?>