<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearHttpNotFoundException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\view;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\exceptions\GearHttpNotFoundException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearViewFileNotFoundException extends GearHttpNotFoundException
{
    public function __construct($action, $additionalInfo = null)
    {
        parent::__construct($action == null
            ? "View file not found.$additionalInfo"
            : "View file '$action' not found.$additionalInfo");
    }
}
/*</module>*/
?>