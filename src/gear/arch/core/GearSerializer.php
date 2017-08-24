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
    static $jsonSerializer;

    public static function setJsonSerializer($serializer)
    {
        self::$jsonSerializer = $serializer;
    }

    public static function stringify($mixed)
    {
        $result = '';
        if (is_object($mixed)) $result .= get_class($mixed);
        elseif (is_array($mixed)) $result .= self::json($mixed);
        else $result .= strval($mixed);
        return $result;
    }

    public static function json($mixed, $config = null)
    {
        if (self::$jsonSerializer != null) {
            return self::$jsonSerializer->serialize($mixed, $config);
        }
        return json_encode($mixed);
    }

    public static function xml($mixed, $config = null)
    {

    }
}

/*</module>*/
?>