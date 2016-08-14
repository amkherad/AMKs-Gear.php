<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearLogger
{
    /**
     * @param $mixed mixed
     * @param null $category
     * @return mixed
     */
    function write($mixed, $category = null);
}
/*</module>*/
?>