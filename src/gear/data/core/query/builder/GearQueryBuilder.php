<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearArgumentNullException;
use gear\arch\core\GearExtensibleClass;
use gear\arch\core\GearInvalidOperationException;
use gear\data\core\query\builder\sqlgenerator\IGearQueryBuilderSqlGenerator;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearQueryBuilder extends GearExtensibleClass
{
    const ConditionJoinAndBehavior = 'and';
    const ConditionJoinOrBehavior = 'or';

    const GearQueryBuilderLimitOne = 'one';
    const GearQueryBuilderLimitNoLimit = null;

    public static $DefaultConditionJoinBehavior = 'and';

    private
        $entityName,
        $tableName,

        $andConditions = [],
        $orConditions = [],
        $whereConditions = [];

    /** @var IGearQueryBuilderSqlGenerator */
    private $queryBuilderSqlGenerator;
    /** @var IGearQueryBuilderEvaluator */
    private $queryEvaluator;

    public
        $unicode = true;

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
        return null;
    }

    public function createJoins()
    {
        return null;
    }

    public function formatValue($value)
    {
        if (is_numeric($value) && !is_string($value)) {
            return "$value";
        }
        if (substr(trim($value), 0, 1) != "'") {
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
    public function OrCondition($condition)
    {
        $this->orConditions[] = $condition;

        return $this;
    }

    /**
     * @param $condition
     * @return $this
     */
    public function AndCondition($condition)
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
        $val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$val1 = $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isGreater($var1, $var2)
    {
        $val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$val1 > $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isGreaterEqual($var1, $var2)
    {
        $val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$val1 >= $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isLesser($var1, $var2)
    {
        $val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$val1 < $val2");

        return $this;
    }

    /**
     * @param $var1
     * @param $var2
     * @return $this
     */
    public function isLesserEqual($var1, $var2)
    {
        $val1 = $this->formatValue($var1);
        $val2 = $this->formatValue($var2);

        $this->where("$val1 <= $val2");

        return $this;
    }

    public function select()
    {
        return $this->queryEvaluator->getManyResult(
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
        return $this->queryEvaluator->getOneResult(
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

    public function __toString()
    {
        return (string)$this->createConditions();
    }
}

/*</module>*/
?>