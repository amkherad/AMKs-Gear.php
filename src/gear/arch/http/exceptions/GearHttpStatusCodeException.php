<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearFxException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\exceptions;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearFxException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHttpStatusCodeException extends GearFxException
{
    public function __construct($message, $httpStatusCode, $code = 0)
    {
        parent::__construct($message, $httpStatusCode, $code);
    }
}
/*</module>*/
?>