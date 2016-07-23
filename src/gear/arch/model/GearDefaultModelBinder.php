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
use gear\arch\core\GearInvalidOperationException;
use gear\arch\helpers\GearHelpers;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearDefaultModelBinder implements IGearModelBinder
{
    protected
        $useRequestParams = true
    ;

    public function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext)
    {
        $constructor = $modelDescriptor->getConstructor();
        if (isset($constructor)) {
            throw new GearInvalidOperationException("ViewModel has a implemented constructor method.");
        }
        $instance = $modelDescriptor->newInstance();
        if ($instance == null) {
            throw new GearInvalidOperationException('Argument $instance is null.');
        }

        $sources[] = $mvcContext->getParams();
        if($this->useRequestParams) {
            $sources[] = $context->getRequest()->getCurrentMethodValues();
        }
        self::_bind($context, $instance, $sources);

        return $instance;
    }

    function fillModelFromContext($instance, $context, $controller, $mvcContext)
    {
        $sources[] = $mvcContext->getParams();
        if($this->useRequestParams) {
            $sources[] = $context->getRequest()->getCurrentMethodValues();
        }
        self::_bind($context, $instance, $sources);
    }

    private static function _bind($context, $instance, $sources)
    {
        $vars = get_class_vars(get_class($instance));
        foreach ($vars as $k => $v) {
            $result = null;
            foreach ($sources as $source) {
                if (!$source || !isset($source)) continue;
                if (GearHelpers::TryGetArrayElementByNameCaseInSensetive($source, $k, $result))
                    $instance->$k = $result;
            }
        }
    }
}

/*</module>*/
?>