<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/20/2016
 * Time: 2:43 AM
 */
//$SOURCE_LICENSE$

/*<requires>*/
//HttpStatusCodeException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\exceptions;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\exceptions\HttpStatusCodeException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class HttpNotFoundException extends HttpStatusCodeException
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