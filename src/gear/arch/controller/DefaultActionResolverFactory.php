<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 2:22 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IEngineFactory;
use gear\arch\core\DefaultActionResolver;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class DefaultActionResolverFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultActionResolver();
    }
}
/*</module>*/
?>