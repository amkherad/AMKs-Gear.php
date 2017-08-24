<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearModel
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\model;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\model\IGearModel;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearModel implements IGearModel
{
    public function validate(&$errorDictionary)
    {
        $errorDictionary = array();
        return true;
    }
}
/*</module>*/
?>