<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\GearBundle;
use gear\arch\pal\file\GearPALIniFileHelper;
/*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/

class GearConfiguration
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

    /**
     * @param $value string
     * @param null $section string
     * @param null $defaultValue string
     *
     * @return string
     */
    public function getValue($value, $section = null, $defaultValue = null)
    {
        if (isset($section)) {
            $result = isset($this->c[$section][$value])
                ? $this->c[$section][$value]
                : null;
        } else {
            $result = isset($this->c[$value])
                ? $this->c[$value]
                : null;
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
        return new self(GearPALIniFileHelper::ParseIniFile($path, true), "ini");
    }

    public static function FromXmlFile($path)
    {
        return new self('', "xml");
    }
}

/*</module>*/
?>