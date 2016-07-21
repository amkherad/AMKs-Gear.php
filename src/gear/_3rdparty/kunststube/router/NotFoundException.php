<?php

/*<namespace.current>*/
namespace Kunststube\Router;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearMessageException;
/*</namespace.use>*/

/*<module>*/
class NotFoundException extends \RuntimeException implements IGearMessageException
{
    public function getHttpStatusCode()
    {
        return 404;
    }
}
/*</module>*/