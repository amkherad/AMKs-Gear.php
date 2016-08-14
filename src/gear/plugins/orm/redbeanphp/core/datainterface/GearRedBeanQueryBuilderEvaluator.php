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


    public function getNonResult($query)
    {
        return RedBean::exec($query);
    }

    public function getOneResult($query)
    {
        return RedBean::getRow($query);
    }

    public function getManyResult($query)
    {
        return RedBean::getAll($query);
    }

    public function getScalarResult($query)
    {
        return RedBean::exec($query);
    }
}
/*</module>*/
?>