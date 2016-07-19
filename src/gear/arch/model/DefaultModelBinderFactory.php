<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 2:44 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\model;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IEngineFactory;
use gear\arch\core\DefaultModelBinder;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class DefaultModelBinderFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultModelBinder();
    }
}
/*</module>*/
?>