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
class searchController extends BaseController
{
    public function query(SearchModel $model)
    {
        $this->dataBag->query = $model->q;

        return $this->view();
    }
}
class SearchModel
{
    public $q;
}
/*</module>*/
?>