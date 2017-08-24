<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
    /*</namespace.current>*/
    /*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/

class GearProfiler
{
    private $start;
    private $end;

    public function startProfiling()
    {
        $this->start = microtime(true);
    }

    public function endProfiling()
    {
        $this->end = microtime(true);
    }

    public function reset()
    {
        $this->start = microtime(true);
    }

    public function getStart()
    {
        if (isset($this->start)) {
            return $this->start;
        } else {
            throw new GearInvalidOperationException("Profiler does not started yet.");
        }
    }

    public function getEnd()
    {
        if (isset($this->end)) {
            return $this->end;
        } else {
            throw new GearInvalidOperationException("Profiler does not finished yet.");
        }
    }

    public function getTotalTime()
    {
        if (!isset($this->start)) {
            throw new GearInvalidOperationException("Profiler does not started yet.");
        }
        if (!isset($this->end)) {
            throw new GearInvalidOperationException("Profiler does not finished yet.");
        }

        return $this->end - $this->start;
    }
}
/*</module>*/
?>