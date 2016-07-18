<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
Bundle::Arch('core/IContext');
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IActionResult
{
    function Execute(IContext $context);
}
/*</module>*/
?>