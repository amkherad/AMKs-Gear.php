<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\core\InspectableClass;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<requires>*/
/*</requires>*/

/*<module>*/
class InspectableClass
{
    public function GetProperty($n)
    {
        throw new InvalidOperationException("Property '$n' not found.");
    }
    public final function __get($n)
    {
        return property_exists($this, $n)
            ? $this->$n
            : $this->GetProperty($n);
    }
    public function __isset($n)
    {
        $result = property_exists($this,$n)
            ? $this->$n
            : $this->GetProperty($n);
        return $result == $this
            ? true
            : isset($result);
    }
}
/*</module>*/
?>