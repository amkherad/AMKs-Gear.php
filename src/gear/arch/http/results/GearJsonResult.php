<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearActionResultBase
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\results\GearActionResultBase;
use gear\arch\core\GearSerializer;
/*</namespace.use>*/

/*<module>*/
class GearJsonResult extends GearActionResultBase
{
    private
        $content,
        $allowGet;

    public function __construct($content, $allowGet)
    {
        $this->content = $content;
        $this->allowGet = $allowGet;
    }

    public function executeResult($context, $request, $response)
    {
        $method = $request->getMethod();
        $allowGet = $context->getConfig()->getValue(Gear_Key_JsonResultAllowGet, Gear_Section_ActionResolver, false);
        if ($method == 'GET' && !($this->allowGet || $allowGet)) {
            return new GearErrorResult("Action is not configured to serve data as GET http method.");
        }

        $response->contentType('application/json');
        $response->write(GearSerializer::json($this->content));
    }
}
/*</module>*/
?>