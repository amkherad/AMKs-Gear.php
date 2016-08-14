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
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearStatusCodeResult extends GearActionResultBase
{
    protected
        $statusCode,
        $message;

    public function __construct($statusCode, $message = null)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function executeResult($context, $request, $response)
    {
        $response->statusCode($this->statusCode);
        if (isset($this->message)) {
            $response->write($this->message);
        }
    }
}
/*</module>*/
?>