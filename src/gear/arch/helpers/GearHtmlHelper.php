<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearExtensibleClass;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;

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

    /**
     * UrlHelper constructor.
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param GearUrlHelper $urlHelper
     */
    public function __construct($context, $mvcContext, $urlHelper)
    {
        parent::__construct(true);

        $this->context = $context;
        $this->mvcContext = $mvcContext;
        $this->url = $urlHelper;
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
}

/*</module>*/
?>