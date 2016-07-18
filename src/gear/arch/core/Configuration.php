<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\core;
/*</namespaces>*/

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