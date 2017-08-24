<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearNotSupportedException extends \Exception implements IGearMessageException
{
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message == null
            ? 'Not supported.'
            : $message, $code, $previous);
    }

    function getHttpStatusCode()
    {
        return 500;
    }
}
/*</module>*/
?>