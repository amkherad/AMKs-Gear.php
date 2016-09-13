<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\viewmodel;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\query\builder\GearQueryBuilder;
use gear\fancypack\jdt\JqueryDataTablesFilter;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
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
/*</module>*/
?>