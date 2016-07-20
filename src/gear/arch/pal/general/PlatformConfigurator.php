<?php
//$SOURCE_LICENSE$

/*
 * This file used to call for some initializer on specified platform.
 */

/*<namespace.current>*/
namespace gear\arch;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\HttpContext;
/*</namespace.use>*/

/*<generals>*/

Bundle::setRootDirectory(getcwd());

function RenderBody()
{
    $context = HttpContext::current();
    $output = $context->getService(Gear_ServiceViewOutputStream);
    if($output != null) {
        $context->getResponse()->write($output->getBuffer());
    }
}

/*</generals>*/
?>