<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\viewmodel;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\fancypack\jdt\JqueryDataTablesContext;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IJqueryDataTablesViewModel
{
    /**
     * @param JqueryDataTablesContext $jdtContext
     * @param array $rows
     * @return mixed
     */
    function orderRows($jdtContext, $rows);

    /**
     * @param JqueryDataTablesContext $jdtContext
     * @param array $rows
     * @return mixed
     */
    function filterRows($jdtContext, $rows);

    /**
     * @param JqueryDataTablesContext $jdtContext
     * @param array $rows
     * @return mixed
     */
    function processRows($jdtContext, $rows);

    /**
     * @param JqueryDataTablesContext $jdtContext
     * @param mixed $row
     * @return mixed
     */
    function processRow($jdtContext, $row);
}
/*</module>*/
?>