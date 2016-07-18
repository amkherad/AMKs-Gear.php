<?php
//$SOURCE_LICENSE$

/*<requires>*/
//FxException
/*</requires>*/

/*<namespaces>*/
namespace gear\arch\app;
use gear\arch\core\FxException;
/*</namespaces>*/

/*<bundles>*/
Bundle::Arch('core\FxException');
/*</bundles>*/

/*<module>*/
class AppEngineNotFoundException extends FxException
{
    public function __construct()
    {
        parent::__construct("Specified app engine not found.", 500);
    }
}
/*</module>*/
?>