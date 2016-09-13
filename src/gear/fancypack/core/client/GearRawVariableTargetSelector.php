<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\core\client;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\fancypack\core\client\IGearHtmlTargetSelector;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearRawVariableTargetSelector implements IGearHtmlTargetSelector
{
    public function buildSelectorFor($name)
    {
        return $name;
    }

    public function buildSelectorForArgs($name, $args)
    {
        return $name;
    }
}
/*</module>*/
?>