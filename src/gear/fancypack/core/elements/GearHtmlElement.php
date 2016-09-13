<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\core\elements;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearSerializer;
use gear\arch\io\GearHtmlStream;
use gear\arch\io\IGearHtmlRenderer;
use gear\arch\io\IGearOutputStream;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
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
/*</module>*/
?>