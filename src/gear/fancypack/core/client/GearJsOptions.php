<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\core\client;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
use \ReflectionClass;
use ReflectionProperty;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearJsOptions
{
    const JsonEscapeComment = '@JsonIgnore';

    public static function serialize($objOrArray)
    {
        if (is_array($objOrArray)) {
            return self::_serializeArray($objOrArray);
        } elseif (is_object($objOrArray)) {
            return self::_serializeObject($objOrArray);
        } else {
            throw new GearInvalidOperationException();
        }
    }

    private static function _serializeObject($obj)
    {
        $reflection = new ReflectionClass($obj);

        $props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $fields = [];
        foreach ($props as $prop) {
            if (stripos($prop->getDocComment(), self::JsonEscapeComment) !== false) {
                continue;
            }
            $val = $prop->getValue($obj);
            $name = $prop->getName();
            if (is_object($val)) {
                if ($val instanceof GearRawOutput) {
                    $val = strval($val);
                    $fields[] = "\"$name\":$val";
                } else {
                    $fields[] = "\"$name\":" . self::_serializeObject($val);
                }
            } elseif (is_array($val)) {
                $fields[] = "\"$name\":" . self::_serializeArray($val);
            } elseif (is_numeric($val)) {
                $fields[] = "\"$name\":$val";
            } elseif (is_string($val)) {
                $fields[] = "\"$name\":\"$val\"";
            } elseif(is_bool($val)) {
                if ($val) {
                    $fields[] = "\"$name\":true";
                } else {
                    $fields[] = "\"$name\":false";
                }
            }
        }

        return '{' . implode(',', $fields) . '}';
    }
    private static function _serializeArray($src)
    {
        $array = [];
        foreach($src as $key => $element) {
            if (is_numeric($element)) {
                $array[] = $element;
            } elseif (is_string($element)) {
                $array[] = '"'.$element.'"';
            } elseif (is_array($element)) {
                $array[] = self::_serializeArray($element);
            } elseif (is_object($element)) {
                $array[] = self::_serializeObject($element);
            } elseif(is_bool($element)) {
                $array[] = $element ? 'true' : 'false';
            }
        }
        return '[' . implode(',', $array) . ']';
    }

    public function __toString()
    {
        return self::serialize($this);
    }
}
/*</module>*/
?>