<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearHttpResponse
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
use gear\arch\helpers\GearHttpHelper;
use gear\arch\core\GearSerializer;
use gear\arch\io\GearHtmlStream;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHttpResponse implements IGearHttpResponse
{
    /** @var GearHtmlStream */
    private $buffer;
    /** @var bool */
    private $bufferStatus;

    /** @var GearHtmlStream */
    private $innerStream;

    /** @var string */
    private $encoding = 'UTF-8';

    /** @var string */
    private $contentType = null;

    public function __construct()
    {
    }

    public function write($mixed)
    {
        if (is_string($mixed)) {
            if ($this->bufferStatus) {
                $this->buffer->write($mixed);
            } else {
                echo $mixed;
            }
        } elseif (is_object($mixed) || is_array($mixed)) {
            $this->setContentType('application/json');
            if ($this->bufferStatus) {
                $this->buffer->write(GearSerializer::json($mixed));
            } else {
                echo GearSerializer::json($mixed);
            }
        } else {
            if ($this->bufferStatus) {
                $this->buffer->write(GearSerializer::stringify($mixed));
            } else {
                echo GearSerializer::stringify($mixed);
            }
        }
    }

    public function clear()
    {
        if ($this->buffer != null) {
            $this->buffer->clear();
        }
    }

    public function serializeWrite($object, $request)
    {
        echo GearSerializer::stringify($object);
    }

    public function getInnerStream()
    {
        if ($this->innerStream == null) {
            $this->innerStream = new GearHtmlStream();
        }
        return $this->innerStream;
    }

    public function writeInnerStream($mixed)
    {
        if ($this->innerStream = null) {
            $this->innerStream = new GearHtmlStream();
        }
        $this->innerStream->write($mixed);
    }

    public function flushInnerStream()
    {
        if ($this->innerStream != null) {
            $this->write($this->innerStream->getBuffer());
            $this->innerStream->clear();
        }
    }

    public function setStatusCode($statusCode)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        header(Gear_PoweredResponseHeader, true, $statusCode);
    }

    public function getStatusCode()
    {
        return http_response_code();
    }

    public function getHeader($name, $defaultValue = null)
    {
        $name = strtolower($name);
        $headers = array_change_key_case($this->getHeaders(), CASE_LOWER);
        if (isset($headers[$name])) {
            return $headers[$name];
        }
        return $defaultValue;
    }

    public function setHeader($name, $value, $replace = true)
    {
        //if (headers_sent()) {
        //    throw new GearInvalidOperationException();
        //}
        if (!headers_sent()) {
            @header("$name: $value", $replace);
        }
    }

    public function setHeaderLegacy($header, $replace = true)
    {
        //if (headers_sent()) {
        //    throw new GearInvalidOperationException();
        //}
        if (!headers_sent()) {
            @header($header, $replace);
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @return bool
     */
    public function headerExists($name, &$value)
    {
        $name = strtolower($name);
        $headers = array_change_key_case($this->getHeaders(), CASE_LOWER);
        if (isset($headers[$name])) {
            $value = $headers[$name];
            return true;
        }
        $value = null;
        return false;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headerLines = headers_list();
        return GearHttpHelper::parseHeaderLines($headerLines);
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        if ($this->contentType != null) {
            return $this->contentType;
        }
        return $this->getHeader('Content-Type', null);
    }

    /**
     * @param string $contentType
     * @return mixed|void
     * @throws GearInvalidOperationException
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->_setContentType($contentType, $this->encoding);
    }

    /**
     * @param string $contentType
     * @param string $encoding
     */
    private function _setContentType($contentType, $encoding)
    {
        $this->setHeader('Content-Type', "$contentType; charset=$encoding");
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return mb_http_output();
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        $this->_setContentType($this->getContentType(), $encoding);
    }

    /**
     * @param string|$name
     * @param string|null $value
     * @param string|null $expire
     * @param string|null $path
     * @param string|null $domain
     * @param bool|null $secure
     * @param bool|null $httpOnly
     * @return mixed|void
     */
    public function setCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    /**
     * @param string $name
     * @param string|null $defaultValue
     * @return null
     */
    public function getCookie($name, $defaultValue = null)
    {
        $cookies = $this->getCookies();
        return
            isset($cookies[$name])
                ? $cookies[$name]
                : $defaultValue;
    }

    /**
     * @return array
     */
    public function getCookies()
    {
        $cookies = $this->getHeader('Set-Cookie');
        if ($cookies == null) {
            return [];
        }

        $cookieList = [];
        foreach ($cookies as $cookieStr) {
            $parts = explode(';', $cookieStr);

            $cookieFields = [];
            if (sizeof($parts) > 0) {
                foreach ($parts as $part) {
                    list($key, $value) = explode('=', $part, 2);
                    $cookieFields[$key] = $value;
                }
            }
            foreach ($cookieFields as $key => $value) {
                $cookieList[$key] = array_merge(['value' => $value], $cookieFields);
            }
        }

        return $cookieList;
    }

    public function isRedirect()
    {
        $status = $this->getStatusCode();
        return $status == 301 || $status == 302 || $status == 303;
    }

    public function redirectUrl()
    {
        if ((!$this->headerExists('Location', $redirect)) && !$this->isRedirect()) {
            throw new GearInvalidOperationException();
        }

        return $redirect;
    }


    public function isJson()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'application/json' ||
            $contentType == 'text/json';
    }

    public function isXml()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'application/xml' ||
            $contentType == 'text/xml';
    }

    public function isPlainText()
    {
        $contentType = strtolower($this->getContentType());
        return $contentType == 'text/plain';
    }

    public function isHtml()
    {
        $contentType = strtolower($this->getContentType());
        return $contentType == 'text/html';
    }

    public function setBufferStatus($status, $flushOld = false)
    {
        if (!$status && $flushOld) {
            $this->flush();
        }
        $this->bufferStatus = $status;
        if ($status && $this->buffer == null) {
            $this->buffer = new GearHtmlStream();
        }
    }

    public function flush()
    {
        if ($this->buffer != null) {
            echo $this->buffer->getBuffer();
            $this->buffer->clear();
        }
    }

    public function getBufferStream()
    {
        if ($this->buffer == null) {
            $this->buffer = new GearHtmlStream();
        }
        return $this->buffer;
    }

    public function &getBuffer()
    {
        return $this->buffer;
    }

    public function bufferSize()
    {
        $size = 0;
        if ($this->buffer != null) {
            $size = $this->buffer->bufferSize();
        }
        return $size;
    }

    public function getBody()
    {
        if ($this->buffer != null) {
            return $this->buffer->getBuffer();
        }
    }
}
/*</module>*/
?>