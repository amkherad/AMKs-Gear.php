<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\core\client;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearHtmlTargetSelector
{
    function buildSelectorFor($name);
    function buildSelectorForArgs($name, $args);
}
/*</module>*/
?>