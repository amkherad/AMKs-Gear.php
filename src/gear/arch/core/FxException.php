<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/18/2016
 * Time: 4:04 AM
 */
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\core;
use gear\arch\core\IMessageException;
/*</namespaces>*/

/*<bundles>*/
Bundle::Arch('core\IMessageException');
/*</bundles>*/

/*<module>*/
class FxException extends \Exception implements IMessageException
{
    private $httpStatusCode;
    public function __construct($message, $httpStatusCode = 500, $code = 0)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code);
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
/*</module>*/
?>