<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/18/2016
 * Time: 3:52 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IMessageException;
use gear\arch\core\Serializer;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class InternalServerError
{
    public static function Render($ex, $eCode = null)
    {
        if ($eCode == null) {
            if ($ex instanceof IMessageException) {
                $errorCode = $ex->getHttpStatusCode();
            } else {
                $errorCode = 500;
            }
        } else {
            $errorCode = $eCode;
        }

        if (defined('DEBUG')) {
            Logger::write($ex->getMessage() . ' trace:' . Serializer::stringify($ex->getTrace()));
        }

        http_response_code($errorCode);
        $errMessage = (defined('DEBUG') && $errorCode == 500) ? 'Internal Server Error!' : $ex->getMessage();
        echo "<h1 style=\"color:red\">$errorCode - $errMessage</h1><br>" .
            (defined('DEBUG') ?
                $ex->getMessage() . '<br>' .
                $ex->getFile() . ' at line: ' . $ex->getLine() . '<br><br>' .
                $ex->getTraceAsString()
                :
                "Sorry! An internal server error has been occured.<br>Please report to website admin.")
            ;
    }
}
/*</module>*/
?>
