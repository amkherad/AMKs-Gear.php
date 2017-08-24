<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearMvcContext
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearMvcContext;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearRouteMvcContext implements IGearMvcContext
{
    private
        $areaName,
        $controllerName,
        $actionName,
        $params;

    public function __construct(
        $areaName,
        $controllerName,
        $actionName,
        $params
    )
    {
        $this->areaName = $areaName;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->params = $params;
    }

    function getAreaName()
    {
        return $this->areaName;
    }

    function getControllerName()
    {
        return $this->controllerName;
    }

    function getActionName()
    {
        return $this->actionName;
    }

    function getParams()
    {
        return $this->params;
    }
}
/*</module>*/
?>