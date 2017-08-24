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
class GearRawOutput
{
    /** @var string */
    private $rawOutput;

    /**
     * GearRawOutput constructor.
     * @param string $rawOutput
     */
    public function __construct($rawOutput)
    {
        $this->rawOutput = $rawOutput;
    }

    public function __toString()
    {
        return $this->rawOutput;
    }
}
/*</module>*/
?>