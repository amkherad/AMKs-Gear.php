<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearStatusCodeResult
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\GearLogger;
use gear\arch\http\results\GearStatusCodeResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearInternalServerErrorResult extends GearStatusCodeResult
{
    /** @var \Exception */
    private $exception;
    /** @var string */
    private $userMessage;

    /**
     * GearInternalServerErrorResult constructor.
     * @param string $message
     * @param \Exception $exception
     */
    public function __construct($message = null, $exception = null)
    {
        $this->exception = $exception;
        $this->userMessage = $message;
        parent::__construct(500, $message);
    }

    public function writeResult($context, $request, $response)
    {
        //parent::writeResult($context, $request, $response);
        $response->setContentType('application/json');

        if (defined('DEBUG')) {
            $response->write(\GearSerializer::json([
                'message' => $this->userMessage,
                'trace' => $this->exception
            ]));
        } else {
            $uuid = uniqid();
            GearLogger::write("$uuid - $this->exception");

            $response->write(\GearSerializer::json([
                'message' => $this->userMessage,
                'traceUid' => $uuid
            ]));
        }
    }
}
/*</module>*/
?>