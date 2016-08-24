<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearHttpRequest
{
    /**
     * @param string $name
     * @param mixed|null $defaultValue
     *
     * @return mixed
     */
    function getValue($name, $defaultValue = null);

    /**
     * @return string
     */
    function getMethod();

    /**
     * @return string
     */
    function accepts();

    /**
     * @return array
     */
    function getAllValues();

    /**
     * @return array
     */
    function &getCurrentMethodValues();
}
/*</module>*/
?>