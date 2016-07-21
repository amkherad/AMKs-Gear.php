<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearMessageException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\core;
use gear\arch\core\IGearMessageException;
/*</namespace.current>*/

/*<bundles>*/
GearBundle::Arch('core\IGearMessageException');
/*</bundles>*/

/*<module>*/
class GearFxException extends \Exception implements IGearMessageException
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