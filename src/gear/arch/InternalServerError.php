<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/18/2016
 * Time: 3:52 AM
 */
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class InternalServerError
{
    public static function Render($ex, $errorCode = 500)
    {
        if (defined('DEBUG')) {
            self::_log($ex->getMessage() . ' trace:' . Utils::stringify($ex->getTrace()));
        }
        if ($ex instanceof MvcMessageException) {
            echo "<h2>$ex->Title</h2><h3 style=\"color:red;\">{$ex->getMessage()}</h3>";
        } else {
            $errMessage = (defined('DEBUG') && $errorCode == 500) ? 'Internal Server Error!' : $ex->getMessage();
            echo "<center><h1 style=\"color:red\">$errorCode - $errMessage</h1><br>" .
                (defined('DEBUG') ?
                    $ex->getMessage() . '<br>' .
                    $ex->getFile() . ' at line: ' . $ex->getLine() . '<br><br>' .
                    $ex->getTraceAsString()
                    :
                    "Sorry! An internal server error has been occured.<br>Please report to website admin.")
                . '</center>';
        }
    }
}

/*</module>*/
?>
