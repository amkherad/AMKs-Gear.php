<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\viewhelpers\section;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHtmlSections
{
    public static $sections = array();
    public static $sectionStarted = false;
    public static $currentSection;

    public static function getSection($sectionName)
    {
        if (!isset(self::$sections[$sectionName])) return null;
        return self::$sections[$sectionName]['content'];
    }

    public static function sectionExists($sectionName)
    {
        return isset(self::$sections[$sectionName]);
    }

    public static function renderSection($sectionName)
    {
        if (!isset(self::$sections[$sectionName])) return;
        echo self::$sections[$sectionName]['content'];
    }

    public static function beginSection($sectionName)
    {
        if (isset(self::$currentSection)) throw new GearInvalidOperationException("Another html section has already been started.");
        foreach (self::$sections as $section)
            if ($section['id'] == $sectionName) throw new GearInvalidOperationException("Html section '$sectionName' has already been started.");
        ob_start();
        self::$currentSection = array('id' => $sectionName, 'started' => true, 'content' => null);
    }

    public static function endSection($sectionName = null)
    {
        if (!isset(self::$currentSection)) throw new GearInvalidOperationException("No html section has been started.");
        $id = self::$currentSection['id'];
        self::$sections[$id] = self::$currentSection;
        self::$currentSection = null;
        self::$sections[$id]['content'] = ob_get_clean();
    }
}
/*</module>*/
?>