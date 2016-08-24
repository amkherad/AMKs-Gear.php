<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\viewhelpers;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearArgumentNullException;
use gear\arch\helpers\GearHelpers;
use gear\arch\http\IGearHttpRequest;
use gear\fancypack\jdt\IJqueryDataTablesFilter;

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
        for(;;)
        {
            $orderCol = $request->getValue("order[$index][column]");
            if (GearHelpers::isNullOrWhitespace($orderCol)) break;
            $col = ctype_digit($orderCol) ? intval($orderCol) : null;
            if($col != null) {
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
}
/*</module>*/
?>