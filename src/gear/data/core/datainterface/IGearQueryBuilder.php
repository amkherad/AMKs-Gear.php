<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\datainterface;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearQueryBuilder
{
    /**
     * @return string Returns the entity name.
     */
    function getEntityName();

    /**
     * @return string Returns the table name.
     */
    function getTableName();

    /**
     * @param $condition string Add raw sql-query into condition collection.
     * @param array|null $params
     * @return mixed
     */
    function where($condition, $params = null);

    /**
     * @param $condition string Add raw sql-query into [or] condition collection.
     * @param array|null $params
     * @return mixed
     */
    function orCondition($condition, $params = null);

    /**
     * @param $condition string Add raw sql-query into [and] condition collection.
     * @param array|null $params
     * @return mixed
     */
    function andCondition($condition, $params = null);

    function isIn($var, $indices, $params = null);
    function isEqual($var1, $var2, $params = null);
    function isGreater($var1, $var2, $params = null);
    function isGreaterEqual($var1, $var2, $params = null);
    function isLesser($var1, $var2, $params = null);
    function isLesserEqual($var1, $var2, $params = null);

    function orderBy($col, $order = 'asc');
    function orderByDescending($col);
    function thenBy($col, $order = 'asc');
    function thenByDescending($col);

    function includeJoin();
    function innerJoin();
    function outerJoin();
    function join();
    function on();

    function groupBy();
    function having();

    function skip($count);
    function take($count);

    function select();
    function selectOne();

    function count();

    function setConverter($converter);

    function sp($storedProcedureName, $params = null);
    function fn($functionName, $params = null);
}
/*</module>*/
?>