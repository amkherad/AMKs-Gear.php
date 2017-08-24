<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IJqueryDataTablesFilter
/*</requires>*/

/*<namespace.current>*/
namespace gear\fancypack\jdt;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JqueryDataTablesFilter implements IJqueryDataTablesFilter
{
    public static $defaultPageSize = 20;

    private $draw;
    private $apiInstance;
    private $start;
    private $length;
    private $generalFilter;
    private $compareMode;
    private $columns;
    private $exOptions;

    public function getDraw()
    {
        return $this->draw;
    }
    public function setDraw($draw)
    {
        $this->draw = $draw;
    }

    public function getApiInstance()
    {
        return $this->apiInstance;
    }
    public function setApiInstance($apiInstance)
    {
        $this->apiInstance = $apiInstance;
    }

    public function getStart()
    {
        return $this->start;
    }
    public function setStart($start)
    {
        $this->start = $start;
    }

    public function getLength()
    {
        return $this->length;
    }
    public function setLength($length)
    {
        $this->length = $length;
    }

    public function getGeneralFilter()
    {
        return $this->generalFilter;
    }
    public function setGeneralFilter($generalFilter)
    {
        $this->generalFilter = $generalFilter;
    }

    public function getCompareMode()
    {
        return $this->compareMode;
    }
    public function setCompareMode($compareMode)
    {
        $this->compareMode = $compareMode;
    }

    public function getColumns()
    {
        return $this->columns;
    }
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getExtendedOptions()
    {
        return $this->exOptions;
    }
    public function setExtendedOptions($exOptions)
    {
        $this->exOptions = $exOptions;
    }
}

/*</module>*/
?>