<?php
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
class GearDefaultModelBinderEngine implements IGearModelBinderEngine
{
    protected
        $useRequestParams = true
    ;

    public function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext)
    {
        //$constructor = $modelDescriptor->getConstructor();
        //if (isset($constructor)) {
        //    throw new GearInvalidOperationException("ViewModel has a implemented constructor method.");
        //}
        $instance = $modelDescriptor->newInstance();
        if ($instance == null) {
            throw new GearInvalidOperationException('Argument $instance is null.');
        }

        $request = $context->getRequest();

        $sources = [];
        if ($request->isJsonRequest()) {
            $sources[] = json_decode($request->getBody(), true, 512, JSON_OBJECT_AS_ARRAY);
        }
        if ($this->useRequestParams) {
            $sources[] = $request->getCurrentMethodValues();
        }

        $sources[] = $mvcContext->getParams();
        self::_bind($context, $instance, $sources);

        return $instance;
    }

    public function fillModelFromContext($instance, $context, $controller, $mvcContext)
    {
        $request = $context->getRequest();

        $sources = [];
        if ($request->isJsonRequest()) {
            $sources[] = $request->getBodyParameters(); //json_decode($request->getBody(), true, 512, JSON_OBJECT_AS_ARRAY);
        }
        if ($this->useRequestParams) {
            $sources[] = $request->getCurrentMethodValues();
        }

        $sources[] = $mvcContext->getParams();
        self::_bind($context, $instance, $sources);
    }

    private static function _bind($context, $instance, $sources)
    {
        $vars = get_class_vars(get_class($instance));
        foreach ($vars as $k => $v) {
            $result = null;
            foreach ($sources as $source) {
                if (!$source || !isset($source)) continue;
                if (GearHelpers::tryGetArrayElementByNameCaseInSensetive($source, $k, $result))
                    $instance->$k = $result;
            }
        }
    }
}

/*</module>*/
?>