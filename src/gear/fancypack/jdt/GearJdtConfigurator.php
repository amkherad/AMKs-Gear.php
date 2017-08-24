<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\controller\GearController;
use gear\arch\helpers\GearHtmlHelper;
use gear\fancypack\jdt\element\JdtTableElement;
use gear\fancypack\jdt\http\results\JdtQueryResult;
use gear\fancypack\jdt\http\results\JdtResult;
use gear\fancypack\jdt\viewhelpers\JqueryDataTablesRenderer;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<generals>*/
GearController::setStaticExtensionMethods([
    'jdtResult' => function ($filterViewModel, $query) {
        return new JdtQueryResult($filterViewModel, $query);
    },
    'jdtFromQuery' => function ($viewModel, $query) {
        return new JdtResult($viewModel, $query);
    },
    'jqueryDataTables' => function ($selector, $options = null) {
        return JqueryDataTablesRenderer::render($selector, $options);
    },
    'jqueryDataTablesElement' => function ($name, $columns, $renderHeader = true, $renderFooter = true) {
        return new JdtTableElement($name, $columns, $renderHeader, $renderFooter);
    }
]);
//GearHtmlHelper::setStaticExtensionMethods([
    //'jqueryDataTables' => function ($id, $options = null) {
    //    return new GearJdtResult($viewModel, $iteratable);
    //}
//]);
/*</generals>*/
?>