<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearQueryBuilderEvaluator
{
    function getNonResult($query);
    function getOneResult($query);
    function getManyResult($query);
    function getScalarResult($query);
}
/*</module>*/
?>