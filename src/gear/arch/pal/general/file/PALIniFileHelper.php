<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\pal\file;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class PALIniFileHelper
{
    public static function ParseIniFile($path, $processSections)
    {
        return parse_ini_file($path, $processSections);
    }
}
/*</module>*/
?>