<?php
//$SOURCE_LICENSE$

/*<requires>*/
//ActionResultBase
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\ActionResultBase;
/*</namespace.use>*/

/*<module>*/
class JsonResult extends ActionResultBase
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
        $allowGet = $context->getConfig()->getValue(Gear_IniKey_JsonResultAllowGet, Gear_IniSection_ActionResolver, false);
        if ($method == 'GET' && !($this->allowGet || $allowGet)) {
            return new ErrorResult("Action is not configured to serve data as GET http method.");
        }

        $response->write(json_encode($this->content));
    }
}
/*</module>*/
?>