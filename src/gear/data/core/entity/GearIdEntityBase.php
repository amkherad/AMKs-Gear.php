<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearIdEntity
/*</requires>*/

/*<namespace.current>*/
namespace gear\data\core\entity;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\entity\IGearIdEntity;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
abstract class GearIdEntityBase implements IGearIdEntity
{
    public
        $id
    ;
    
    function getId() {
        return $this->id;
    }
}

/*</module>*/
?>