<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\datainterface;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\query\builder\GearQueryBuilder;
use gear\data\core\query\builder\sqlgenerator\GearQueryBuilderSqlGeneratorMySql;
use PDO;
use PDOStatement;

/*</namespace.use>*/
/*<namespace.use-3rdparty>*/
/*</namespace.use-3rdparty>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

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

    protected function __construct(
        $tableName,
        $entityName,
        $pdo
    )
    {
        $this->tableName = $tableName;
        $this->entityName = $entityName;

        $this->pdo = $pdo;
    }

    function createModelInstance()
    {
        return null;
    }

    /**
     * @return string entity name.
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @return string table name.
     */
    public function getTableName()
    {
        return $this->tableName;
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
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function executeQuery($query, $params = null)
    {
        $q = $this->pdo->prepare($query);
        
        if ($params != null) {
            foreach ($params as $key => $param) {
                $q->bindValue($key, $param, PDO::PARAM_STR); 
            }
        }
        
        $q->execute($params);
        //$q->execute();
        return $q;
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
    }

    public function updateById($id, $entity)
    {
        // TODO: Implement UpdateById() method.
    }

    public function delete($entity)
    {
        return true;
    }

    public function deleteById($id)
    {
    }

    public function countAll()
    {
    }

    public function count($predicate)
    {
    }

    public function dispose()
    {
    }
}

/*</module>*/
?>