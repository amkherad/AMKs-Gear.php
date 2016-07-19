<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 3:48 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http\results;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
class ErrorResult extends ActionResultBase
{
    private $error;
    public function __construct($error)
    {
        $this->error = $error;
    }

    public function executeResult($context, $request, $response)
    {
        throw new Exception($this->error);
    }
}
/*</module>*/
?>