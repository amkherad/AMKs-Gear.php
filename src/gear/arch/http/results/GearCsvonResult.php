<?php
//$SOURCE_LICENSE$

/*
 *  CSVON  CSV Object Notation - Created by Ali Mousavi Kherad
 */

/*<requires>*/
//GearActionResultBase
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\app\GearAppEngine;
use gear\arch\core\IGearContext;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
/*</namespace.use>*/

/*<module>*/
class GearCsvonResult extends GearActionResultBase
{
    /** @var string */
    protected $name;
    /** @var mixed */
    protected $content;
    /** @var bool */
    protected $allowGet;

    /**
     * GearJsonResult constructor.
     * @param string $name
     * @param mixed $content
     * @param bool $allowGet
     */
    public function __construct($name, $content, $allowGet)
    {
        $this->name = $name;
        $this->content = $content;
        $this->allowGet = $allowGet;
    }

    public function executeResult($context, $request, $response)
    {
        $method = $request->getMethod();
        $allowGet = $context->getConfig()->getValue(Gear_Key_JsonResultAllowGet, Gear_Section_ActionResolver, false);
        if ($method == 'GET' && !($this->allowGet || $allowGet)) {
            return new GearErrorResult("Action is not configured to serve data as GET http method.");
        }

        $json = $this->createJson($context, $request, $response, $this->content);
        $response->setContentType('application/csvon');
        $this->writeResult($context, $request, $response, $json);
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param mixed $content
     * @return mixed
     */
    public function createJson($context, $request, $response, $content)
    {
        return $content;
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param string $data
     *
     * @return GearErrorResult
     */
    public function writeResult($context, $request, $response, $data)
    {
        $childArray = false;
        $grandChildArray = false;

        $result = $this->csvonSerialize(0, '_', $this->name, $data, 'a', "\n", $childArray, $grandChildArray)."\n\n";

        if (GearAppEngine::isDebug()) {
            $response->setHeader('CSVON-Length', strlen($result));
        }

        $response->write($result);
    }


    private function csvonSerialize($level, $parent, $name, $data, $descriptor, $separator, &$childArray, &$childrenAsColumns)
    {
        $lines = [];

        if (is_array($data) || is_object($data)) {
            $desc = 'a';
            if (is_object($data)) {
                $data = (array)$data;
                $desc = 'o('.get_class($data).')';
            }
            $anyChildArray = false;
            $allChildArray = true;
            $innerLines = [];
            $allChildrenAsColumns = true;
            foreach ($data as $key => $row) {
                $childrenAsColumns1 = true;
                $childArray1 = false;
                $result = $this->csvonSerialize($level + 1, $name, $key, $row, $desc, ',', $childArray1, $childrenAsColumns1);
                if (empty($result)) {
                    continue;
                }
                if (!$childrenAsColumns1) {
                    $allChildrenAsColumns = false;
                }
                if ($childArray1) {
                    $separator = "\n";
                    //$lines[] = $key;
                    $anyChildArray = true;
                    $childrenAsColumns = false;
                } else {
                    $allChildArray = false;
                }
                $innerLines[] = $result;
            }
            $childArray = true;
            if ($allChildArray && $allChildrenAsColumns) {
                $descriptor = 't';
            }
            if ($anyChildArray) {
                $lines[] = $level.','.$parent.','.$descriptor.','. $name . ',';
            }
            if (!empty($innerLines)) {
                array_push($lines, ...$innerLines);
            }
            if ($anyChildArray) {
                $lines[] = null;
            }
        } else {
            $childrenAsColumns = false;
            $childArray = false;
            return $data;
        }

        return implode($separator, $lines);
    }
}
/*</module>*/
?>