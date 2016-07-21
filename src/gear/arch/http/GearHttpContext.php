<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class GearHttpContext
{
    static
        $currentContext;

    public $Ending;

    public function End()
    {
        if (is_callable($this->Ending)) {
            $c = $this->Ending;
            $c();
        }
        exit;
    }

    public static function current()
    {
        return self::$currentContext;
    }

    public static function setCurrent($context)
    {
        self::$currentContext = $context;
    }
}
/*</module>*/
?>