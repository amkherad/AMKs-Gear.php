<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace GearCmsRemotePanel\areas\cp\controllers;
/*</namespace.current>*/
/*<namespace.use>*/
use GearCmsRemotePanel\areas\cp\controllers\base\BaseController;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class homeController extends BaseController
{
    public function index()
    {
        return $this->view();
    }
}
/*</module>*/
?>