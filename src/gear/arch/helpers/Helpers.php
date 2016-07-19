<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 1:37 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class Helpers
{
    public static function TryGetArrayElementByNameCaseInSensetive(array&$arr, $key, &$result)
    {
        $key = strtolower($key);
        foreach ($arr as $k => $val)
            if (strtolower($k) == $key) {
                $result = $val;
                return true;
            }
        return false;
    }
}

/*</module>*/
?>