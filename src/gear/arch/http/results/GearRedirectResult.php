<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearStatusCodeResult
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\results\GearStatusCodeResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearRedirectResult extends GearStatusCodeResult
{
    /** @var string */
    private $url;

    public function __construct($url, $isPermanent = false)
    {
        $this->url = $url;
        if ($isPermanent) {
            parent::__construct(301, 'Moved Permanently');
        } else {
            parent::__construct(302, 'Found');
        }
    }

    public function writeResult($context, $request, $response)
    {
        return $response->setHeader('Location', $this->url);
    }
}
/*</module>*/
?>