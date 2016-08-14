<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;
/*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/

class GearHttpContext
{
    static
        $currentContext;

    public $ending;

    public function End()
    {
        if (is_callable($this->ending)) {
            $c = $this->ending;
            $c();
        }
        exit;
    }

    /**
     * @return IGearContext
     */
    public static function current()
    {
        return self::$currentContext;
    }

    /**
     * @param $context IGearContext
     */
    public static function setCurrent($context)
    {
        self::$currentContext = $context;
    }
}
/*</module>*/
?>