<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearArgumentNullException;
use gear\arch\core\GearSerializer;
use gear\arch\http\results\GearJsonResult;
use gear\data\core\query\builder\GearQueryBuilder;
use gear\fancypack\jdt\viewhelpers\JqueryDataTablesDriver;
use gear\fancypack\jdt\viewmodel\IJqueryDataTablesFilterViewModel;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JdtQueryResult extends GearJsonResult
{
    /** @var IJqueryDataTablesFilterViewModel */
    private $filterViewModel;
    
    /** @var bool */
    public $countFiltereds = true;
    
    /**
     * GearJdtResult constructor.
     * @param IJqueryDataTablesFilterViewModel $filterViewModel
     * @param GearQueryBuilder $query
     * @throws GearArgumentNullException
     */
    public function __construct($filterViewModel, $query)
    {
        if (!($filterViewModel instanceof IJqueryDataTablesFilterViewModel)) {
            throw new GearArgumentNullException('filterViewModel');
        }

        $this->filterViewModel = $filterViewModel;

        parent::__construct($query, true);
    }

    public function createJson($context, $request, $response, $query)
    {
        //$array = null;
        //if (is_array($content)) {
        //    $array = $content;
        //} elseif (is_null($content)) {
        //    $array = [];
        //} else {
        //    $array = iterator_to_array($content);
        //}

        return JqueryDataTablesDriver::createJqueryDataTablesResult($query, $request, $this->filterViewModel, $this->countFiltereds);
    }
}
/*</module>*/
?>