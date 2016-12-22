<?php
//Bundle: Gear.Data

/* Dependencies: */


/* Modules: */
class GearCrudResult
{

}
class GearPDOQueryBuilderHelper implements IGearQueryBuilderHelper
{
    /** @var \PDO */
    private $pdo;

    /**
     * GearPDOQueryBuilderHelper constructor.
     * @param $pdo \PDO
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function escapeValue($value)
    {
        return $this->pdo->quote($value);
    }
}
class GearQueryBuilderSqlGeneratorMySql implements IGearQueryBuilderSqlGenerator
{
    public function createSelect(
        $table,
        $cols,
        $conditions,
        $limit,
        $grouping,
        $ordering,
        $join)
    {
        $result = $this->_createSelect(
            $table,
            $cols,
            $conditions,
            $limit,
            $grouping,
            $ordering,
            $join);

        return $result;
    }

    private function _createSelect(
        $table,
        $cols,
        $conditions,
        $limit,
        $grouping,
        $ordering,
        $join)
    {
        if ($table == null) {
            throw new GearArgumentNullException('table');
        }

        if ($cols == null) {
            $cols = '*';
        }

        if ($conditions != null) {
            $conditions = "WHERE $conditions";
        }

        if ($limit != null) {
            $limit = $this->formatLimit($limit);
        }

        return trim("SELECT $cols FROM $table $conditions $limit $join");
    }

    function createCount(
        $table,
        $cols,
        $conditions,
        $limit,
        $grouping,
        $ordering,
        $join)
    {
        if ($table == null) {
            throw new GearArgumentNullException('table');
        }

        if ($cols == null) {
            $cols = '*';
        }

        if ($conditions != null) {
            $conditions = "WHERE $conditions";
        }

        if ($limit != null) {
            $limit = $this->formatLimit($limit);
        }

        //return trim("SELECT COUNT($cols) FROM $table $conditions $limit $join");
        return trim("SELECT COUNT($cols) FROM $table $conditions");
    }



    public function formatLimit($limit)
    {
        if ($limit == null) {
            return null;
        } else if($limit == GearQueryBuilder::GearQueryBuilderLimitOne) {
            return 'LIMIT 1';
        }

        $col = strpos($limit, ':');
        if ($col === false) {
            return null;
        }
        $limitType = substr($limit, 0, $col);
        $limitValue = substr($limit, $col);

        switch ($limitType) {
            case GearQueryBuilder::GearQueryBuilderLimitNRecordSig:
                return "LIMIT $limitValue";
            case GearQueryBuilder::GearQueryBuilderLimitRangeSig:
                $parts = explode('-', $limitValue);
                if (count($parts) != 2) {
                    return null;
                }
                $offset = intval($parts[0]);
                $highVal = intval($parts[1]);
                if ($offset > $highVal) {
                    throw new GearInvalidOperationException("Invalid range encountered on query");
                }
                $count = $highVal - $offset;
                return "LIMIT $count OFFSET {$parts[0]}";
            case GearQueryBuilder::GearQueryBuilderLimitOffsetSig:
                $parts = explode('-', $limitValue);
                if (count($parts) != 2) {
                    return null;
                }
                $offset = intval($parts[0]);
                $count = intval($parts[1]);
                return "LIMIT $count OFFSET {$offset}";
            case GearQueryBuilder::GearQueryBuilderLimitOffset:
                $offset = intval($limitValue);
                //http://dev.mysql.com/doc/refman/5.7/en/select.html#id4651990
                return "LIMIT 18446744073709551615 OFFSET {$offset}";
            default: return null;
        }
    }
}
class GearStringQuery implements IGearQuery
{
    /** @var string */
    private
        $query
    ;

    /**
     * GearStringQuery constructor.
     * @param $query string
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->query;
    }
}
interface IGearCrudService
{
    function getUnderlyingContext();

    /**
     * @return GearQueryBuilder
     */
    function query();

    function createModelInstance();

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
interface IGearEntity
{

}
interface IGearIdEntity
{
    function getId();
}
interface IGearQuery
{
    /**
     * @return string
     */
    function __toString();
}
interface IGearQueryBuilder
{
    /**
     * @return string Returns the entity name.
     */
    function getEntityName();

    /**
     * @return string Returns the table name.
     */
    function getTableName();

    /**
     * @param $condition string Add raw sql-query into condition collection.
     * @return mixed
     */
    function where($condition);

    /**
     * @param $condition string Add raw sql-query into [or] condition collection.
     * @return mixed
     */
    function orCondition($condition);
    /**
     * @param $condition string Add raw sql-query into [and] condition collection.
     * @return mixed
     */
    function andCondition($condition);

    function isIn($var, $indices);
    function isEqual($var1, $var2);
    function isGreater($var1, $var2);
    function isGreaterEqual($var1, $var2);
    function isLesser($var1, $var2);
    function isLesserEqual($var1, $var2);

    function orderBy($col, $order = 'asc');
    function orderByDescending($col);
    function thenBy($col, $order = 'asc');
    function thenByDescending($col);

    function includeJoin();
    function innerJoin();
    function outerJoin();
    function join();
    function on();

    function groupBy();
    function having();

    function skip($count);
    function take($count);

    function select();
    function selectOne();

    function count();

    function setConverter($converter);
}
interface IGearQueryBuilderEvaluator
{
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getNonResult($queryBuilder, $queryString);
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getOneResult($queryBuilder, $queryString);
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getManyResult($queryBuilder, $queryString);
    /**
     * @param $queryBuilder IGearQueryBuilder
     * @param $queryString string
     * @return mixed
     */
    function getScalarResult($queryBuilder, $queryString);
}
interface IGearQueryBuilderHelper
{
    function escapeValue($value);
}
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
abstract class IdEntityBase implements IGearIdEntity
{
    public
        $id
    ;
}
class GearQueryBuilder extends GearExtensibleClass implements IGearQueryBuilder
{
    const ConditionJoinAndBehavior = 'and';
    const ConditionJoinOrBehavior = 'or';

    const SqlFieldEscapeSymbol = '@';

    const GearQueryBuilderLimitNRecordSig = 'limit';
    const GearQueryBuilderLimitRangeSig = 'range';
    const GearQueryBuilderLimitOffsetSig = 'offset';
    const GearQueryBuilderLimitOne = 'limit:one';
    const GearQueryBuilderLimitNRecord = 'limit:{COUNT}';
    const GearQueryBuilderLimitRange = 'range:{BEGIN}-{END}';
    const GearQueryBuilderLimitOffsetNRecord = 'offset:{OFFSET}-{COUNT}';
    const GearQueryBuilderLimitOffset = 'offset:{OFFSET}';
    const GearQueryBuilderLimitNoLimit = null;

    public static $DefaultConditionJoinBehavior = 'and';

    private
        $entityName,
        $tableName,

        $andConditions = [],
        $orConditions = [],
        $whereConditions = [],

        $limitType,
        $skip,
        $count
    ;

    public $unicode = true;

    /** @var IGearQueryBuilderSqlGenerator */
    private $queryBuilderSqlGenerator;
    /** @var IGearQueryBuilderEvaluator */
    private $queryEvaluator;

    /**
     * GearQueryBuilder constructor.
     * @param $entityName string
     * @param $tableName string
     * @param $queryBuilderSqlGenerator IGearQueryBuilderSqlGenerator
     * @param $queryEvaluator IGearQueryBuilderEvaluator
     *
     * @throws GearArgumentNullException
     */
    public function __construct(
        $entityName,
        $tableName,
        $queryBuilderSqlGenerator,
        $queryEvaluator)
    {
        parent::__construct();

        if ($entityName == null) {
            throw new GearArgumentNullException('entityName');
        }
        if ($tableName == null) {
            throw new GearArgumentNullException('tableName');
        }
        if ($queryBuilderSqlGenerator == null) {
            throw new GearArgumentNullException('queryBuilderSqlGenerator');
        }
        if ($queryEvaluator == null) {
            throw new GearArgumentNullException('queryEvaluator');
        }
        $this->entityName = $entityName;
        $this->tableName = $tableName;
        $this->queryBuilderSqlGenerator = $queryBuilderSqlGenerator;
        $this->queryEvaluator = $queryEvaluator;
    }

    /**
     * Returns the entity's name.
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Returns the table's name.
     * @return string
     */
    public function getTableName()
    {
        return $this->entityName;
    }

    public function flushAsOr()
    {
        if (count($this->whereConditions) > 0) {
            $this->orConditions = array_merge($this->whereConditions, $this->orConditions);
        }
    }

    public function flushAsAnd()
    {
        if (count($this->whereConditions) > 0) {
            $this->andConditions = array_merge($this->whereConditions, $this->andConditions);
        }
    }

    public function createConditions()
    {
        //if (self::$DefaultConditionJoinBehavior == self::ConditionJoinOrBehavior) {
        //    $this->flushAsOr();
        //} else {
        //    $this->flushAsAnd();
        //}

        $whereConditions = [];
        foreach ($this->whereConditions as $condition) {
            $whereConditions[] = $condition;
        }
        $orConditions = [];
        foreach ($this->orConditions as $condition) {
            $orConditions[] = $condition;
        }
        $andConditions = [];
        foreach ($this->andConditions as $condition) {
            $andConditions[] = $condition;
        }

        return implode(' AND ', array_merge($whereConditions, $orConditions, $andConditions));
    }

    public function createColumns()
    {
        return '*';
    }

    public function createOrdering()
    {
        return null;
    }

    public function createGrouping()
    {
        return null;
    }

    public function createLimit()
    {
        if ($this->limitType == null) {
            return null;
        }

        switch ($this->limitType) {
            case self::GearQueryBuilderLimitOffsetSig: {
                $limit = str_replace('{OFFSET}', $this->skip, self::GearQueryBuilderLimitOffsetNRecord);
                if ($this->count != null) {
                    $limit = str_replace('{COUNT}', $this->count, $limit);
                }
                return $limit;
            }
            case self::GearQueryBuilderLimitNRecordSig: {
                if ($this->count == 1) {
                    return self::GearQueryBuilderLimitOne;
                } else {
                    return str_replace('{COUNT}', $this->count, self::GearQueryBuilderLimitNRecord);
                }
            }
            case self::GearQueryBuilderLimitRangeSig: {
                $limit = str_replace('{BEGIN}', $this->skip, self::GearQueryBuilderLimitRange);
                if ($this->count != null) {
                    $limit = str_replace('{END}', $this->count, $limit);
                }
                return $limit;
            }
            case self::GearQueryBuilderLimitOne:
                return self::GearQueryBuilderLimitOne;
        }

        return null;
    }

    public function createJoins()
    {
        return null;
    }

    public function formatValue($value)
    {
        $value = trim($value);
        if (is_numeric($value) && !is_string($value)) {
            return "$value";
        }
        if ($value[0] == self::SqlFieldEscapeSymbol) {
            return substr($value, 1);
        }
        if (substr($value, 0, 1) != "'") {
            if ($this->unicode) {
                return "N'$value'";
            } else {
                return "'$value'";
            }
        } else {
            return $value;
        }
    }

    /**
     * Adds a string condition to condition list.
     * @param $condition string
     *
     * @throws GearInvalidOperationException
     * @return GearQueryBuilder
     */
    public function where($condition)
    {
        $this->whereConditions[] = $condition;

        return $this;
    }

    /**
     * @param $condition
     * @return $this
     */
    public function orCondition($condition)
    {
        $this->orConditions[] = $condition;

        return $this;
    }

    /**
     * @param $condition
     * @return $this
     */
    public function andCondition($condition)
    {
        $this->andConditions[] = $condition;

        return $this;
    }

    /**
     * @param $var
     * @param $indices
     * @return $this
     */
    public function isIn($var, $indices)
    {
        $items = [];
        foreach ($indices as $element) {
            $items[] = $this->formatValue($element);
        }
        $items = implode(',', $items);

        $this->where("$var IN ($items)");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isEqual($var1, $var2)
    {
        //$val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$var1 = $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isGreater($var1, $var2)
    {
        //$val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$var1 > $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isGreaterEqual($var1, $var2)
    {
        //$val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$var1 >= $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isLesser($var1, $var2)
    {
        //$val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$var1 < $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isLesserEqual($var1, $var2)
    {
        //$val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$var1 <= $val2");

        return $this;
    }

    public function orderBy($col, $order = 'asc'){}
    public function orderByDescending($col){}
    public function thenBy($col, $order = 'asc'){}
    public function thenByDescending($col){}

    public function includeJoin(){}
    public function innerJoin(){}
    public function outerJoin(){}
    public function join(){}
    public function on(){}

    public function groupBy(){}
    public function having(){}

    public function skip($count)
    {
        if ($this->limitType != null && $this->limitType != self::GearQueryBuilderLimitOffsetSig) {
            throw new GearInvalidOperationException();
        }

        if ($this->skip == null) {
            $this->skip = $count;
        } else {
            $this->skip += $count;
        }

        $this->limitType = self::GearQueryBuilderLimitOffsetSig;

        return $this;
    }
    public function take($count)
    {
        if ($this->limitType != null && $this->limitType != self::GearQueryBuilderLimitOffsetSig) {
            throw new GearInvalidOperationException();
        }

        if ($this->count == null) {
            $this->count = $count;
        } else {
            $this->count += $count;
        }

        $this->limitType = self::GearQueryBuilderLimitOffsetSig;

        return $this;
    }

    public function select()
    {
        return $this->queryEvaluator->getManyResult($this,
            $this->queryBuilderSqlGenerator->createSelect(
                $this->tableName,
                $this->createColumns(),
                $this->createConditions(),
                $this->createLimit(),
                $this->createGrouping(),
                $this->createOrdering(),
                $this->createJoins()
            ));
    }

    public function selectOne()
    {
        return $this->queryEvaluator->getOneResult($this,
            $this->queryBuilderSqlGenerator->createSelect(
                $this->tableName,
                $this->createColumns(),
                $this->createConditions(),
                self::GearQueryBuilderLimitOne,
                $this->createGrouping(),
                $this->createOrdering(),
                $this->createJoins()
            ));
    }

    public function count()
    {
        return $this->queryEvaluator->getScalarResult($this,
            $this->queryBuilderSqlGenerator->createCount(
                $this->tableName,
                $this->createColumns(),
                $this->createConditions(),
                $this->createLimit(),
                $this->createGrouping(),
                $this->createOrdering(),
                $this->createJoins()
            ));
    }

    public function __clone()
    {
        $query = new self($this->entityName,
            $this->tableName,
            $this->queryBuilderSqlGenerator,
            $this->queryEvaluator);

        $query->andConditions = $this->andConditions;
        $query->orConditions = $this->orConditions;
        $query->whereConditions = $this->whereConditions;

        $query->unicode = $this->unicode;

        return $query;
    }

    public function __toString()
    {
        return (string)$this->createConditions();
    }

    public function setConverter($converter)
    {

        return $this;
    }
}


/* Generals: */

