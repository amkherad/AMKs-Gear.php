<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\viewhelpers;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\helpers\GearHtmlHelper;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<generals>*/
GearHtmlHelper::setStaticExtensionMethods([
    'jdtResult' => function ($viewModel, $iteratable) {
        return new GearJdtResult($viewModel, $iteratable);
    }
]);
/*</generals>*/
?>