<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHttpHelper
{
    /**
     * @param string $headerBlock
     * @return array
     */
    public static function parseHeaders($headerBlock)
    {
        $headerBlock = preg_replace('/^\r\n/m', '', $headerBlock);
        $headerBlock = preg_replace('/\r\n\s+/m', ' ', $headerBlock);
        preg_match_all('/^([^: ]+):\s(.+?(?:\r\n\s(?:.+?))*)?\r\n/m', $headerBlock . "\r\n", $matches);

        $result = array();
        foreach ($matches[1] as $key => $value)
            $result[$value][] = (array_key_exists($value, $result) ? $result[$value] . "\n" : '') . $matches[2][$key];

        return $result;
    }

    /**
     * @param array $headerLines
     * @return array
     */
    public static function parseHeaderLines($headerLines)
    {
        $new_headers = [];
        foreach ($headerLines as $header) {
            list($key, $value) = explode(':', $header, 2);
            $new_headers[trim($key)][] = trim($value);
        }
        return $new_headers;
    }
}
/*</module>*/
?>