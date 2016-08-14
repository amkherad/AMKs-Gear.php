<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

interface IGearRouteService
{
    /**
     * @return mixed Underlying routing service configurator.
     */
    function getConfigurator();
    /**
     * Returns mvc context.
     *
     * @return IGearMvcContext
     */
    function getMvcContext();

    /**
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $params array
     *
     * @return string
     */
    function createUrl($context, $mvcContext, $params);

    /**
     * @param $provider IGearRouteService
     *
     * @return void
     */
    function setUrlProvider($provider);

    /**
     * Enables mvcContext cache.
     *
     * @return void
     */
    function enableCache();
}
/*</module>*/
?>