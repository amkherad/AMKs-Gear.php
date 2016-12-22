<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\controller\GearController;
use gear\arch\core\GearExtensibleClass;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\view\GearViewFileNotFoundException;
use gear\arch\view\IGearViewEngine;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearHtmlHelper extends GearExtensibleClass
{
    /** @var IGearContext */
    private $context;
    /** @var IGearMvcContext */
    private $mvcContext;
    /** @var GearUrlHelper */
    private $url;
    /** @var GearController */
    private $controller;

    /**
     * UrlHelper constructor.
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param GearUrlHelper $urlHelper
     */
    public function __construct($context, $mvcContext, $urlHelper, $controller)
    {
        parent::__construct(true);

        $this->context = $context;
        $this->mvcContext = $mvcContext;
        $this->url = $urlHelper;
        $this->controller = $controller;
    }

    /**
     * @return IGearContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return IGearMvcContext
     */
    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    /**
     * @return GearUrlHelper
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function partial($name, $model = null, $params = null)
    {
        $context = $this->context;
        $viewEngineFactory = $context->getService(Gear_ServiceViewEngineFactory);

        /** @var IGearViewEngine $viewEngine */
        $viewEngine = $viewEngineFactory->createEngine($context);

        if(!isset($name)){
            $name = $context->getRoute()->getMvcContext()->getActionName();
        }

        $viewEngine->renderPartialView(
            $context,
            $this->controller,
            $name,
            $model
        );

        return null;
    }
    public function partialIfExists($name, $model = null, $params = null)
    {
        try {
            $context = $this->context;
            $viewEngineFactory = $context->getService(Gear_ServiceViewEngineFactory);

            /** @var IGearViewEngine $viewEngine */
            $viewEngine = $viewEngineFactory->createEngine($context);

            if(!isset($name)) {
                $name = $context->getRoute()->getMvcContext()->getActionName();
            }

            $viewEngine->renderPartialView(
                $context,
                $this->controller,
                $name,
                $model
            );

            return null;
        } catch (GearViewFileNotFoundException $ex) {

        }
    }
}

/*</module>*/
?>