<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\viewhelpers;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearArgumentNullException;
use gear\arch\core\GearErrorStrategy;
use gear\arch\helpers\GearHelpers;
use gear\arch\http\IGearHttpRequest;
use gear\data\core\query\builder\GearQueryBuilder;
use gear\fancypack\jdt\IJqueryDataTablesFilter;
use gear\fancypack\jdt\JqueryDataTablesFilter;
use gear\fancypack\jdt\viewmodel\IJqueryDataTablesFilterViewModel;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class JqueryDataTablesDriver
{
    /**
     * @param IGearHttpRequest $request
     *
     * @return array
     */
    public static function getOrders($request)
    {
        $result = [];

        $index = 0;
        for (; ;) {
            $orderCol = $request->getValue("order[$index][column]");
            if (GearHelpers::isNullOrWhitespace($orderCol)) break;
            $col = ctype_digit($orderCol) ? intval($orderCol) : null;
            if ($col != null) {
                $result[$col] = $request->getValue(["order[$index][dir]"]);
            }
            $index++;
        }

        return $result;
    }

    /**
     * @param IGearHttpRequest $request
     * @param array $orders
     * @param int $index
     *
     * @return null
     */
    public static function readColumn($request, $orders, $index)
    {
        $name = $request->getValue("columns[$index][name]");
        if (GearHelpers::isNullOrWhitespace($name)) {
            return null;
        }
        $data = $request->getValue("columns[$index][data]");
        $orderable = boolval($request->getValue("columns[$index][orderable]"));
        $searchable = boolval($request->getValue("columns[$index][searchable]"));
        $order = null;
        $filter = null;
        $filterIsRegex = false;
        if ($searchable) {
            $filter = $request->getValue("columns[$index][search][value]");
            $filterIsRegex = boolval($request->getValue("columns[$index][search][regex]"));
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

        $filterModel->setDraw($request->getValue("draw"));
        $filterModel->setApiInstance($request->getValue("apiInstance", true));

        $columns = [];
        $orders = self::getOrders($request);

        $index = 0;
        $col = self::readColumn($request, $orders, 0);
        while ($col != null) {
            $columns[] = $col;
            $index++;
            $col = self::readColumn($request, $orders, $index);
        }

        $filterModel->setGeneralFilter($request->getValue("search[value]"));
        $regex = boolval($request->getValue("search[regex]"));
        $filterModel->setCompareMode($regex ? 'regex' : 'contains');

        $startStr = $request->getValue("start");
        $lengthStr = $request->getValue("length");

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

        $skipNull = $filterModel->getStart();
        $skip = ctype_digit($skipNull) ? intval($skipNull) : 0;

        $pageSizeNull = $filterModel->getLength();
        $pageSize = ctype_digit($pageSizeNull) ? intval($pageSizeNull) : 10;

        $query = $query
            ->skip($skip)
            ->take($pageSize);

        //$data = $query->select();

        return [
            'result' => $query,
            'count' => $filteredCount
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
        $filterViewModel
    )
    {
        $filterModel = new JqueryDataTablesFilter();

        try {
            $total = $query->count();

            $result = self::createJqueryDataTablesFilter(
                $query,
                $request,
                $filterViewModel,
                $filterModel
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
/*</module>*/
?>