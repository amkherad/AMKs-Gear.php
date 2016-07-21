<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\pal\file;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearPALIniFileHelper
{
    public static function ParseIniFile($path, $processSections)
    {
        return parse_ini_file($path, $processSections);
    }
}
/*</module>*/
?>