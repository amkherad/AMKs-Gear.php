<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 12:52 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class InvalidOperationException extends \Exception implements IMessageException
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message == null
            ? 'Invalid operation exception.'
            : $message, $code, $previous);
    }

    function getHttpStatusCode()
    {
        return 500;
    }
}
/*</module>*/
?>