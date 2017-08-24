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
    protected static $staticExtensionMethods = [];
    /** @var array */
    protected $extensionMethods = [];
    /** @var array */
    protected static $memberExtensionMethods = [];

    protected $caseSensitive = false;

    /**
     * GearExtensibleClass constructor.
     * @param $caseSensitive bool Indicates the case-sensitiveness of name comparison.
     */
    public function __construct($caseSensitive = false)
    {
        $this->caseSensitive = $caseSensitive;
    }

    public function __call($name, $args)
    {
        $lowerName = strtolower($name);
        if (!$this->caseSensitive) {
            $name = $lowerName;
        }
        if (isset(static::$memberExtensionMethods[$lowerName])) {
            $method = static::$memberExtensionMethods[$lowerName];
            if ($args == null) {
                $args = [$this];
            } else {
                $args = array_merge([$this], $args);
            }
        } elseif (isset($this->extensionMethods[$name])) {
            $method = $this->extensionMethods[$name];
        } elseif (isset(static::$staticExtensionMethods[$lowerName])) {
            $method = static::$staticExtensionMethods[$lowerName];
        } else {
            throw new GearInvalidOperationException("Method '$name' not found.");
        }

        return call_user_func_array($method, $args);
    }

    /**
     * Adds an extension method to extended member methods list.
     * @param $name
     * @param $callableValue
     */
    public static function setMemberExtensionMethod($name, $callableValue)
    {
        $name = strtolower($name);
        static::$memberExtensionMethods[$name] = $callableValue;
    }

    /**
     * Adds a list of extension methods to extended member methods list.
     * @param $dictionaryOfCallable
     */
    public static function setMemberExtensionMethods($dictionaryOfCallable)
    {
        foreach ($dictionaryOfCallable as $name => $callable) {
            $name = strtolower($name);
            static::$memberExtensionMethods[$name] = $callable;
        }
    }

    /**
     * Removes an extension method from extended member methods list.
     * @param $name
     */
    public function removeMemberExtensionMethod($name)
    {
        $name = strtolower($name);
        unset(static::$memberExtensionMethods[$name]);
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


    /**
     * Adds an extension method to extended methods list.
     * @param $name
     * @param $callableValue
     */
    public static function setStaticExtensionMethod($name, $callableValue)
    {
        $name = strtolower($name);
        static::$staticExtensionMethods[$name] = $callableValue;
    }

    /**
     * Adds a list of extension methods to extended methods list.
     * @param $dictionaryOfCallable
     */
    public static function setStaticExtensionMethods($dictionaryOfCallable)
    {
        foreach ($dictionaryOfCallable as $name => $callable) {
            $name = strtolower($name);
            static::$staticExtensionMethods[$name] = $callable;
        }
    }

    /**
     * Removes an extension method from extended methods list.
     * @param $name
     */
    public static function removeStaticExtensionMethod($name)
    {
        $name = strtolower($name);
        unset(static::$staticExtensionMethods[$name]);
    }
}
/*</module>*/
?>