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
class GearExtensibleClass
{
    /** @var array */
    private $extensionMethods = [];
    private $caseSensitive = false;

    /**
     * GearExtensibleClass constructor.
     * @param $caseSensitive bool Indicates the case-sensitiveness of name comparison.
     */
    public function __construct($caseSensitive)
    {
        $this->caseSensitive = $caseSensitive;
    }

    public function __call($name, $args)
    {
        if (isset($this->extensionMethods[$name])) {
            $method = $this->extensionMethods[$name];
        } else {
            throw new GearInvalidOperationException("Method '$name' not found.");
        }

        return call_user_func($method, $args, $this);
    }

    /**
     * Adds an extension method to extended methods list.
     * @param $name
     * @param $callableValue
     */
    public function setExtensionMethod($name, $callableValue)
    {
        if ($this->caseSensitive) {
            $name = strtolower($name);
        }
        $this->extensionMethods[$name] = $callableValue;
    }

    /**
     * Adds a list of extension methods to extended methods list.
     * @param $dictionaryOfCallable
     */
    public function setExtensionMethods($dictionaryOfCallable)
    {
        $caseSensitive = $this->caseSensitive;
        foreach ($dictionaryOfCallable as $name => $callable) {
            if ($caseSensitive) {
                $name = strtolower($name);
            }
            $this->extensionMethods[$name] = $callable;
        }
    }

    /**
     * Removes an extension method from extended methods list.
     * @param $name
     */
    public function removeExtensionMethod($name)
    {
        if ($this->caseSensitive) {
            $name = strtolower($name);
        }
        unset($this->extensionMethods[$name]);
    }
}
/*</module>*/
?>