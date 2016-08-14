<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core\GearInspectableClass;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<requires>*/
/*</requires>*/

/*<module>*/
class GearInspectableClass
{
    /**
     * @param $name
     *
     * @return mixed
     *
     * @throws GearInvalidOperationException
     */
    public function getProperty($name)
    {
        throw new GearInvalidOperationException("Property '$name' not found.");
    }
    public final function __get($name)
    {
        return property_exists($this, $name)
            ? $this->$name
            : $this->getProperty($name);
    }
    public function __isset($name)
    {
        $result = property_exists($this,$name)
            ? $this->$name
            : $this->getProperty($name);
        return $result == $this
            ? true
            : isset($result);
    }
}
/*</module>*/
?>