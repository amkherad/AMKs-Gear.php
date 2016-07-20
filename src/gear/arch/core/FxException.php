<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IMessageException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\core;
use gear\arch\core\IMessageException;
/*</namespace.current>*/

/*<bundles>*/
Bundle::Arch('core\IMessageException');
/*</bundles>*/

/*<module>*/
class FxException extends \Exception implements IMessageException
{
    private $httpStatusCode;
    public function __construct($message, $httpStatusCode = 500, $code = 0)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code);
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
/*</module>*/
?>