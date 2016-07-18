<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\core;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class Configuration
{
    private $c;

    private function __construct($configFile, $type)
    {
        $bundle = Bundle::Pal('file\IniFile');
        print_r($bundle);
        echo phpversion ();
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
        return new self($path, "ini");
    }

    public static function FromXmlFile($path)
    {
        return new self($path, "xml");
    }
}
/*</module>*/
?>