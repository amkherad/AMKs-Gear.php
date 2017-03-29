<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 4:14 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearLogger;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearLogger
{
    private static $loggers = [];

    public static function write($mixed, $category = null)
    {
        foreach (self::$loggers as $logger) {
            /** @var $logger IGearLogger */
            $logger->write($mixed, $category);
        }
    }

    public static function registerLogger($logger)
    {
        self::$loggers[] = $logger;
    }
}
/*</module>*/
?>