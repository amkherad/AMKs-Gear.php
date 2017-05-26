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
use gear\arch\app\GearAppEngine;
use gear\arch\core\IGearMessageException;
use gear\arch\core\GearSerializer;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearInternalServerError
{
    public static function Render($ex, $eCode = null)
    {
        if ($eCode == null) {
            if ($ex instanceof IGearMessageException) {
                $errorCode = $ex->getHttpStatusCode();
            } else {
                $errorCode = 500;
            }
        } else {
            $errorCode = $eCode;
        }

        $debug = GearAppEngine::isDebug();

        if ($debug) {
            GearLogger::write($ex->getMessage() . ' trace:' . GearSerializer::stringify($ex->getTrace()));
        }

        http_response_code($errorCode);
        $errMessage = $debug ? $ex->getMessage() : 'An Error Has Been Occurred!';
        echo "<title>$errorCode - Error</title><h1 style=\"color:red\">$errorCode - $errMessage</h1><br>" .
            ($debug ?
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
