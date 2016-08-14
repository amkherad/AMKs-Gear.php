<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\plugins\orm\redbeanphp\datainterface;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\datainterface\IGearCrudService;
use gear\data\core\query\builder\GearQueryBuilder;
use gear\data\core\query\builder\sqlgenerator\GearQueryBuilderSqlGeneratorMySql;
use gear\plugins\orm\redbeanphp\core\datainterface\GearRedBeanQueryBuilderEvaluator;
/*</namespace.use>*/
/*<namespace.use-3rdparty>*/
use \RedBeanPHP\Facade as RedBean;
/*</namespace.use-3rdparty>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearRedBeanDataInterface implements IGearCrudService
{
    const RedBean_AllName = 'all';
    const RedBean_DispenseName = 'dispense';
    const RedBean_DispenseAllName = 'dispenseAll';
    const RedBean_LoadName = 'load';
    const RedBean_LoadAllName = 'loadAll';
    const RedBean_StoreName = 'store';
    const RedBean_TrashName = 'trash';
    const RedBean_TrashAllName = 'trashAll';
    const RedBean_WipeName = 'wipe';
    const RedBean_NukeName = 'nuke';
    const RedBean_FreshName = 'fresh';
    const RedBean_FindName = 'find';
    const RedBean_FindOneName = 'findOne';
    const RedBean_FindAllName = 'findAll';
    const RedBean_FindOrCreateName = 'findOrCreate';
    const RedBean_FindMultipleName = 'findMulti';
    const RedBean_CountName = 'count';
    const RedBean_CountOwnName = 'countOwn';
    const RedBean_CountSharedName = 'countShared';
    const RedBean_WithConditionName = 'withCondition';
    const RedBean_AliasName = 'alias';
    const RedBean_NoLoadName = 'noLoad';


    private
        $entityName
    ;

    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }

    function getUnderlyingContext()
    {
        return $this->entityName;
    }

    /**
     * @return GearQueryBuilder
     */
    function query()
    {
        return new GearQueryBuilder(
            $this->entityName,
            $this->entityName,
            new GearQueryBuilderSqlGeneratorMySql(),
            new GearRedBeanQueryBuilderEvaluator($this));
    }

    public function getRedBeanMethod($methodName)
    {
        return array($this->entityName, $methodName);
    }

    public function getAll()
    {
        $result = call_user_func($this->getRedBeanMethod(''));
    }

    function findAll($predicate)
    {
        // TODO: Implement FindAll() method.
    }

    function findById($id)
    {
        // TODO: Implement FindById() method.
    }

    function insert($entity)
    {
        // TODO: Implement Insert() method.
    }

    function update($entity)
    {
        // TODO: Implement Update() method.
    }

    function updateById($id, $entity)
    {
        // TODO: Implement UpdateById() method.
    }

    function delete($entity)
    {
        // TODO: Implement Delete() method.
    }

    function deleteById($id)
    {
        // TODO: Implement DeleteById() method.
    }

    function countAll()
    {
        // TODO: Implement CountAll() method.
    }

    function count($predicate)
    {
        // TODO: Implement Count() method.
    }

    public function dispose()
    {
        RedBean::close();
    }
}
/*</module>*/
?>