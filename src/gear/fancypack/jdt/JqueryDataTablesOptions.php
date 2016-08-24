<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\fancypack\jdt\languages\JqueryDataTablesLanguagePack;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JqueryDataTablesOptions
{
    const JdtColumnFilterModeList = 'list';
    const JdtColumnFilterModeText = 'text';
    const JdtColumnFilterModeNone = 'none';
    const JdtColumnFilterModeBoolean = 'bool';
    const JdtColumnFilterModeDateTime = 'date';

    /** @var bool */
    public $processing;
    /** @var bool */
    public $serverSide;

    /** @var bool */
    public $searching;

    /** @var bool */
    public $ordering;
    /** @var mixed */
    public $order;

    /** @var bool */
    public $paging;
    /** @var string */
    public $pagingType;

    /** @var bool */
    public $responsive;

    /**
     * "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
     * @var array
     */
    public $lengthMenu;

    /**
     * no-footer
     * @var string
     */
    public $noFooter;

    /** @var int */
    public $scrollX;
    /** @var int */
    public $scrollY;

    /** @var mixed */
    public $footerCallback;

    /** @var mixed */
    public $dom;

    /** @var mixed */
    public $paginationType;

    /** @var bool */
    public $autoWidth;
    /** @var bool */
    public $info;
    /** @var bool */
    public $lengthChange;
    /** @var bool */
    public $stateSave;
    /** @var bool */
    public $jQueryUI;
    /** @var bool */
    public $filterOnEnter;

    /** @var bool */
    public $orderMulti;

    /** @var mixed */
    public $colReorder;

    /** @var mixed */
    public $rowReorder;

    /** @var mixed */
    public $select;

    /** @var mixed */
    public $ajax;

    /** @var JqueryDataTablesColumnInfo[] */
    public $columns;

    /** @var JqueryDataTablesColumnInfo[] */
    public $searchCols;

    /** @var JqueryDataTablesLanguagePack */
    public $language;

    /** @var mixed */
    public $data;

    public function __toString()
    {
        $fields = [];

        if (isset($this->processing)) $fields['processing'] = $this->processing;
        if (isset($this->serverSide)) $fields['serverSide'] = $this->serverSide;
        if (isset($this->searching)) $fields['searching'] = $this->searching;
        if (isset($this->ordering)) {
            $fields['ordering'] = $this->ordering;
            if (isset($this->order)) $fields['order'] = "\"$this->order\"";
        }
        if (isset($this->paging)) {
            $fields['paging'] = $this->paging;
            if (isset($this->pagingType)) $fields['pagingType'] = "\"$this->pagingType\"";
        }
        if (isset($this->responsive)) $fields['responsive'] = $this->responsive;
        if (isset($this->lengthMenu)) $fields['lengthMenu'] = $this->lengthMenu;
        if (isset($this->noFooter)) $fields['noFooter'] = "\"$this->noFooter\"";
        if (isset($this->scrollX)) $fields['scrollX'] = $this->scrollX;
        if (isset($this->scrollY)) $fields['scrollY'] = $this->scrollY;
        if (isset($this->footerCallback)) $fields['footerCallback'] = "\"$this->footerCallback\"";
        if (isset($this->dom)) $fields['dom'] = "\"$this->dom\"";
        if (isset($this->paginationType)) $fields['paginationType'] = "\"$this->paginationType\"";
        if (isset($this->autoWidth)) $fields['autoWidth'] = $this->autoWidth;
        if (isset($this->info)) $fields['info'] = $this->info;
        if (isset($this->lengthChange)) $fields['lengthChange'] = $this->lengthChange;
        if (isset($this->stateSave)) $fields['stateSave'] = $this->stateSave;
        if (isset($this->jQueryUI)) $fields['jQueryUI'] = $this->jQueryUI;
        if (isset($this->filterOnEnter)) $fields['filterOnEnter'] = $this->filterOnEnter;
        if (isset($this->orderMulti)) $fields['orderMulti'] = $this->orderMulti;
        if (isset($this->colReorder)) $fields['colReorder'] = $this->colReorder;
        if (isset($this->rowReorder)) $fields['rowReorder'] = $this->rowReorder;
        if (isset($this->select)) $fields['select'] = $this->select;
        if (isset($this->ajax)) $fields['ajax'] = $this->ajax;
        if (isset($this->columns)) $fields['columns'] = $this->columns;
        if (isset($this->searchCols)) $fields['searchCols'] = $this->searchCols;
        if (isset($this->language)) $fields['language'] = $this->language;
        if (isset($this->data)) $fields['data'] = $this->data;

        return self::_serializeArray($fields);
    }

    private static function _serializeArray($fields)
    {
        $elements = [];
        foreach($fields as $fieldName => $fieldValue) {
            if (is_object($fieldValue)) {
                $fieldValue = strval($fieldValue);
                $elements[] = "\"$fieldName\":$fieldValue";
            } elseif (is_array($fieldValue)) {
                $fieldValue = self::_serializeArray($fields);
                $elements[] = "\"$fieldName\":$fieldValue";
            } elseif (is_string($fieldValue)) {
                $elements[] = "\"$fieldName\":\"$fieldValue\"";
            } else {
                $elements[] = "\"$fieldName\":$fieldValue";
            }
        }
        return '['.implode($elements).']';
    }
}
/*</module>*/
?>