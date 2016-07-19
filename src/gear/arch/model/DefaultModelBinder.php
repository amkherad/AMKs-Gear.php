<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 1:42 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\model;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\InvalidOperationException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class DefaultModelBinder implements IModelBinder
{
    public function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext)
    {
        $constructor = $modelDescriptor->getConstructor();
        if (isset($constructor)) throw new MvcInvalidOperationException("ViewModel has a implemented constructor method.");
        $instance = $modelDescriptor->newInstance();
        if ($instance == null) throw new InvalidOperationException('Argument $instance is null.');

        _bind($instance);

        return $instance;
    }

    function _bind($context, $instance, $source)
    {
        if (!isset($source)) {
            $source =  $context->getRequest()->getCurrentMethodValues();
        }
        $vars = get_class_vars(get_class($instance));
        foreach ($vars as $k => $v) {
            $result = null;
            if (Helpers::TryGetArrayElementByNameCaseInSensetive($source, $k, $result))
                $instance->$k = $result;
        }
    }
}
/*</module>*/
?>