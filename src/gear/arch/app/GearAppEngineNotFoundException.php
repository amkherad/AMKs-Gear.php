<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearFxException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\app;
use gear\arch\core\GearFxException;
/*</namespace.current>*/

/*<bundles>*/
Bundle::Arch('core\FxException');
/*</bundles>*/

/*<module>*/
class GearAppEngineNotFoundException extends GearFxException
{
    public function __construct()
    {
        parent::__construct("Specified app engine not found.", 500);
    }
}
/*</module>*/
?>