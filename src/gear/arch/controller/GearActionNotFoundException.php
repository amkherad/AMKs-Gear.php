<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearHttpNotFoundException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\exceptions\GearHttpNotFoundException;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearActionNotFoundException extends GearHttpNotFoundException
{
    public function __construct($actionName = null, $lookupActionNames = null)
    {
        if ($lookupActionNames == null || empty($lookupActionNames)) {
            parent::__construct("Action '$actionName' not found.");
        } else {
            $allActions = implode(', ', $lookupActionNames);
            parent::__construct("Action '$actionName' not found, using following lookup list: [$allActions]");
        }
    }
}
/*</module>*/
?>