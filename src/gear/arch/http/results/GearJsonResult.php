<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearActionResultBase
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
use gear\arch\http\results\GearActionResultBase;
use gear\arch\core\GearSerializer;
/*</namespace.use>*/

/*<module>*/
class GearJsonResult extends GearActionResultBase
{
    /** @var mixed */
    protected $content;
    /** @var bool */
    protected $allowGet;

    /**
     * GearJsonResult constructor.
     * @param mixed $content
     * @param bool $allowGet
     */
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

        $json = $this->createJson($context, $request, $response, $this->content);
        $response->setContentType('application/json');
        $this->writeResult($context, $request, $response, $json);
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param mixed $content
     * @return mixed
     */
    public function createJson($context, $request, $response, $content)
    {
        return $content;
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param string $json
     *
     * @return GearErrorResult
     */
    public function writeResult($context, $request, $response, $json)
    {
        $response->write(GearSerializer::json($json));
    }
}
/*</module>*/
?>