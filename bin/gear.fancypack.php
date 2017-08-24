<?php
//Bundle: Gear.FancyPack

/* Dependencies: */


/* Modules: */
class GearClientLibraryOptions extends GearJsOptions
{
    /**
     * @JsonIgnore
     *
     * @var IGearHtmlTargetSelector
     */
    public $targetSelector;

    /**
     * @return IGearHtmlTargetSelector
     */
    public function getTargetSelector()
    {
        $result = $this->targetSelector;
        if ($result == null) {
            $result = new GearJquerySelector();
            $this->targetSelector = $result;
        }
        return $result;
    }
}
abstract class GearHtmlElement implements IGearHtmlRenderer
{
    protected $attributes = [];

    public function __toString()
    {
        $outputStream = new GearHtmlStream();
        $this->renderToStream($outputStream);
        return strval($outputStream->getBuffer());
    }

    public function __call($name, $args)
    {
        $name = strtolower($name);
        $this->attributes[$name] = implode(' ', $args);
        return $this;
    }

    /**
     * @param bool|true $useLowerCaseAttributes
     * @return string
     */
    public function getCustomAttributes($useLowerCaseAttributes = true)
    {
        return self::serializeCustomAttributes($this->attributes, $useLowerCaseAttributes);
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        $key = strtolower($key);
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return null
     */
    public function getAttribute($key)
    {
        $key = strtolower($key);
        return isset($this->attributes[$key])
            ? $this->attributes[$key]
            : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function attributeExists($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @return string
     */
    public function getSerializedAttributes()
    {
        return self::serializeCustomAttributes($this->attributes);
    }

    /**
     * @param array $attributes
     * @param bool|true $useLowerCaseAttributes
     * @return string
     */
    public static function serializeCustomAttributes($attributes, $useLowerCaseAttributes = true)
    {
        $ret = '';
        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                if (isset($key) && isset($value)) {
                    if ($useLowerCaseAttributes) {
                        $key = strtolower($key);
                    }
                    $ret .= " $key=\"$value\"";
                }
            }
            $ret .= ' ';
        }
        return $ret;
    }


    /**
     * @param IGearOutputStream $stream
     * @return void
     */
    public abstract function renderToStream($stream);
}
class GearJqueryAjaxOptions extends GearClientLibraryOptions
{
    /** @var string */
    public $url;
    /** @var string */
    public $type;

    /** @var string */
    public $accepts;
    /** @var bool */
    public $async;
    /** @var int */
    public $beforeSend;
    /** @var bool */
    public $cache;
    public $complete;
    public $contents;
    /** @var string */
    public $contentType;
    public $context;
    /** @var string */
    public $converters;
    /** @var bool */
    public $crossDomain;
    public $data;
    public $dataFilter;
    /** @var string */
    public $dataType;
    public $error;
    /** @var bool */
    public $global;
    public $headers;
    /** @var bool */
    public $ifModified;
    /** @var bool */
    public $isLocal;
    /** @var string */
    public $jsonp;
    public $jsonpCallback;
    /** @var string */
    public $method;
    /** @var string */
    public $mimeType;
    /** @var string */
    public $password;
    /** @var bool */
    public $processData;
    /** @var string */
    public $scriptCharset;
    public $statusCode;
    public $success;
    /** @var int */
    public $timeout;
    /** @var bool */
    public $traditional;
    /** @var string */
    public $username;
    public $xhr;
    public $hxtFields;
}
class GearJquerySelector implements IGearHtmlTargetSelector
{
    const TargetSelectorTypeMinified = 'minified';
    const TargetSelectorTypeUseJqueryObject = 'jquery';

    private $type;

    public function __construct($type = self::TargetSelectorTypeMinified)
    {
        $this->type = $type;
    }

    public function buildSelectorFor($name)
    {
        switch ($this->type)
        {
            case self::TargetSelectorTypeMinified:
                return "$('#$name')";
            case self::TargetSelectorTypeUseJqueryObject:
                return "jQuery('#$name)'";
            default:
                throw new GearInvalidOperationException();
        }
    }

    public function buildSelectorForArgs($name, $args)
    {
        switch ($this->type)
        {
            case self::TargetSelectorTypeMinified:
                return "$('#$name')";
            case self::TargetSelectorTypeUseJqueryObject:
                return "jQuery('#$name)'";
            default:
                throw new GearInvalidOperationException();
        }
    }
}
class GearJsFunctionListCall extends GearRawOutput
{
    /**
     * GearJsFunctionListCall constructor.
     * @param array $functions
     */
    public function __construct($functions)
    {
        $functionCalls = '';
        foreach($functions as $func) {
            $functionCalls .= "$func();";
        }
        parent::__construct('function(){'.$functionCalls.'}');
    }
}
class GearJsOptions
{
    const JsonEscapeComment = '@JsonIgnore';

    public static function serialize($objOrArray)
    {
        if (is_array($objOrArray)) {
            return self::_serializeArray($objOrArray);
        } elseif (is_object($objOrArray)) {
            return self::_serializeObject($objOrArray);
        } else {
            throw new GearInvalidOperationException();
        }
    }

    private static function _serializeObject($obj)
    {
        $reflection = new ReflectionClass($obj);

        $props = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $fields = [];
        foreach ($props as $prop) {
            if (stripos($prop->getDocComment(), self::JsonEscapeComment) !== false) {
                continue;
            }
            $val = $prop->getValue($obj);
            $name = $prop->getName();
            if (is_object($val)) {
                if ($val instanceof GearRawOutput) {
                    $val = strval($val);
                    $fields[] = "\"$name\":$val";
                } else {
                    $fields[] = "\"$name\":" . self::_serializeObject($val);
                }
            } elseif (is_array($val)) {
                $fields[] = "\"$name\":" . self::_serializeArray($val);
            } elseif (is_numeric($val)) {
                $fields[] = "\"$name\":$val";
            } elseif (is_string($val)) {
                $fields[] = "\"$name\":\"$val\"";
            } elseif(is_bool($val)) {
                if ($val) {
                    $fields[] = "\"$name\":true";
                } else {
                    $fields[] = "\"$name\":false";
                }
            }
        }

        return '{' . implode(',', $fields) . '}';
    }
    private static function _serializeArray($src)
    {
        $array = [];
        foreach($src as $key => $element) {
            if (is_numeric($element)) {
                $array[] = $element;
            } elseif (is_string($element)) {
                $array[] = '"'.$element.'"';
            } elseif (is_array($element)) {
                $array[] = self::_serializeArray($element);
            } elseif (is_object($element)) {
                $array[] = self::_serializeObject($element);
            } elseif(is_bool($element)) {
                $array[] = $element ? 'true' : 'false';
            }
        }
        return '[' . implode(',', $array) . ']';
    }

    public function __toString()
    {
        return self::serialize($this);
    }
}
class GearRawOutput
{
    /** @var string */
    private $rawOutput;

    /**
     * GearRawOutput constructor.
     * @param string $rawOutput
     */
    public function __construct($rawOutput)
    {
        $this->rawOutput = $rawOutput;
    }

    public function __toString()
    {
        return $this->rawOutput;
    }
}
class GearRawVariableTargetSelector implements IGearHtmlTargetSelector
{
    public function buildSelectorFor($name)
    {
        return $name;
    }

    public function buildSelectorForArgs($name, $args)
    {
        return $name;
    }
}
interface IGearHtmlTargetSelector
{
    function buildSelectorFor($name);
    function buildSelectorForArgs($name, $args);
}


/* Generals: */

