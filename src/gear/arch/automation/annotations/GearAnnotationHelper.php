<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\automation\annotations;
    /*</namespace.current>*/
    /*<namespace.use>*/
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

    /*<module>*/
/**
 * Class GearAnnotationHelper helps to extract information from doc comments.
 * Format: @annotationName(arg1=23,arg2='test')
 *
 * @package gear\arch\automation\annotations
 */
class GearAnnotationHelper
{
    private $name;
    private $rawValue;
    private $args;

    private $caseSensitive;

    public function getName()
    {
        return $this->name;
    }

    public function getArg($name, $defaultValue = null)
    {
        if (!$this->caseSensitive) {
            $name = strtolower($name);
        }

        return isset($this->args[$name])
            ? $this->args[$name]
            : $defaultValue;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function getValue()
    {
        return $this->rawValue;
    }

    public function isCaseSensitive()
    {
        return $this->caseSensitive;
    }

    public function __construct($name, $rawArgs, $caseSensitive)
    {
        $this->args = [];
        $args = preg_split('/(,)(?=(?:[^"]|"[^"]*")*$)/', $rawArgs);

        foreach ($args as $arg) {
            $eqPos = strpos($arg, '=');
            $key = '';
            $value = '';
            if ($eqPos !== false) {
                $fP = trim(substr($arg, 0, $eqPos), " \t\n\r\0\x0B\"'*");
                $sP = trim(substr($arg, $eqPos + 1), " \t\n\r\0\x0B\"'*");

                $key = $fP;
                $value = $sP;
            } else {
                $key = $arg;
                $value = $arg;
            }

            if (is_numeric($value)) {
                if (is_integer($value)) {
                    $value = intval($value);
                } else {
                    $value = floatval($value);
                }
            } elseif (strtolower($value) == 'false') {
                $value = false;
            } elseif (strtolower($value) == 'true') {
                $value = true;
            }

            if ($caseSensitive) {
                $this->args[$key] = $value;
            } else {
                $this->args[strtolower($key)] = $value;
            }
        }
    }

    /**
     * @param $str
     * @return GearAnnotationHelper[]
     */
    public static function exportAnnotations($str)
    {
        $lines = explode(PHP_EOL, $str);

        $annotations = [];
        foreach ($lines as $line) {
            $atSign = strpos($line, '@');
            if ($atSign !== null) {
                $annotations[] = substr($line, $atSign);
            }
        }

        return $annotations;
    }

    /**
     * @param $str
     * @param $name
     * @return GearAnnotationHelper
     */
    public static function exportAnnotation($str, $name, $caseSensitive = true)
    {
        $pos = stripos($str, "@$name");
        if ($pos === false) {
            return null;
        }

        $nlPos = stripos($str, PHP_EOL, $pos);
        if ($nlPos === false || $nlPos <= $pos) {
            $annotation = substr($str, $pos);
        } else {
            $oPrant = strpos($str, '(', $pos);
            if ($oPrant !== false) {
                $close = strpos($str, ')', $oPrant);
                $annotation = substr($str, $oPrant + 1, $close - $oPrant - 1);
            } else {
                $annotation = substr($str, $pos, $nlPos - $pos);
            }
        }

        $pStart = strpos($annotation, '(');
        if ($pStart !== false) {
            $pEnd = strpos($annotation, ')');
            if ($pEnd !== false) {
                $annotation = substr($annotation, $pStart + 1, $pEnd - $pStart - 1);
            }
        }

        return new GearAnnotationHelper($name, $annotation, $caseSensitive);
    }
}

/*</module>*/
?>