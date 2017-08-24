<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\helpers\GearMimeHelper;
use gear\arch\http\exceptions\GearHttpNotFoundException;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearFileResult extends GearActionResultBase
{
    /** @var string */
    public $filePath;
    /** @var string */
    public $mimeType;

    public function __construct($filePath, $mimeType = null)
    {
        $this->filePath = $filePath;
        $this->mimeType = $mimeType;
    }

    public function executeResult($context, $request, $response)
    {
        if (file_exists($this->filePath)) {
            if ($this->mimeType == null) {
                $mime = GearMimeHelper::getMimeFromExtension($this->filePath);
                $response->setContentType($mime);
            } else {
                $response->setContentType($this->mimeType);
            }

            echo file_get_contents($this->filePath);
        } else {
            throw new GearHttpNotFoundException("File '$this->filePath' not found.");
        }
    }
}
/*</module>*/
?>