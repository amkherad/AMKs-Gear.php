<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JqueryDataTablesContext
{
    /** @var IGearContext $context */
    private $context;
    /** @var IGearHttpRequest */
    private $request;
    /** @var IGearHttpResponse */
    private $response;

    /**
     * JqueryDataTablesContext constructor.
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     */
    public function __construct($context, $request, $response)
    {
        $this->context = $context;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return IGearContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return IGearHttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return IGearHttpResponse
     */
    public function getResponse()
    {
        return $this->response;
    }
}
/*</module>*/
?>