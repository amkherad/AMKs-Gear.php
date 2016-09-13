<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\core\client;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
use gear\fancypack\core\client\IGearHtmlTargetSelector;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearJquerySelector implements IGearHtmlTargetSelector
{
    const TargetSelectorTypeMinified = 'minified';
    const TargetSelectorTypeUseJqueryObject = 'jquery';

    private $type;

    public function __construct($type = self::TargetSelectorTypeMinified)
    {
        $this->type = $type;
    }

    public function buildSelectorFor($name)
    {
        switch ($this->type)
        {
            case self::TargetSelectorTypeMinified:
                return "$('#$name')";
            case self::TargetSelectorTypeUseJqueryObject:
                return "jQuery('#$name)'";
            default:
                throw new GearInvalidOperationException();
        }
    }

    public function buildSelectorForArgs($name, $args)
    {
        switch ($this->type)
        {
            case self::TargetSelectorTypeMinified:
                return "$('#$name')";
            case self::TargetSelectorTypeUseJqueryObject:
                return "jQuery('#$name)'";
            default:
                throw new GearInvalidOperationException();
        }
    }
}
/*</module>*/
?>