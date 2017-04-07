<?php
//Bundle: jQueryDataTables

/* Dependencies: */

GearBundle::dependency('gear.fancypack');



/* Modules: */
interface IJqueryDataTablesFilter
{
    /**
     * @return int
     */
    function getDraw();
    /**
     * @param int $draw
     * @return void
     */
    function setDraw($draw);

    /**
     * @return bool
     */
    function getApiInstance();
    /**
     * @param bool $apiInstance
     * @return void
     */
    function setApiInstance($apiInstance);

    /**
     * @return int|null
     */
    function getStart();
    /**
     * @param int|null $start
     * @return void
     */
    function setStart($start);

    /**
     * @return int|null
     */
    function getLength();
    /**
     * @param int|null $length
     * @return void
     */
    function setLength($length);

    /**
     * @return string
     */
    function getGeneralFilter();
    /**
     * @param string $generalFilter
     * @return void
     */
    function setGeneralFilter($generalFilter);
    /**
     * Returns string compare mode.
     *
     * @return string
     */
    function getCompareMode();
    /**
     * @param string $compareMode
     * @return void
     */
    function setCompareMode($compareMode);

    /**
     * @return array
     */
    function getColumns();
    /**
     * @param array $columns
     * @return void
     */
    function setColumns($columns);

    /**
     * @return mixed
     */
    function getExtendedOptions();
    /**
     * @param mixed $exOptions
     * @return void
     */
    function setExtendedOptions($exOptions);
}
interface IJqueryDataTablesFilterViewModel
{
    /**
     * Used to determine that the internal filterer should filter query.
     *
     * @return bool
     */
    function useAutoFilterer();
    /**
     * @param JqueryDataTablesFilter $filterModel
     * @param GearQueryBuilder $query
     * @return GearQueryBuilder
     */
    function orderRows($filterModel, $query);

    /**
     * @param JqueryDataTablesFilter $filterModel
     * @param GearQueryBuilder $query
     * @return GearQueryBuilder
     */
    function filterRows($filterModel, $query);

    /**
     * @param JqueryDataTablesFilter $filterModel
     * @param GearQueryBuilder $query
     * @return mixed
     */
    function processRows($filterModel, $query);

    /**
     * @param JqueryDataTablesFilter $filterModel
     * @param mixed $row
     * @return mixed
     */
    function processRow($filterModel, $row);
}
class JdtQueryResult extends GearJsonResult
{
    /** @var IJqueryDataTablesFilterViewModel */
    private $filterViewModel;
    
    /** @var bool */
    public $countFiltereds = true;
    
    /**
     * GearJdtResult constructor.
     * @param IJqueryDataTablesFilterViewModel $filterViewModel
     * @param GearQueryBuilder $query
     * @throws GearArgumentNullException
     */
    public function __construct($filterViewModel, $query)
    {
        if (!($filterViewModel instanceof IJqueryDataTablesFilterViewModel)) {
            throw new GearArgumentNullException('filterViewModel');
        }

        $this->filterViewModel = $filterViewModel;

        parent::__construct($query, true);
    }

    public function createJson($context, $request, $response, $query)
    {
        //$array = null;
        //if (is_array($content)) {
        //    $array = $content;
        //} elseif (is_null($content)) {
        //    $array = [];
        //} else {
        //    $array = iterator_to_array($content);
        //}

        return JqueryDataTablesDriver::createJqueryDataTablesResult($query, $request, $this->filterViewModel, $this->countFiltereds);
    }
}
class JdtResult extends GearJsonResult
{
    /** @var IJqueryDataTablesViewModel */
    private $viewModel;
    /**
     * GearJdtResult constructor.
     * @param array $arraySerializable
     * @param IJqueryDataTablesViewModel $viewModel
     * @throws GearArgumentNullException
     */
    public function __construct($viewModel, $arraySerializable)
    {
        if (!($viewModel instanceof IJqueryDataTablesViewModel)) {
            throw new GearArgumentNullException('viewModel');
        }

        $this->viewModel = $viewModel;

        parent::__construct($arraySerializable, true);
    }

    public function writeResult($context, $request, $response, $json)
    {
        $array = null;
        if (is_array($this->content)) {
            $array = $this->content;
        } else {
            $array = iterator_to_array($this->content);
        }

        //$jdtContext = new JqueryDataTablesContext($context, $request, $response);
//
        //$viewModel = $this->viewModel;
//
        //$array = $viewModel->filterRows($jdtContext, $array);
        //$array = $viewModel->orderRows($jdtContext, $array);
        //$array = $viewModel->processRows($jdtContext, $array);
//
        //$response->write(GearSerializer::json($array));
    }
}
class JdtTableElement extends GearHtmlElement
{
    private
        $columns,
        $renderHeader,
        $renderFooter;

    public function __construct($id, $columns, $renderHeader = true, $renderFooter = true)
    {
        $this->setAttribute('id', $id);

        $this->columns = $columns;
        $this->renderHeader = $renderHeader;
        $this->renderFooter = $renderFooter;
    }

    /**
     * @param IGearOutputStream $stream
     * @return void
     */
    public function renderToStream($stream)
    {
        $thead = '<thead><tr>';
        $tfoot = '<tfoot><tr>';
        $tbody = '<tbody><tr>';
        foreach($this->columns as $column) {
            $thead .= "<th>{$column->title}</th>";
            $tfoot .= "<td>{$column->title}</td>";
            //$tbody .= "<td></td>";
        }
        $thead .= '</tr></thead>';
        $tfoot .= '</tr></tfoot>';
        $tbody .= '</tr></tbody>';

        $stream->write('<table');
        $stream->write($this->getSerializedAttributes());
        $stream->write('>');
        $stream->write($thead);
        $stream->write($tbody);
        $stream->write($tfoot);
        $stream->write('</table>');
    }
}
class JqueryDataTablesAriaLanguagePack
{
    /** @var string */
    public $sortAscending;
    /** @var string */
    public $sortDescending;
}
class JqueryDataTablesColumnDefinition extends GearJsOptions
{
    /** @var array $targets */
    public $targets;

    /** @var bool $visible */
    public $visible;

    /** @var string $data */
    public $data;

    /** @var mixed $render */
    public $render;
}
class JqueryDataTablesColumnInfo extends GearJsOptions
{
    const JdtIgnore = 'JdtIgnore';
    const JdtAnnotation = 'Jdt';

    /** @var string */
    public $name;
    /** @var string */
    public $data;
    /** @var string */
    public $orderData;
    /** @var bool */
    public $orderable;
    /** @var bool */
    public $searchable;
    /** @var string */
    public $filter;
    /** @var string */
    public $title;
    /** @var bool */
    public $isRegex;
    /** @var bool */
    public $visible;
    /** @var mixed */
    public $type;
    /** @var mixed */
    public $cellType;
    /** @var mixed */
    public $width;
    /** @var mixed */
    public $defaultContent;
    /** @var mixed */
    public $targets;
    /** @var mixed */
    public $contentPadding;

    /** @var JqueryDataTablesSearchModel */
    public $search;

    /** @var mixed */
    public $render;
    /** @var mixed */
    public $createdCell;

    /** @var string */
    public $filterMode;
    /** @var bool */
    public $filterUseRemoteData;
    /** @var string */
    public $filterRemoteDataUrl;
    /** @var string */
    public $filterRemoteDataAjaxRequestType;
    /** @var string */
    public $filterRemoteDataAjaxRequestData;
    /** @var mixed */
    public $filterList;
    /** @var string */
    public $filterPlaceHolder;
    /** @var string */
    public $filterRemoteDataController;
    /** @var string */
    public $filterRemoteDataAction;
    /** @var string */
    public $filterTrueDisplayName;
    /** @var string */
    public $filterFalseDisplayName;
    /** @var bool */
    public $filterAddNoFilter;
    /** @var string */
    public $filterNoFilterDisplayName;


    /**
     * @JsonIgnore
     *
     * @var int
     */
    public $modelOrder;

    private function __construct($name, $doc)
    {
        $this->name = $name;
        $this->data = $name;
        $this->title = $name;

        if ($doc != null) {
            $annotation = GearAnnotationHelper::exportAnnotation($doc, self::JdtAnnotation);

            if ($annotation != null) {
                $thisReflection = new ReflectionClass($this);
                $props = $thisReflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

                foreach ($props as $prop) {
                    $pName = $prop->getName();
                    $an = $annotation->getArg($pName);
                    if ($an !== null) {
                        $this->$pName = $an;
                    }
                }
            }
        }
    }

    /**
     * Creates a new instance of JqueryDataTablesColumnInfo
     *
     * @param string $name
     * @param string $title
     * @param string $data
     *
     * @return JqueryDataTablesColumnInfo
     */
    public static function create($name, $title = null, $data = null) {
        $instance = new self($name, null);

        $instance->title = $title;
        $instance->data = $data;

        return $instance;
    }

    /**
     * Creates a list of columns from a view model.
     *
     * @param object $model
     * @param IActionUrlBuilder $urlBuilder
     *
     * @return array
     */
    public static function fromModel($model, $urlBuilder)
    {
        $reflection = new ReflectionClass($model);

        $props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $result = [];
        foreach ($props as $prop) {
            $doc = $prop->getDocComment();
            if (stripos($doc, '@'.self::JdtIgnore) !== false) {
                continue;
            }

            $field = new JqueryDataTablesColumnInfo(
                $prop->getName(),
                $doc
            );

            if (!GearHelpers::isNullOrWhitespace($field->filterRemoteDataAction)) {
                $field->filterRemoteDataUrl = $urlBuilder->action(
                    $field->filterRemoteDataAction,
                    $field->filterRemoteDataController
                );
            }

            $result[] = $field;
        }

        usort($result, function ($a, $b) {
            $a = $a->modelOrder;
            $b = $b->modelOrder;
            if (!is_numeric($a)) {
                if (!is_numeric($b)) {
                    return 0;
                } else {
                    return 1;
                }
            }
            if (!is_numeric($b)) {
                return -1;
            }
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        $orderAfter = [];
        $orderBefore = [];
        $retVal = [];
        foreach ($result as $ko => $ro) {
            $mo = $ro->modelOrder;
            $colIndex = strpos($mo, ':');
            if ($colIndex !== false) {
                $action = substr($mo, 0, $colIndex);
                if ($action == 'before') {
                    $orderBefore[$ko] = substr($mo, $colIndex + 1);
                } else {
                    $orderAfter[$ko] = substr($mo, $colIndex + 1);
                }
            } else {
                $retVal[] = $ro;
            }
        }

        do {
            $action = false;
            foreach ($orderAfter as $ak => $order) {
                foreach ($retVal as $bk => $r) {
                    if ($r->name == $order) {
                        array_splice($retVal, $bk + 1, 0, [$result[$ak]]);
                        unset($orderAfter[$ak]);
                        $action = true;
                        break;
                    }
                }
            }
        } while ($action && false);

        do {
            $action = false;
            foreach ($orderBefore as $ak => $order) {
                foreach ($retVal as $bk => $r) {
                    if ($r->name == $order) {
                        array_splice($retVal, $bk, 0, [$result[$ak]]);
                        unset($orderBefore[$ak]);
                        $action = true;
                        break;
                    }
                }
            }
        } while ($action && false);

        return $retVal;
    }
}
class JqueryDataTablesDriver
{
    /**
     * @param IGearHttpRequest $request
     * @param bool $apiInstance
     *
     * @return array
     */
    public static function getOrders($request, $apiInstance)
    {
        $result = [];

        $index = 0;
        for (; ;) {
            $orderCol = $request->getValue($apiInstance ? "order[$index][column]" : "iSortCol_$index");
            if (GearHelpers::isNullOrWhitespace($orderCol)) break;
            $col = ctype_digit($orderCol) ? intval($orderCol) : null;
            if ($col != null) {
                $result[$col] = $request->getValue($apiInstance ? "order[$index][dir]" : "sSortDir_$index");
            }
            $index++;
        }

        return $result;
    }

    /**
     * @param IGearHttpRequest $request
     * @param bool $apiInstance
     * @param array $orders
     * @param int $index
     *
     * @return null
     */
    public static function readColumn($request, $apiInstance, $orders, $index)
    {
        $name = $request->getValue($apiInstance ? "columns[$index][name]" : "mDataProp_$index");
        if (GearHelpers::isNullOrWhitespace($name)) {
            return null;
        }
        $data = $request->getValue($apiInstance ? "columns[$index][data]" : "mDataProp_$index");
        $orderable = boolval($request->getValue($apiInstance ? "columns[$index][orderable]" : "bSortable_$index"));
        $searchable = boolval($request->getValue($apiInstance ? "columns[$index][searchable]" : "bSearchable_$index"));
        $order = null;
        $filter = null;
        $filterIsRegex = false;
        if ($searchable) {
            $filter = $request->getValue($apiInstance ? "columns[$index][search][value]" : "sSearch_$index");
            $filterIsRegex = boolval($request->getValue($apiInstance ? "columns[$index][search][regex]" : "bRegex_$index"));
        }

        if ($orderable) {
            if (isset($orders[$index])) {
                $order = $orders[$index];
            }
        }

        return [
            'name' => $name,
            'data' => $data,
            'order' => $order,
            'filters' => [
                $filter
            ],
            'compareMode' => $filterIsRegex ? 'regex' : 'contains'
        ];
    }

    /**
     * @param IGearHttpRequest $request
     * @param IJqueryDataTablesFilter $filterModel
     *
     * @throws GearArgumentNullException
     * @return array
     */
    public static function fillJqueryDataTablesFilter($request, $filterModel)
    {
        if ($request == null) {
            throw new GearArgumentNullException('request');
        }
        if ($filterModel == null) {
            throw new GearArgumentNullException('filterModel');
        }

        $apiInstance = $request->getValue("apiInstance", true);
        $filterModel->setApiInstance($apiInstance);

        $filterModel->setDraw($request->getValue($apiInstance ? 'draw' : 'sEcho'));

        $columns = [];
        $orders = self::getOrders($request, $apiInstance);

        $index = 0;
        $col = self::readColumn($request, $apiInstance, $orders, 0);
        while ($col != null) {
            $columns[] = $col;
            $index++;
            $col = self::readColumn($request, $apiInstance, $orders, $index);
        }

        $filterModel->setGeneralFilter($request->getValue($apiInstance ? 'search[value]' : 'sSearch'));
        $regex = boolval($request->getValue($apiInstance ? 'search[regex]' : 'bRegex'));
        $filterModel->setCompareMode($regex ? 'regex' : 'contains');

        $startStr = $request->getValue($apiInstance ? 'start' : 'iDisplayStart');
        $lengthStr = $request->getValue($apiInstance ? 'length' : 'iDisplayLength');

        if (ctype_digit($startStr)) {
            $filterModel->setStart(intval($startStr));
        }
        if (ctype_digit($lengthStr)) {
            $filterModel->setLength(intval($lengthStr));
        }

        return $columns;
    }

    /**
     * @param GearQueryBuilder $query
     *
     * @param IJqueryDataTablesFilter $filterModel
     * @return GearQueryBuilder
     */
    public static function createInternalFiltererFiltersOnQuery(
        $query,
        $filterModel
    )
    {
        $query = clone $query;



        return $query;
    }

    /**
     * @param GearQueryBuilder $query
     * @param IGearHttpRequest $request
     * @param IJqueryDataTablesFilterViewModel $filterViewModel
     * @param JqueryDataTablesFilter $filterModel
     *
     * @return array
     * @throws GearArgumentNullException
     */
    public static function createJqueryDataTablesFilter(
        $query,
        $request,
        $filterViewModel,
        $filterModel,
        $countFiltereds = true
    )
    {
        $columns = self::fillJqueryDataTablesFilter($request, $filterModel);
        $filterModel->setColumns($columns);

        if ($filterViewModel->useAutoFilterer()) {
            $query = self::createInternalFiltererFiltersOnQuery($query, $filterModel);
        }

        $query = $filterViewModel->filterRows($filterModel, $query);

        $filteredCount = $countFiltereds ? $query->count() : 0;

        $query = $filterViewModel->orderRows($filterModel, $query);

        //$skipNull = $filterModel->getStart();
        //$skip = ctype_digit($skipNull) ? intval($skipNull) : 0;
        $skip = $filterModel->getStart();

        $pageSizeNull = $filterModel->getLength();
        $pageSize = ctype_digit($pageSizeNull) ? intval($pageSizeNull) : 10;

        $query = $query
            ->skip($skip)
            ->take($pageSize);

        //$data = $query->select();

        return [
            'result' => $query,
            'count' => (int)$filteredCount
        ];
    }

    /**
     * @param GearQueryBuilder $query
     * @param IGearHttpRequest $request
     * @param IJqueryDataTablesFilterViewModel $filterViewModel
     *
     * @return array
     */
    public static function createJqueryDataTablesResult(
        $query,
        $request,
        $filterViewModel,
        $countFiltereds = true
    )
    {
        $filterModel = new JqueryDataTablesFilter();

        try {
            $total = (int)$query->count();

            $result = self::createJqueryDataTablesFilter(
                $query,
                $request,
                $filterViewModel,
                $filterModel,
                $countFiltereds
            );

            $records = $filterViewModel->processRows($filterModel, $result['result']);

            if ($filterModel->getApiInstance()) {
                return [
                    'draw' => $filterModel->getDraw(),
                    'recordsTotal' => $total,
                    'recordsFiltered' => $result['count'],
                    'data' => $records
                ];
            } else {
                return [
                    'sEcho' => $filterModel->getDraw(),
                    'iTotalRecords' => $total,
                    'iTotalDisplayRecords' => $result['count'],
                    'aaData' => $records
                ];
            }
        } catch (\Exception $ex) {
            $uid = null;
            $error = GearErrorStrategy::saveLogAndGetTrace($ex, $uid);
            if ($filterModel->getApiInstance()) {
                return [
                    'draw' => $filterModel->getDraw(),
                    'error' => $error,
                    'errorTackId' => $uid
                ];
            } else {
                return [
                    'sEcho' => $filterModel->getDraw(),
                    'sError' => $error,
                    'errorTackId' => $uid
                ];
            }
        }
    }
}
class JqueryDataTablesFiltererInitializer
{
    public static function renderInitializer($useApi = true, $addScriptTag = true)
    {
        $filterTypeNone = JqueryDataTablesOptions::JdtColumnFilterModeNone;
        $filterTypeList = JqueryDataTablesOptions::JdtColumnFilterModeList;
        $filterTypeText = JqueryDataTablesOptions::JdtColumnFilterModeText;
        $filterTypeBoolean = JqueryDataTablesOptions::JdtColumnFilterModeBoolean;
        $filterTypeDateTime = JqueryDataTablesOptions::JdtColumnFilterModeDateTime;

        if ($useApi) {
            $outputHtml = <<<JavaScript
    function _listDataTableFiltererInitializer(obj) {
        // Apply the search
        obj.columns().every(function(colIndx) {
            var that = this;

            var column = this.settings()[0].aoColumns[colIndx];
            var individualColumnInfo = column;
            var filterOnEnter = this.settings()[0].filterOnEnter;

            var innerHtml;
            if (column.bSearchable) {
                individualColumnInfo = $.extend({
                    filterList: null,
                    filterMode: 'none',
                    filterUseRemoteData: false,
                    filterRemoteDataUrl: 'jdtRemoteData',
                    filterRemoteDataAjaxRequestType: 'POST',
                    filterRemoteDataAjaxRequestData: 'defaultUrl',
                    filterPlaceHolder: null,
                    filterTrueDisplayName: 'صحیح',
                    filterFalseDisplayName: 'غلط',
                    filterAddNoFilter: true,
                    filterNoFilterDisplayName: '(نمایش همه)'
                }, individualColumnInfo);
                switch (individualColumnInfo.filterMode) {
                    case '{$filterTypeNone}':

                    case '{$filterTypeText}':
                        {
                            innerHtml = (individualColumnInfo.placeHolder == null
                                    ? $('<input type="text" placeholder="جستجو ' + column.title + '..." />')
                                    : $('<input type="text" placeholder="' + individualColumnInfo.placeHolder + '" />'))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeBoolean}':
                        {
                            innerHtml = $('<select></select>');
                            if (individualColumnInfo.addNoFilter) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', '')
                                        .text(individualColumnInfo.noFilterDisplayName));
                            }
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', true)
                                    .text(individualColumnInfo.trueDisplayName))
                                .append($('<option></option>')
                                    .attr('value', false)
                                    .text(individualColumnInfo.falseDisplayName))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeList}':{
                        innerHtml = $('<select></select>')
                            .addClass('jdtInput selectInput');

                        if (individualColumnInfo.addNoFilter) {
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', '')
                                    .text(individualColumnInfo.noFilterDisplayName));
                        }

                        if (individualColumnInfo.useRemoteData) {
                            $.ajax({
                                url: individualColumnInfo.remoteDataUrl,
                                type: individualColumnInfo.remoteDataAjaxRequestType,
                                data: individualColumnInfo.remoteDataAjaxRequestData,
                                success: function(data) {
                                    $(data).each(function(idx, val) {
                                        innerHtml
                                            .append($('<option></option>')
                                                .attr('value', val[0])
                                                .text(val[1]));
                                    });
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    if (individualColumnInfo.filterList != null) {
                                        $(individualColumnInfo.filterList).each(function(idx, val) {
                                            innerHtml
                                                .append($('<option></option>')
                                                    .attr('value', val[0])
                                                    .text(val[1]));
                                        });
                                    }
                                }
                            });
                        } else if (individualColumnInfo.filterList != null) {
                            $(individualColumnInfo.filterList).each(function(idx, val) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', val[0])
                                        .text(val[1]));
                            });
                        } else {
                            this.data().unique().sort().each(function(d, j) {
                                innerHtml.append('<option value="' + d + '">' + d + '</option>');
                            });
                        }

                        break;
                    }
                    case '{$filterTypeDateTime}':{
                        innerHtml = $('<input/>')
                            .addClass('jdtInput dateTimeInput');

                        break;
                    }
                }
            }
            if (innerHtml != null)
                innerHtml = innerHtml.appendTo($(this.footer()).empty());

            if (filterOnEnter) {
                $('.jdtInput', this.footer()).keypress(function(e) {
                    if (e.which == 13) {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                        return false;
                    }
                });
            } else {
                $('.jdtInput', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            }
        });
    }
JavaScript;
        } else {
            $outputHtml = <<<JavaScript
    function _listDataTableFiltererInitializer(obj) {
        // Apply the search
        $.each(obj.aoColumns, function(colIndx, column) {
            var that = this;

            var individualColumnInfo = column;
            var filterOnEnter = this.settings()[0].filterOnEnter;

            var innerHtml;
            if (column.bSearchable) {
                individualColumnInfo = $.extend({
                    filterList: null,
                    filterMode: 'none',
                    filterUseRemoteData: false,
                    filterRemoteDataUrl: 'jdtRemoteData',
                    filterRemoteDataAjaxRequestType: 'POST',
                    filterRemoteDataAjaxRequestData: 'defaultUrl',
                    filterPlaceHolder: null,
                    filterTrueDisplayName: 'صحیح',
                    filterFalseDisplayName: 'غلط',
                    filterAddNoFilter: true,
                    filterNoFilterDisplayName: '(نمایش همه)'
                }, individualColumnInfo);
                switch (individualColumnInfo.filterMode) {
                    case '{$filterTypeNone}':

                    case '{$filterTypeText}':
                        {
                            innerHtml = (individualColumnInfo.placeHolder == null
                                    ? $('<input type="text" placeholder="جستجو ' + column.title + '..." />')
                                    : $('<input type="text" placeholder="' + individualColumnInfo.placeHolder + '" />'))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeBoolean}':
                        {
                            innerHtml = $('<select></select>');
                            if (individualColumnInfo.addNoFilter) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', '')
                                        .text(individualColumnInfo.noFilterDisplayName));
                            }
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', true)
                                    .text(individualColumnInfo.trueDisplayName))
                                .append($('<option></option>')
                                    .attr('value', false)
                                    .text(individualColumnInfo.falseDisplayName))
                                .addClass('jdtInput textInput');

                            break;
                        }
                    case '{$filterTypeList}':{
                        innerHtml = $('<select></select>')
                            .addClass('jdtInput selectInput');

                        if (individualColumnInfo.addNoFilter) {
                            innerHtml
                                .append($('<option></option>')
                                    .attr('value', '')
                                    .text(individualColumnInfo.noFilterDisplayName));
                        }

                        if (individualColumnInfo.useRemoteData) {
                            $.ajax({
                                url: individualColumnInfo.remoteDataUrl,
                                type: individualColumnInfo.remoteDataAjaxRequestType,
                                data: individualColumnInfo.remoteDataAjaxRequestData,
                                success: function(data) {
                                    $(data).each(function(idx, val) {
                                        innerHtml
                                            .append($('<option></option>')
                                                .attr('value', val[0])
                                                .text(val[1]));
                                    });
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    if (individualColumnInfo.filterList != null) {
                                        $(individualColumnInfo.filterList).each(function(idx, val) {
                                            innerHtml
                                                .append($('<option></option>')
                                                    .attr('value', val[0])
                                                    .text(val[1]));
                                        });
                                    }
                                }
                            });
                        } else if (individualColumnInfo.filterList != null) {
                            $(individualColumnInfo.filterList).each(function(idx, val) {
                                innerHtml
                                    .append($('<option></option>')
                                        .attr('value', val[0])
                                        .text(val[1]));
                            });
                        } else {
                            this.data().unique().sort().each(function(d, j) {
                                innerHtml.append('<option value="' + d + '">' + d + '</option>');
                            });
                        }

                        break;
                    }
                    case '{$filterTypeDateTime}':{
                        innerHtml = $('<input/>')
                            .addClass('jdtInput dateTimeInput');

                        break;
                    }
                }
            }
            if (innerHtml != null)
                innerHtml = innerHtml.appendTo($(this.footer()).empty());

            if (filterOnEnter) {
                $('.jdtInput', this.footer()).keypress(function(e) {
                    if (e.which == 13) {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                        return false;
                    }
                });
            } else {
                $('.jdtInput', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            }
        });
    }
JavaScript;
        }

        $outputHtml = new GearHtmlString($outputHtml);
        if ($addScriptTag) {
            $outputHtml->prepend("<script type=\"text/javascript\">\n");
            $outputHtml->append("\n</script>");
        }
        return $outputHtml;
    }
}
abstract class JqueryDataTablesLanguagePack
{
    /** @var string */
    public $emptyTable;
    /** @var string */
    public $info;
    /** @var string */
    public $infoEmpty;
    /** @var string */
    public $infoFiltered;
    /** @var string */
    public $infoPostFix;
    /** @var string */
    public $infoThousands;
    /** @var string */
    public $lengthMenu;
    /** @var string */
    public $loadingRecords;
    /** @var string */
    public $processing;
    /** @var string */
    public $search;
    /** @var string */
    public $zeroRecords;
    /** @var string */
    public $paginate;
    /** @var string */
    public $aria;
    /** @var string */
    public $url;
}
class JqueryDataTablesOptions extends GearClientLibraryOptions
{
    const JdtColumnFilterModeList = 'list';
    const JdtColumnFilterModeText = 'text';
    const JdtColumnFilterModeNone = 'none';
    const JdtColumnFilterModeBoolean = 'bool';
    const JdtColumnFilterModeDateTime = 'date';



    /**
     * @JsonIgnore
     *
     * @var bool
     */
    public $apiInstance;
    /**
     * @JsonIgnore
     *
     * @var string
     */
    public $declareVariable;



    /** @var bool */
    public $processing;
    /** @var bool */
    public $serverSide;

    /** @var bool */
    public $searching;

    /** @var bool */
    public $ordering;
    /** @var mixed */
    public $order;

    /** @var bool */
    public $paging;
    /** @var string */
    public $pagingType;

    /** @var bool */
    public $responsive;

    /**
     * "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
     * @var array
     */
    public $lengthMenu;

    /**
     * no-footer
     * @var string
     */
    public $noFooter;

    /** @var int */
    public $scrollX;
    /** @var int */
    public $scrollY;

    /** @var mixed */
    public $footerCallback;

    /** @var mixed */
    public $dom;

    /** @var mixed */
    public $paginationType;

    /** @var bool */
    public $autoWidth;
    /** @var bool */
    public $info;
    /** @var bool */
    public $lengthChange;
    /** @var bool */
    public $stateSave;
    /** @var bool */
    public $jQueryUI;
    /** @var bool */
    public $filterOnEnter;

    /** @var bool */
    public $orderMulti;

    /** @var mixed */
    public $colReorder;

    /** @var JqueryDataTablesRowReorder */
    public $rowReorder;

    /** @var mixed */
    public $select;

    /** @var GearJqueryAjaxOptions */
    public $ajax;

    /** @var JqueryDataTablesColumnInfo[] */
    public $columns;

    /** @var JqueryDataTablesColumnDefinition[] */
    public $columnDefs;

    /** @var JqueryDataTablesColumnInfo[] */
    public $searchCols;

    /** @var JqueryDataTablesLanguagePack */
    public $language;

    /** @var mixed */
    public $data;

    /** @var mixed */
    public $initComplete;


    /**
     * @param callable $initializer
     * @return JqueryDataTablesOptions
     */
    public static function createDefault($initializer = null)
    {
        $instance = new self();

        $instance->apiInstance = true;
        $instance->autoWidth = true;

        if (is_callable($initializer)) {
            $initializer($instance);
        }

        return $instance;
    }

    /**
     * @param callable $initializer
     * @return JqueryDataTablesOptions
     */
    public static function createDefaultServerSide($initializer = null)
    {
        $instance = new self();

        $instance->apiInstance = true;
        $instance->processing = true;
        $instance->serverSide = true;
        $instance->orderMulti = true;
        $instance->autoWidth = true;

        if (is_callable($initializer)) {
            $initializer($instance);
        }

        return $instance;
    }

    /**
     * @param callable $initializer
     * @return JqueryDataTablesOptions
     */
    public static function create($initializer = null)
    {
        $instance = new self();

        if (is_callable($initializer)) {
            $initializer($instance);
        }

        return $instance;
    }
}
class JqueryDataTablesPaginateLanguagePack
{
    /** @var string */
    public $first;
    /** @var string */
    public $previous;
    /** @var string */
    public $next;
    /** @var string */
    public $last;
}
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
            'columnDefs.data' => 'mData',
            'columnDefs.render' => 'mRender',
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
            'ajax' => '',
            'ajax.type' => '.sServerMethod',
            'ajax.dataSrc' => '.sAjaxDataProp',
            'ajax.url' => '.sAjaxSource',
            //'ajax' => 'ajx',
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
            'language' => 'oLanguage',
            'language.aria' => 'oAria',
            'language.aria.sortAscending' => 'sSortAscending',
            'language.aria.sortDescending' => 'sSortDescending',
            'language.paginate' => 'oPaginate',
            'language.paginate.first' => 'sFirst',
            'language.paginate.last' => 'sLast',
            'language.paginate.next' => 'sNext',
            'language.paginate.previous' => 'sPrevious',
            'language.emptyTable' => 'sEmptyTable',
            'language.info' => 'sInfo',
            'language.infoEmpty' => 'sInfoEmpty',
            'language.infoFiltered' => 'sInfoFiltered',
            'language.infoPostFix' => 'sInfoPostFix',
            'language.thousands' => 'sInfoThousands',
            'language.lengthMenu' => 'sLengthMenu',
            'language.loadingRecords' => 'sLoadingRecords',
            'language.infoThousands' => 'sInfoThousands',
            'language.processing' => 'sProcessing',
            'language.search' => 'sSearch',
            'language.url' => 'sUrl',
            'language.zeroRecords' => 'sZeroRecords',
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

        $rootObj = null;
        return self::_convertToOldApi($mapping, $rootObj, $options, null);
    }

    /**
     * @param array $mapping
     * @param object $rootObj
     * @param object $obj
     * @param string $level
     * @return array
     */
    private static function _convertToOldApi($mapping, &$rootObj, $obj, $level)
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
                //if ($name == '') {
                //    continue;
                //}
                //$fields[$name] = '';
                if (substr($name, 0, 1) == '.') {
                    $name = substr($name, 1);
                    if ($rootObj != null) {
                        $target = &$rootObj;
                    }
                }
            }

            if ($rootObj == null) {
                $rootObj = &$fields;
            }

            if (is_object($val)) {
                if ($val instanceof GearRawOutput) {
                    $val = strval($val);
                    $target[] = "\"$name\":$val";
                } else {
                    $target[] = "\"$name\":" . self::_convertToOldApi(
                            $mapping,
                            $rootObj,
                            $val,
                            "$stageName.");
                }
            } elseif (is_array($val)) {
                $target[] = "\"$name\":" . self::_convertToOldApiArray($mapping, $rootObj, $val, "$stageName.");
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
class JqueryDataTablesRowReorder extends GearJsOptions
{
    /** @var string */
    public $dataSrc;

    /** @var string */
    public $selector;
}
class JqueryDataTablesSearchModel
{
    public $caseInsensetive;
    public $tegex;
    public $smart;

    public $search;
}
class JqueryDataTablesSelect extends GearJsOptions
{
    /** @var string */
    public $style;
}
class JqueryDataTablesEnglishLanguagePack extends JqueryDataTablesLanguagePack
{
    public function __construct()
    {
        $this->emptyTable = "No data available in table";
        $this->info = "Showing _START_ to _END_ of _TOTAL_ entries";
        $this->infoEmpty = "Showing 0 to 0 of 0 entries";
        $this->infoFiltered = "(filtered from _MAX_ total entries)";
        $this->infoPostFix = "";
        $this->infoThousands = ",";
        $this->lengthMenu = "Show _MENU_ entries";
        $this->loadingRecords = "Loading...";
        $this->processing = "Processing...";
        $this->search = "Search:";
        $this->zeroRecords = "No matching records found";

        $paginate = new JqueryDataTablesPaginateLanguagePack();
        $this->paginate = $paginate;
        $paginate->first = "First";
        $paginate->previous = "Previous";
        $paginate->next = "Next";
        $paginate->last = "Last";

        $aria = new JqueryDataTablesAriaLanguagePack();
        $this->aria = $aria;
        $aria->sortAscending = ": activate to sort column ascending";
        $aria->sortDescending = ": activate to sort column descending";

        $this->url = "Url";
    }
}
class JqueryDataTablesFilter implements IJqueryDataTablesFilter
{
    public static $defaultPageSize = 20;

    private $draw;
    private $apiInstance;
    private $start;
    private $length;
    private $generalFilter;
    private $compareMode;
    private $columns;
    private $exOptions;

    public function getDraw()
    {
        return $this->draw;
    }
    public function setDraw($draw)
    {
        $this->draw = $draw;
    }

    public function getApiInstance()
    {
        return $this->apiInstance;
    }
    public function setApiInstance($apiInstance)
    {
        $this->apiInstance = $apiInstance;
    }

    public function getStart()
    {
        return $this->start;
    }
    public function setStart($start)
    {
        $this->start = $start;
    }

    public function getLength()
    {
        return $this->length;
    }
    public function setLength($length)
    {
        $this->length = $length;
    }

    public function getGeneralFilter()
    {
        return $this->generalFilter;
    }
    public function setGeneralFilter($generalFilter)
    {
        $this->generalFilter = $generalFilter;
    }

    public function getCompareMode()
    {
        return $this->compareMode;
    }
    public function setCompareMode($compareMode)
    {
        $this->compareMode = $compareMode;
    }

    public function getColumns()
    {
        return $this->columns;
    }
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getExtendedOptions()
    {
        return $this->exOptions;
    }
    public function setExtendedOptions($exOptions)
    {
        $this->exOptions = $exOptions;
    }
}
class JqueryDataTablesParsiLanguagePack extends JqueryDataTablesLanguagePack
{
    public function __construct()
    {
        $this->emptyTable = "هیچ داده ای موجود نمیباشد";
        $this->info = "نمایش _START_ تا _END_ از مجموع _TOTAL_ مورد";
        $this->infoEmpty = "هیچ رکوردی موجود نمیباشد";
        $this->infoFiltered = "(نمایش رکوردها از _MAX_ رکورد)";
        $this->infoPostFix = "";
        $this->infoThousands = ",";
        $this->lengthMenu = "نمایش _MENU_ رکورد در هر صفحه";
        $this->loadingRecords = "Loading...";
        $this->processing = "درحال پردازش...";
        $this->search = "جستجو:";
        $this->zeroRecords = "موردی یافت نشد";

        $paginate = new JqueryDataTablesPaginateLanguagePack();
        $this->paginate = $paginate;
        $paginate->first = "ابتدا";
        $paginate->previous = "قبلی";
        $paginate->next = "بعدی";
        $paginate->last = "انتها";

        $aria = new JqueryDataTablesAriaLanguagePack();
        $this->aria = $aria;
        $aria->sortAscending = ": برای مرتب سازی سعودی";
        $aria->sortDescending = ": برای مرتب سازی نزولی";

        //$this->url = "Url";
    }
}


/* Generals: */
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

