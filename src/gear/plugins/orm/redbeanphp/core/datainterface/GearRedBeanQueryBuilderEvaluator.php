<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\plugins\orm\redbeanphp\core\datainterface;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\query\builder\IGearQueryBuilderEvaluator;
use gear\plugins\orm\redbeanphp\datainterface\GearRedBeanDataInterface;

/*</namespace.use>*/
/*<namespace.use-3rdparty>*/
use \RedBeanPHP\Facade as RedBean;

/*</namespace.use-3rdparty>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
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
/*</module>*/
?>