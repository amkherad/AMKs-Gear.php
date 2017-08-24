<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder\sqlgenerator;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearQueryBuilderSqlGenerator
{
    function createSelect(
        $table,
        $cols,
        $conditions,
        $limit,
        $grouping,
        $ordering,
        $join);

    function createCount(
        $table,
        $cols,
        $conditions,
        $limit,
        $grouping,
        $ordering,
        $join);
}
/*</module>*/
?>