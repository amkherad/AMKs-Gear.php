<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearArgumentNullException extends \Exception implements IGearMessageException
{
    public function __construct($argument = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($argument == null
            ? 'Argument null exception.'
            : "Argument '$argument' is null.'", $code, $previous);
    }

    function getHttpStatusCode()
    {
        return 500;
    }
}
/*</module>*/
?>