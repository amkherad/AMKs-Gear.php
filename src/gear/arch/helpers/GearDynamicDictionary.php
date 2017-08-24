<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDynamicDictionary implements \ArrayAccess
{
    private $v, $a;//a:true all isset=true else if exists.

    public function __construct($p, $a = false)
    {
        $this->v = $p;
        $this->a = $a;
    }

    public function __set($k, $val)
    {
        $this->v[$k] = $val;
    }

    public function &__get($k)
    {
        return $this->v[$k];
    }

    public function __isset($k)
    {
        return $this->a || isset($this->v[$k]);
    }

    public function __unset($k)
    {
        unset($this->v[$k]);
    }

    public function offsetExists($o)
    {
        return $this->a || isset($this->v[$o]);
    }

    public function &offsetGet($o)
    {
        return $this->v[$o];
    }

    public function offsetSet($o, $val)
    {
        $this->v[$o] = $val;
    }

    public function offsetUnset($o)
    {
        unset($this->v[$o]);
    }

    public function setInnerBuffer(&$v)
    {
        $this->v = $v;
    }
}
/*</module>*/
?>