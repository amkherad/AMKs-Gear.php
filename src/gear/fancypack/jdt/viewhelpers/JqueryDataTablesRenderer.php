<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\viewhelpers;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearSerializer;
use gear\fancypack\core\client\GearJsOptions;
use gear\fancypack\core\client\GearRawOutput;
use gear\fancypack\jdt\JqueryDataTablesColumnInfo;
use gear\fancypack\jdt\JqueryDataTablesOptions;
use ReflectionClass;
use ReflectionProperty;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JqueryDataTablesRenderer
{
    /**
     * @param string $selector
     * @param JqueryDataTablesOptions $options
     * @param bool $writeScriptTag
     * @return string
     */
    public static function render($selector, $options, $writeScriptTag = false)
    {
        if ($options == null) {
            $options = JqueryDataTablesOptions::createDefault();
        }

        $optionsStr = '';
        if ($options->apiInstance) {
            $funcName = 'DataTable';
            $optionsStr = strval($options);
        } else {
            $funcName = 'dataTable';
            $optionsStr = self::convertToOldApiFromNewApi($options);
        }

        $sb = '';
        if ($writeScriptTag) {
            $sb .= '<script type="text/javascript">';
        }
        $declaration = $options->declareVariable;

        $selector = $options->getTargetSelector()->buildSelectorFor($selector);

        $sb .= $declaration == null
            ? "$selector.$funcName($optionsStr);"
            : "$declaration=$selector.$funcName($optionsStr);";

        if ($writeScriptTag)
            $sb .= '</script>';

        return $sb;
    }

    /**
     * @param JqueryDataTablesOptions $options
     * @return array
     */
    public static function convertToOldApiFromNewApi($options)
    {
        $mapping = [
            'data' => 'aaData',
            'order' => 'aaSorting',
            'orderFixed' => 'aaSortingFixed',
            'columns.orderData' => 'aDataSort',
            'lengthMenu' => 'aLengthMenu',
            'columnDefs.targets' => 'aTargets',
            'columns' => 'aoColumns',
            'columnDefs' => 'aoColumnDefs',
            'searchCols' => 'aoSearchCols',
            'columns.orderSequence' => 'asSorting',
            'stripeClasses' => 'asStripeClasses',
            'autoWidth' => 'bAutoWidth',
            'deferRender' => 'bDeferRender',
            'destroy' => 'bDestroy',
            'searching' => 'bFilter',
            'info' => 'bInfo',
            'jQueryUI' => 'bJQueryUI',
            'lengthChange' => 'bLengthChange',
            'paging' => 'bPaginate',
            'processing' => 'bProcessing',
            'retrieve' => 'bRetrieve',
            'Removed1' => 'bScrollAutoCss',
            'scrollCollapse' => 'bScrollCollapse',
            'Removed2' => 'bScrollInfinite',
            'columns.searchable' => 'bSearchable',
            'serverSide' => 'bServerSide',
            'ordering' => 'bSort',
            'columns.orderable' => 'bSortable',
            'orderCellsTop' => 'bSortCellsTop',
            'orderClasses' => 'bSortClasses',
            'stateSave' => 'bStateSave',
            'Removed3' => 'bUseRendered',
            'columns.visible' => 'bVisible',
            'Removed4' => 'fnCookieCallback',
            'columns.createdCell' => 'fnCreatedCell',
            'createdRow' => 'fnCreatedRow',
            'drawCallback' => 'fnDrawCallback',
            'footerCallback' => 'fnFooterCallback',
            'formatNumber' => 'fnFormatNumber',
            'headerCallback' => 'fnHeaderCallback',
            'infoCallback' => 'fnInfoCallback',
            'initComplete' => 'fnInitComplete',
            'preDrawCallback' => 'fnPreDrawCallback',
            'Removed5' => 'fnRender',
            'rowCallback' => 'fnRowCallback',
            'ajax.data' => '.fnServerData',
            //'ajax' => '.fnServerParams',
            'ajax.type' => '.sServerMethod',
            'ajax.dataSrc' => '.sAjaxDataProp',
            'ajax.url' => '.sAjaxSource',
            'stateLoadCallback' => 'fnStateLoad',
            'stateLoaded' => 'fnStateLoaded',
            'stateLoadParams' => 'fnStateLoadParams',
            'stateSaveCallback' => 'fnStateSave',
            'stateSaveParams' => 'fnStateSaveParams',
            'stateDuration' => 'iCookieDuration',
            //'columns.orderData' => 'iDataSort',
            'deferLoading' => 'iDeferLoading',
            'pageLength' => 'iDisplayLength',
            'displayStart' => 'iDisplayStart',
            'Removed6' => 'iScrollLoadGap',
            'tabIndex' => 'iTabIndex',
            'columns.data' => 'mData',
            'columns.render' => 'mRender',
            'language.aria.sortAscending' => 'oLanguage.oAria.sSortAscending',
            'language.aria.sortDescending' => 'oLanguage.oAria.sSortDescending',
            'language.paginate.first' => 'oLanguage.oPaginate.sFirst',
            'language.paginate.last' => 'oLanguage.oPaginate.sLast',
            'language.paginate.next' => 'oLanguage.oPaginate.sNext',
            'language.paginate.previous' => 'oLanguage.oPaginate.sPrevious',
            'language.emptyTable' => 'oLanguage.sEmptyTable',
            'language.info' => 'oLanguage.sInfo',
            'language.infoEmpty' => 'oLanguage.sInfoEmpty',
            'language.infoFiltered' => 'oLanguage.sInfoFiltered',
            'language.infoPostFix' => 'oLanguage.sInfoPostFix',
            'language.thousands' => 'oLanguage.sInfoThousands',
            'language.lengthMenu' => 'oLanguage.sLengthMenu',
            'language.loadingRecords' => 'oLanguage.sLoadingRecords',
            'language.processing' => 'oLanguage.sProcessing',
            'language.search' => 'oLanguage.sSearch',
            'language.url' => 'oLanguage.sUrl',
            'language.zeroRecords' => 'oLanguage.sZeroRecords',
            'search' => 'oSearch',
            'columns.cellType' => 'sCellType',
            'columns.className' => 'sClass',
            'contentPadding' => 'sContentPadding',
            'Removed7' => 'sCookiePrefix',
            'columns.defaultContent' => 'sDefaultContent',
            'dom' => 'sDom',
            'columns.name' => 'sName',
            'pagingType' => 'sPaginationType',
            'scrollX' => 'sScrollX',
            'scrollXInner' => 'sScrollXInner',
            'scrollY' => 'sScrollY',
            'columns.orderDataType' => 'sSortDataType',
            'columns.title' => 'sTitle',
            'columns.type' => 'sType',
            'columns.width' => 'sWidth',
        ];

        return self::_convertToOldApi($mapping, null, $options, null);
    }

    /**
     * @param array $mapping
     * @param object $rootObj
     * @param object $obj
     * @param string $level
     * @return array
     */
    private static function _convertToOldApi($mapping, $rootObj, $obj, $level)
    {
        $reflection = new ReflectionClass($obj);
        $props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $fields = [];
        foreach ($props as $prop) {
            if (stripos($prop->getDocComment(), GearJsOptions::JsonEscapeComment) !== false) {
                continue;
            }
            $val = $prop->getValue($obj);
            $name = $prop->getName();
            $stageName = "$level$name";
            //echo "$stageName\n";//continue;
            $target = &$fields;
            if (isset($mapping[$stageName])) {
                $name = $mapping[$stageName];
                //$fields[$name] = '';
                if (substr($name, 0, 1) == '.') {
                    $name = substr($name, 1);
                    if ($rootObj != null) {
                        $target = &$rootObj;
                    }
                }
            }

            if (is_object($val)) {
                if ($val instanceof GearRawOutput) {
                    $val = strval($val);
                    $target[] = "\"$name\":$val";
                } else {
                    $target[] = "\"$name\":" . self::_convertToOldApi(
                            $mapping,
                            $rootObj == null ? $fields : $rootObj,
                            $val,
                            "$level$name.");
                }
            } elseif (is_array($val)) {
                $target[] = "\"$name\":" . self::_convertToOldApiArray($mapping, $rootObj, $val, "$level$name.");
            } elseif (is_numeric($val)) {
                $target[] = "\"$name\":$val";
            } elseif (is_string($val)) {
                $target[] = "\"$name\":\"$val\"";
            } elseif(is_bool($val)) {
                if ($val) {
                    $target[] = "\"$name\":true";
                } else {
                    $target[] = "\"$name\":false";
                }
            }
        }

        return '{' . implode(',', $fields) . '}';
    }
    private static function _convertToOldApiArray($mapping, $rootObj, $src, $level)
    {
        $array = [];
        foreach($src as $key => $element) {
            if (is_numeric($element)) {
                $array[] = $element;
            } elseif (is_string($element)) {
                $array[] = '"'.$element.'"';
            } elseif (is_array($element)) {
                $array[] = self::_convertToOldApiArray($mapping, $rootObj, $element, $level);
            } elseif (is_object($element)) {
                $array[] = self::_convertToOldApi($mapping, $rootObj, $element, $level);
            } elseif(is_bool($element)) {
                $array[] = $element ? 'true' : 'false';
            }
        }
        return '[' . implode(',', $array) . ']';
    }
}
/*</module>*/
?>