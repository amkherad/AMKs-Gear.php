<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\viewhelpers\html;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
use gear\arch\core\GearSerializer;
use gear\arch\io\GearHtmlStream;
use gear\arch\io\IGearOutputStream;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
abstract class HtmlElement
{
    /** @var array|null */
    protected $attrs = [];
    public $useLowerCaseAttributes = true;

    /**
     * HtmlElement constructor.
     * @param array $attrs
     */
    public function __construct($attrs = null)
    {
        if ($attrs != null) {
            $this->attrs = $attrs;
        }
    }

    public function __toString()
    {
        $hs = new GearHtmlStream();
        $this->renderToStream($hs);
        return strval($hs->getBuffer());
    }

    /**
     * @param IGearOutputStream $stream
     * @return mixed
     */
    public abstract function renderToStream($stream);

    public function __call($name, $args)
    {
        $this->attrs[($this->useLowerCaseAttributes ? strtolower($name) : $name)] = GearSerializer::stringify($args);
        return $this;
    }

    public function attribute($key, $value)
    {
        $this->attrs[($this->useLowerCaseAttributes ? strtolower($key) : $key)] = $value;
        return $this;
    }

    public function attributes($arr)
    {
        if (!is_array($arr)) {
            throw new GearInvalidOperationException("Only arrays accepted in HtmlElement::Attrs().");
        }
        foreach ($arr as $key => $value) {
            $this->attrs[($this->useLowerCaseAttributes ? strtolower($key) : $key)] = $value;
        }
        return $this;
    }

    public function getCustomAttributes()
    {
        return self::serializeCustomeAttributes($this->attrs, $this->useLowerCaseAttributes);
    }

    public static function serializeCustomAttributes($attrs, $useLowerCaseAttrs = true)
    {
        $ret = '';
        if (is_array($attrs)) {
            foreach ($attrs as $key => $value) {
                if (isset($key) && isset($value)) {
                    $ret .= ' ' . ($useLowerCaseAttrs ? strtolower($key) : $key) . "=\"$value\"";
                }
            }
            $ret .= ' ';
        }
        return $ret;
    }

    public function preferLowerCaseAttributes($val = true)
    {
        $this->useLowerCaseAttributes = $val;
        return $this;
    }
}
/*</module>*/
?>