<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\datainterface;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\query\builder\GearQueryBuilder;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearCrudService
{
    function getUnderlyingContext();

    /**
     * @return GearQueryBuilder
     */
    function query();

    function getAll();
    function findAll($predicate);
    function findById($id);

    function countAll();
    function count($predicate);

    function insert($entity);

    function update($entity);
    function updateById($id, $entity);

    function delete($entity);
    function deleteById($id);

    function dispose();
}
/*</module>*/
?>