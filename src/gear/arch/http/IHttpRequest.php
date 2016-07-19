<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 1:37 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IHttpRequest
{
    function getValue($name);
    function getMethod();
    function accepts();
    function getAllValues();
    function &getCurrentMethodValues();
}
/*</module>*/
?>