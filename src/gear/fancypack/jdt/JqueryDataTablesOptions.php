<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\fancypack\core\client\GearClientLibraryOptions;
use gear\fancypack\core\client\GearJqueryAjaxOptions;
use gear\fancypack\core\client\IGearHtmlTargetSelector;
use gear\fancypack\jdt\languages\JqueryDataTablesLanguagePack;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JqueryDataTablesOptions extends GearClientLibraryOptions
{
    const JdtColumnFilterModeList = 'list';
    const JdtColumnFilterModeText = 'text';
    const JdtColumnFilterModeNone = 'none';
    const JdtColumnFilterModeBoolean = 'bool';
    const JdtColumnFilterModeDateTime = 'date';



    /**
     * @JsonIgnore
     *
     * @var bool
     */
    public $apiInstance;
    /**
     * @JsonIgnore
     *
     * @var string
     */
    public $declareVariable;



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

    /** @var JqueryDataTablesRowReorder */
    public $rowReorder;

    /** @var mixed */
    public $select;

    /** @var GearJqueryAjaxOptions */
    public $ajax;

    /** @var JqueryDataTablesColumnInfo[] */
    public $columns;

    /** @var JqueryDataTablesColumnDefinition[] */
    public $columnDefs;

    /** @var JqueryDataTablesColumnInfo[] */
    public $searchCols;

    /** @var JqueryDataTablesLanguagePack */
    public $language;

    /** @var mixed */
    public $data;

    /** @var mixed */
    public $initComplete;


    /**
     * @param callable $initializer
     * @return JqueryDataTablesOptions
     */
    public static function createDefault($initializer = null)
    {
        $instance = new self();

        $instance->apiInstance = true;
        $instance->autoWidth = true;

        if (is_callable($initializer)) {
            $initializer($instance);
        }

        return $instance;
    }

    /**
     * @param callable $initializer
     * @return JqueryDataTablesOptions
     */
    public static function createDefaultServerSide($initializer = null)
    {
        $instance = new self();

        $instance->apiInstance = true;
        $instance->processing = true;
        $instance->serverSide = true;
        $instance->orderMulti = true;
        $instance->autoWidth = true;

        if (is_callable($initializer)) {
            $initializer($instance);
        }

        return $instance;
    }

    /**
     * @param callable $initializer
     * @return JqueryDataTablesOptions
     */
    public static function create($initializer = null)
    {
        $instance = new self();

        if (is_callable($initializer)) {
            $initializer($instance);
        }

        return $instance;
    }
}
/*</module>*/
?>