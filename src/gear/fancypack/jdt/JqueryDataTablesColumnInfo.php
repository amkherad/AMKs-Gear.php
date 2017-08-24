<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\automation\annotations\GearAnnotationHelper;
use gear\arch\helpers\GearHelpers;
use gear\arch\helpers\IActionUrlBuilder;
use gear\fancypack\core\client\GearJsOptions;
use ReflectionClass;
use ReflectionProperty;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class JqueryDataTablesColumnInfo extends GearJsOptions
{
    const JdtIgnore = 'JdtIgnore';
    const JdtAnnotation = 'Jdt';

    /** @var string */
    public $name;
    /** @var string */
    public $data;
    /** @var string */
    public $orderData;
    /** @var bool */
    public $orderable;
    /** @var bool */
    public $searchable;
    /** @var string */
    public $filter;
    /** @var string */
    public $title;
    /** @var bool */
    public $isRegex;
    /** @var bool */
    public $visible;
    /** @var mixed */
    public $type;
    /** @var mixed */
    public $cellType;
    /** @var mixed */
    public $width;
    /** @var mixed */
    public $defaultContent;
    /** @var mixed */
    public $targets;
    /** @var mixed */
    public $contentPadding;

    /** @var JqueryDataTablesSearchModel */
    public $search;

    /** @var mixed */
    public $render;
    /** @var mixed */
    public $createdCell;

    /** @var string */
    public $filterMode;
    /** @var bool */
    public $filterUseRemoteData;
    /** @var string */
    public $filterRemoteDataUrl;
    /** @var string */
    public $filterRemoteDataAjaxRequestType;
    /** @var string */
    public $filterRemoteDataAjaxRequestData;
    /** @var mixed */
    public $filterList;
    /** @var string */
    public $filterPlaceHolder;
    /** @var string */
    public $filterRemoteDataController;
    /** @var string */
    public $filterRemoteDataAction;
    /** @var string */
    public $filterTrueDisplayName;
    /** @var string */
    public $filterFalseDisplayName;
    /** @var bool */
    public $filterAddNoFilter;
    /** @var string */
    public $filterNoFilterDisplayName;


    /**
     * @JsonIgnore
     *
     * @var int
     */
    public $modelOrder;

    private function __construct($name, $doc)
    {
        $this->name = $name;
        $this->data = $name;
        $this->title = $name;

        if ($doc != null) {
            $annotation = GearAnnotationHelper::exportAnnotation($doc, self::JdtAnnotation);

            if ($annotation != null) {
                $thisReflection = new ReflectionClass($this);
                $props = $thisReflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

                foreach ($props as $prop) {
                    $pName = $prop->getName();
                    $an = $annotation->getArg($pName);
                    if ($an !== null) {
                        $this->$pName = $an;
                    }
                }
            }
        }
    }

    /**
     * Creates a new instance of JqueryDataTablesColumnInfo
     *
     * @param string $name
     * @param string $title
     * @param string $data
     *
     * @return JqueryDataTablesColumnInfo
     */
    public static function create($name, $title = null, $data = null) {
        $instance = new self($name, null);

        $instance->title = $title;
        $instance->data = $data;

        return $instance;
    }

    /**
     * Creates a list of columns from a view model.
     *
     * @param object $model
     * @param IActionUrlBuilder $urlBuilder
     *
     * @return array
     */
    public static function fromModel($model, $urlBuilder)
    {
        $reflection = new ReflectionClass($model);

        $props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $result = [];
        foreach ($props as $prop) {
            $doc = $prop->getDocComment();
            if (stripos($doc, '@'.self::JdtIgnore) !== false) {
                continue;
            }

            $field = new JqueryDataTablesColumnInfo(
                $prop->getName(),
                $doc
            );

            if (!GearHelpers::isNullOrWhitespace($field->filterRemoteDataAction)) {
                $field->filterRemoteDataUrl = $urlBuilder->action(
                    $field->filterRemoteDataAction,
                    $field->filterRemoteDataController
                );
            }

            $result[] = $field;
        }

        usort($result, function ($a, $b) {
            $a = $a->modelOrder;
            $b = $b->modelOrder;
            if (!is_numeric($a)) {
                if (!is_numeric($b)) {
                    return 0;
                } else {
                    return 1;
                }
            }
            if (!is_numeric($b)) {
                return -1;
            }
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        $orderAfter = [];
        $orderBefore = [];
        $retVal = [];
        foreach ($result as $ko => $ro) {
            $mo = $ro->modelOrder;
            $colIndex = strpos($mo, ':');
            if ($colIndex !== false) {
                $action = substr($mo, 0, $colIndex);
                if ($action == 'before') {
                    $orderBefore[$ko] = substr($mo, $colIndex + 1);
                } else {
                    $orderAfter[$ko] = substr($mo, $colIndex + 1);
                }
            } else {
                $retVal[] = $ro;
            }
        }

        do {
            $action = false;
            foreach ($orderAfter as $ak => $order) {
                foreach ($retVal as $bk => $r) {
                    if ($r->name == $order) {
                        array_splice($retVal, $bk + 1, 0, [$result[$ak]]);
                        unset($orderAfter[$ak]);
                        $action = true;
                        break;
                    }
                }
            }
        } while ($action && false);

        do {
            $action = false;
            foreach ($orderBefore as $ak => $order) {
                foreach ($retVal as $bk => $r) {
                    if ($r->name == $order) {
                        array_splice($retVal, $bk, 0, [$result[$ak]]);
                        unset($orderBefore[$ak]);
                        $action = true;
                        break;
                    }
                }
            }
        } while ($action && false);

        return $retVal;
    }
}

/*</module>*/
?>