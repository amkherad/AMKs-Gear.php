<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class GearHelpers
{
    public static function tryGetArrayElementByNameCaseInSensetive(&$arr, $key, &$result)
    {
        $key = strtolower($key);
        foreach ($arr as $k => $val)
            if (strtolower($k) == $key) {
                $result = $val;
                return true;
            }
        return false;
    }

    public static function isNullOrWhitespace($string)
    {
        if ((!isset($string)) || $string == null) {
            return true;
        }
        return preg_match('/^[\s]*$/', $string);
    }

    private static function _dumpArray($arr, $indent)
    {
        $size = sizeof($arr);
        echo "<span style=\"color:orange;\">Array($size)</span> => [";
        foreach ($arr as $k => $e) {
            echo '<br>';
            for ($i = 0; $i < $indent; $i++) echo "----";
            echo "<span style=\"color:green;\">'$k'</span> : ";
            if (is_array($e)) self::_dumpArray($e, $indent + 1); else var_dump($e);
            echo ' ,<br>';
        }
        for ($i = 0; $i < $indent; $i++) echo "----";
        echo ']';
    }

    public static function show($var)
    {
        if (is_array($var)) self::_dumpArray($var, 1); else var_dump($var);
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';
        for ($i = 0; $i < $length; $i++) {
            $randString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randString;
    }
}

/*</module>*/
?>