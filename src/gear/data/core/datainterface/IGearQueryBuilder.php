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
     * @return mixed
     */
    function where($condition);

    /**
     * @param $condition string Add raw sql-query into [or] condition collection.
     * @return mixed
     */
    function orCondition($condition);
    /**
     * @param $condition string Add raw sql-query into [and] condition collection.
     * @return mixed
     */
    function andCondition($condition);

    function isIn($var, $indices);
    function isEqual($var1, $var2);
    function isGreater($var1, $var2);
    function isGreaterEqual($var1, $var2);
    function isLesser($var1, $var2);
    function isLesserEqual($var1, $var2);

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
}
/*</module>*/
?>