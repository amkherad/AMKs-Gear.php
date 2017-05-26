<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\app\GearAppEngine;
use gear\arch\GearLogger;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearErrorStrategy
{
    /**
     * @param \Exception $ex
     * @param $uid
     *
     * @return string
     */
    public static function saveLogAndGetTrace($ex, &$uid)
    {
        $uid = uniqid();
        $message = $ex->getMessage();
        $trace = $ex->getTrace();
        GearLogger::write("TrackId: $uid - Message: $message - Trace: $trace");
        if (GearAppEngine::isDebug()) {
            return strval($ex);
        } else {
            return "An error has been occurred. Error unique id: $uid";
        }
    }
}
/*</module>*/
?>