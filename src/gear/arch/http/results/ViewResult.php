<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http\results;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class ViewResult extends ActionResultBase
{
    public function executeResult($context, $request, $response)
    {
        $execResult = $this->controller->View->RenderView($this->controllerName, $this->action, $this->model);
        $result = array();
        if (is_array($execResult))
            foreach ($execResult as $r)
                if ($r instanceof ActionResult)
                    $result[] = $r;
        if (sizeof($result) > 0) return new BatchActionResult($result);
        return true;
    }
}
/*</module>*/
?>