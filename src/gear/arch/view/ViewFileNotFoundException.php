<?php
//$SOURCE_LICENSE$

/*<requires>*/
//HttpNotFoundException
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\view;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\exceptions\HttpNotFoundException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class ViewFileNotFoundException extends HttpNotFoundException
{
    public function __construct($action)
    {
        parent::__construct($action == null
            ? "404 - View file not found."
            : "404 - View file '$action' not found.");
    }
}
/*</module>*/
?>