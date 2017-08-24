<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\datainterface\IGearQueryBuilder;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearQueryBuilderEvaluator
{
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getNonResult($queryBuilder, $queryString, $params = null);
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getOneResult($queryBuilder, $queryString, $params = null);
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getManyResult($queryBuilder, $queryString, $params = null);
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getScalarResult($queryBuilder, $queryString, $params = null);
}
/*</module>*/
?>