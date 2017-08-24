<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearEngineFactory
{
    /**
     * @param $context IGearContext
     *
     * @return mixed Target engine.
     */
    function createEngine($context);
}
/*</module>*/
?>