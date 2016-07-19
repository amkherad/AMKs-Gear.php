<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
use gear\arch\pal\file\IniFile;
use gear\arch\pal\file\IniFileHelper;

class Configuration
{
    private $c;

    private function __construct($configArray, $type)
    {
        $this->c = $configArray;
    }

    public function getSection($section)
    {
        return isset($this->c[$section])
            ? $this->c[$section]
            : null;
    }

    public function getValue($value, $section = null, $defaultValue = null)
    {
        if (isset($section)) {
            $result = isset($this->c[$section][$value])
                ? $this->c[$section][$value]
                : $defaultValue;
        } else {
            $result = isset($this->c[$value])
                ? $this->c[$value]
                : $defaultValue;
        }
        return $result == null
            ? $defaultValue
            : $result;
    }

    public static function FromFile($path, $type = 0)
    {
        return $type == 0
            ? self::FromIniFile($path)
            : self::FromXmlFile($path);
    }

    public static function FromIniFile($path)
    {
        Bundle::Pal('file\PALIniFileHelper');
        return new self(PALIniFileHelper::ParseIniFile($path, true), "ini");
    }

    public static function FromXmlFile($path)
    {
        return new self('', "xml");
    }
}

/*</module>*/
?>