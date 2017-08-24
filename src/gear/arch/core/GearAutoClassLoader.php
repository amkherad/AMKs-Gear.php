<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearClassLoader
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\core;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearClassLoader;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearAutoClassLoader implements IGearClassLoader
{
    private $className;
    private $namespace;

    public function __construct($className, $namespace)
    {
        $this->className = $className;
        $this->namespace = $namespace;
    }

    public function createInstance($params)
    {
        $alias = "$this->namespace\\$this->className";

        if ($params == null) {
            return new $alias();
        } else {
            return new $alias($params);
        }
    }
}
/*</module>*/
?>