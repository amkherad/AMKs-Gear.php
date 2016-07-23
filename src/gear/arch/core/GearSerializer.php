<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class GearSerializer
{
    public static function stringify($mixed)
    {
        $result = '';
        if (is_object($mixed)) $result .= get_class($mixed);
        elseif (is_array($mixed)) foreach ($mixed as $element) $result .= self::stringify($element);
        else $result .= strval($mixed);
        return $result;
    }

    public static function json($mixed, $config = null)
    {
        return json_encode($mixed);
    }

    public static function xml($mixed, $config = null)
    {

    }
}

/*</module>*/
?>