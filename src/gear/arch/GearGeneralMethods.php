<?php
//$SOURCE_LICENSE$

/*
 * This file used to call for some initializer on specified platform.
 */

/*<namespace.current>*/
namespace gear\arch;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\app\GearAppEngine;
use gear\arch\http\GearHttpContext;
/*</namespace.use>*/

/*<generals>*/
function RenderBody()
{
    $context = GearHttpContext::current();
    if ($context == null) return;

    $response = $context->getResponse();
    $response->flushInnerStream();

//    $output = $context->getService(Gear_ServiceViewOutputStream);
//    if($output != null) {
//        if ($response != null) {
//            $response->write($output->getBuffer());
//        }
//    }
}

function isDebug() {
    return GearAppEngine::isDebug();
}
/*</generals>*/
?>