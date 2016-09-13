<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\core\client;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearJsFunctionListCall extends GearRawOutput
{
    /**
     * GearJsFunctionListCall constructor.
     * @param array $functions
     */
    public function __construct($functions)
    {
        $functionCalls = '';
        foreach($functions as $func) {
            $functionCalls .= "$func();";
        }
        parent::__construct('function(){'.$functionCalls.'}');
    }
}
/*</module>*/
?>