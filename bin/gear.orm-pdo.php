<?php
//Bundle: Gear.Orm.Pdo

/* Dependencies: */


/* Modules: */
class GearPdoDataInterface implements IGearCrudService
{
    /** @var PDO */
    private $pdo;
    private
//        $databaseName,
//        $provider,
//        $username,
//        $password,
        $tableName,
        $entityName;

    private function __construct(
        $entityName,
        $pdo
    )
    {
        $this->tableName = $entityName;
        $this->entityName = $entityName;

        $this->pdo = $pdo;
    }

    function createModelInstance()
    {
        return null;
    }

    /**
     * @param string $entityName
     * @param PDO $pdo
     * @return GearPdoDataInterface
     */
    public static function fromPdo(
        $entityName,
        $pdo
    )
    {
        return new self($entityName, $pdo);
    }

    public static function fromParts(
        $entityName,
        $dsn,
        $username,
        $password,
        $options = null
    )
    {
        $pdo = new PDO($dsn, $username, $password, $options);
        return new self($entityName, $pdo);
    }

    public function getUnderlyingContext()
    {
        return $this->pdo;
    }

    /**
     * @return GearQueryBuilder
     */
    public function query()
    {
        return new GearQueryBuilder(
            $this->entityName,
            $this->tableName,
            new GearQueryBuilderSqlGeneratorMySql(),
            new GearPdoQueryBuilderEvaluator($this));
    }

    /**
     * @param $query
     * @return PDOStatement
     */
    public function executeQuery($query) {
        return $this->pdo->query($query);
    }

    public function getAll()
    {
        return self::query()
            ->select();
    }

    public function findAll($predicate)
    {
        // TODO: Implement FindAll() method.
    }

    public function findById($id)
    {
        return self::query()
            ->isEqual("id", $id)
            ->selectOne();
    }

    public function insert($entity)
    {
        $this->pdo->query("INSERT INTO `{$this->pdo->tableName}` ()");
        return $this->pdo->lastInsertId();
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
}
class GearPdoEntity
{

}
class GearPdoQueryBuilderEvaluator implements IGearQueryBuilderEvaluator
{
    /** @var GearPdoDataInterface */
    private $gearPdoDataInterface;

    /**
     * GearPdoQueryBuilderEvaluator constructor.
     * @param $gearPdoDataInterface
     */
    public function __construct($gearPdoDataInterface)
    {
        $this->gearPdoDataInterface = $gearPdoDataInterface;
    }


    public function getNonResult($queryBuilder, $queryString)
    {
        $result = $this->gearPdoDataInterface->executeQuery($queryString);

        return true;
    }

    public function getOneResult($queryBuilder, $queryString)
    {
        $result = $this->gearPdoDataInterface->executeQuery($queryString);

        $first = $result->fetch();
        if ($first) {
            return $first;
        }

        return null;
    }

    public function getManyResult($queryBuilder, $queryString)
    {
        $result = $this->gearPdoDataInterface->executeQuery($queryString);

        $first = $result->fetchAll();
        if ($first) {
            return $first;
        }

        return null;
    }

    public function getScalarResult($queryBuilder, $queryString)
    {
        $result = $this->gearPdoDataInterface->executeQuery($queryString);

        $first = $result->fetchColumn();
        return $first;
    }
}
class GearPdoQueryBuilderHelper implements IGearQueryBuilderHelper
{
    function escapeValue($value)
    {
        // TODO: Implement escapeValue() method.
    }
}


/* Generals: */

