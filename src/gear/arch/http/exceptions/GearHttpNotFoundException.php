<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearHttpStatusCodeException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\exceptions;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\exceptions\GearHttpStatusCodeException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHttpNotFoundException extends GearHttpStatusCodeException
{
    public function __construct($message = null)
    {
        parent::__construct($message == null
            ? 'Not found'
            : $message, 404, 0);
    }
}
/*</module>*/
?>