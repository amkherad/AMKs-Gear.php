<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearArgumentNullException;
use gear\arch\core\GearSerializer;
use gear\arch\http\results\GearJsonResult;
use gear\fancypack\jdt\viewmodel\IJqueryDataTablesViewModel;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JdtResult extends GearJsonResult
{
    /** @var IJqueryDataTablesViewModel */
    private $viewModel;
    /**
     * GearJdtResult constructor.
     * @param array $arraySerializable
     * @param IJqueryDataTablesViewModel $viewModel
     * @throws GearArgumentNullException
     */
    public function __construct($viewModel, $arraySerializable)
    {
        if (!($viewModel instanceof IJqueryDataTablesViewModel)) {
            throw new GearArgumentNullException('viewModel');
        }

        $this->viewModel = $viewModel;

        parent::__construct($arraySerializable, true);
    }

    public function writeResult($context, $request, $response, $json)
    {
        $array = null;
        if (is_array($this->content)) {
            $array = $this->content;
        } else {
            $array = iterator_to_array($this->content);
        }

        //$jdtContext = new JqueryDataTablesContext($context, $request, $response);
//
        //$viewModel = $this->viewModel;
//
        //$array = $viewModel->filterRows($jdtContext, $array);
        //$array = $viewModel->orderRows($jdtContext, $array);
        //$array = $viewModel->processRows($jdtContext, $array);
//
        //$response->write(GearSerializer::json($array));
    }
}
/*</module>*/
?>