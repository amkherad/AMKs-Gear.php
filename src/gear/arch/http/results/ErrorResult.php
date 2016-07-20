<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 3:48 AM
 */
//$SOURCE_LICENSE$

/*<requires>*/
//ActionResultBase
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\results\ActionResultBase;
/*</namespace.use>*/

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