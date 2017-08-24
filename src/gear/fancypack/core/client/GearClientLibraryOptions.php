<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\core\client;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearClientLibraryOptions extends GearJsOptions
{
    /**
     * @JsonIgnore
     *
     * @var IGearHtmlTargetSelector
     */
    public $targetSelector;

    /**
     * @return IGearHtmlTargetSelector
     */
    public function getTargetSelector()
    {
        $result = $this->targetSelector;
        if ($result == null) {
            $result = new GearJquerySelector();
            $this->targetSelector = $result;
        }
        return $result;
    }
}
/*</module>*/
?>