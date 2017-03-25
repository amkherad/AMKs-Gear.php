<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\data\core\query\builder;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\datainterface\GearPdoDataInterface;
/*</namespace.use>*/
/*<namespace.use-3rdparty>*/
/*</namespace.use-3rdparty>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
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


    public function getNonResult($queryBuilder, $queryString, $params = null)
    {
        $query = $this->gearPdoDataInterface->executeQuery($queryString, $params);

        return true;
    }

    public function getOneResult($queryBuilder, $queryString, $params = null)
    {
        $query = $this->gearPdoDataInterface->executeQuery($queryString, $params);

        $first = $query->fetch();
        if ($first) {
            return $first;
        }

        return null;
    }

    public function getManyResult($queryBuilder, $queryString, $params = null)
    {
        $query = $this->gearPdoDataInterface->executeQuery($queryString, $params);

        $first = $query->fetchAll();
        if ($first) {
            return $first;
        }

        return null;
    }

    public function getScalarResult($queryBuilder, $queryString, $params = null)
    {
        $query = $this->gearPdoDataInterface->executeQuery($queryString, $params);

        $first = $query->fetchColumn();
        return $first;
    }
}
/*</module>*/
?>