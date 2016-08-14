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
abstract class IdEntityBase implements IGearIdEntity
{
    public
        $id
    ;
}

/*</module>*/
?>