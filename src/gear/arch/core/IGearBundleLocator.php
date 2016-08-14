<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
    /*</namespace.current>*/
    /*<namespace.use>*/
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
interface IGearBundleLocator
{
    /**
     * @param $path string
     * @param $require bool
     * @param $once bool
     *
     * @return bool
     */
    function tryLocate($path, $require, $once);
}

/*</module>*/
?>