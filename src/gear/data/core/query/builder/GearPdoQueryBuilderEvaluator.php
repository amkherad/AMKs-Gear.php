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
/*</module>*/
?>