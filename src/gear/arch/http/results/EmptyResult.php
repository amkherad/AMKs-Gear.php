<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 3:40 AM
 */
//$SOURCE_LICENSE$

/*<requires>*/
//IInnerActionResult
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\IInnerActionResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class EmptyResult extends ActionResultBase implements IInnerActionResult
{
    public function executeResult($context, $request, $response)
    {
        return null;
    }
}
/*</module>*/
?>