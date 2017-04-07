<?php
//Bundle: Gear.Orm.RedBeanPhp

/* Dependencies: */


/* Modules: */
use \RedBeanPHP\Facade as RedBean;
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

    public function getUnderlyingContext()
    {
        return $this->entityName;
    }

    public function createModelInstance()
    {
        return RedBean::dispense($this->entityName);
    }

    /**
     * @return GearQueryBuilder
     */
    public function query()
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

    public function findAll($predicate)
    {
        // TODO: Implement FindAll() method.
    }

    public function findById($id)
    {
        // TODO: Implement FindById() method.
    }

    public function insert($entity)
    {
        return RedBean::store($entity);
    }

    public function update($entity)
    {
        return RedBean::store($entity);
    }

    public function updateById($id, $entity)
    {
        // TODO: Implement UpdateById() method.
    }

    public function delete($entity)
    {
        RedBean::trash($entity);
        return true;
    }

    public function deleteById($id)
    {
        RedBean::trash($this->entityName, $id);
    }

    public function countAll()
    {
        return RedBean::count($this->entityName);
    }

    public function count($predicate)
    {
        return RedBean::count($this->entityName, $predicate);
    }

    public function dispose()
    {
        RedBean::close();
    }

    function lastInsertId()
    {
        return RedBean::getInsertID();
    }
}
use \RedBeanPHP\SimpleModel;
class GearRedBeanEntity extends SimpleModel
{

}
class GearRedBeanQueryBuilderEvaluator implements IGearQueryBuilderEvaluator
{
    /** @var GearRedBeanDataInterface */
    private $gearRedBeanDataInterface;

    /**
     * GearRedBeanQueryBuilderEvaluator constructor.
     * @param $gearRedBeanDataInterface
     */
    public function __construct($gearRedBeanDataInterface)
    {
        $this->gearRedBeanDataInterface = $gearRedBeanDataInterface;
    }


    public function getNonResult($queryBuilder, $queryString, $params = null)
    {
        return RedBean::exec($queryString);
    }

    public function getOneResult($queryBuilder, $queryString, $params = null)
    {
        $entityName = $queryBuilder->getEntityName();
        $record = RedBean::getRow($queryString);
        $result = RedBean::convertToBean($entityName, $record);
        return $result;
    }

    public function getManyResult($queryBuilder, $queryString, $params = null)
    {
        $entityName = $queryBuilder->getEntityName();
        $records = RedBean::getAll($queryString);
        $result = RedBean::convertToBeans($entityName, $records);
        return $result;
    }

    public function getScalarResult($queryBuilder, $queryString, $params = null)
    {
        return RedBean::getCell($queryString);
    }
}
class GearRedBeanQueryBuilderHelper implements IGearQueryBuilderHelper
{
    function escapeValue($value)
    {
        // TODO: Implement escapeValue() method.
    }
}


/* Generals: */
GearBundle::resolvePackage('gear.rb-lib');

