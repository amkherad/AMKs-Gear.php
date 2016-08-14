<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder\sqlgenerator;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearArgumentNullException;
use gear\arch\core\GearInvalidOperationException;
use gear\data\core\query\builder\GearQueryBuilder;
use gear\data\core\query\builder\sqlgenerator\IGearQueryBuilderSqlGenerator;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

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

    public function formatLimit($limit)
    {
        switch ($limit) {
            case GearQueryBuilder::GearQueryBuilderLimitOne:
                return 'LIMIT 1';
        }
    }
}

/*</module>*/
?>