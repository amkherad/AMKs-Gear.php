<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
    /*</namespace.current>*/
    /*<namespace.use>*/
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
use gear\fancypack\jdt\languages\JqueryDataTablesLanguagePack;

class JqueryDataTablesOptions
{
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
}
/*</module>*/
?>