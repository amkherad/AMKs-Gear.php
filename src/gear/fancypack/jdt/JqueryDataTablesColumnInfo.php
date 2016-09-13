<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\jdt;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\automation\annotations\GearAnnotationHelper;
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
    /** @var mixed */
    public $individualColumnInfo;

    /** @var JqueryDataTablesSearchModel */
    public $search;

    /** @var mixed */
    public $render;
    /** @var mixed */
    public $createdCell;

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

    public static function fromModel($model)
    {
        $reflection = new ReflectionClass($model);

        $props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

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
                if (!is_numeric($a)) {
                    return 0;
                } else {
                    return -1;
                }
            }
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        $orderAfter = [];
        $orderBefore = [];
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
            }
        }

        foreach ($orderAfter as $ak => $order) {
            foreach ($result as $bk => $r) {
                if ($r->name == $order) {
                    $result[$bk] = $result[$ak];
                    $result[$ak] = $r;
                    break;
                }
            }
        }
        foreach ($orderBefore as $ak => $order) {
            foreach ($result as $bk => $r) {
                if ($r->name == $order) {
                    $result[$bk] = $result[$ak];
                    $result[$ak] = $r;
                    break;
                }
            }
        }

        //echo GearJsOptions::serialize($result);exit;

        return $result;
    }
}

/*</module>*/
?>