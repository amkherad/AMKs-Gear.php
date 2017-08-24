<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\io;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHtmlString
{
    /** @var callable(string) */
    private static $compressor;

    /** @var string */
    private $buffer;
    /** @var bool */
    private $allowCompression;

    /**
     * GearHtmlString constructor.
     * @param string $buffer
     * @param bool $allowCompression
     */
    public function __construct($buffer, $allowCompression = true)
    {
        $this->buffer = $buffer;
        $this->allowCompression = $allowCompression;
    }

    /**
     * Clears the buffer.
     */
    public function clear()
    {
        $this->buffer = '';
    }

    /**
     * Appends string into buffer.
     *
     * @param string $value
     */
    public function append($value)
    {
        $this->buffer .= $value;
    }

    /**
     * Prepends string into buffer.
     *
     * @param string $value
     */
    public function prepend($value)
    {
        $this->buffer = $value . $this->buffer;
    }

    /**
     * Returns a copy of internal buffer.
     *
     * @return string
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * Provides reference access to internal buffer.
     *
     * @return string
     */
    public function &accessBuffer()
    {
        return $this->buffer;
    }

    public function __toString()
    {
        $compressor = self::$compressor;
        if ($this->allowCompression && $compressor != null) {
            return $compressor($this->buffer);
        }
        return $this->buffer;
    }
}
/*</module>*/
?>