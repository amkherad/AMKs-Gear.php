<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\view\IGearViewEngine;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearViewResult extends GearActionResultBase
{
    private
        $controller,
        $viewName,
        $model;

    public function __construct($controller, $viewName, $model)
    {
        $this->controller = $controller;
        $this->viewName = $viewName;
        $this->model = $model;
    }

    public function executeResult($context, $request, $response)
    {
        $viewEngineFactory = $context->getService(Gear_ServiceViewEngineFactory);

        $viewEngine = $viewEngineFactory->createEngine($context);

        $viewName = $this->viewName;
        if(!isset($viewName)){
            $viewName = $context->getRoute()->getMvcContext()->getActionName();
        }

        return $viewEngine->renderView(
            $context,
            $this->controller,
            $viewName,
            $this->model
        );
    }
}
/*</module>*/
?>