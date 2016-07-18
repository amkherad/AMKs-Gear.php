<?php
//$MVC_LICENSE$
//$CODESECTION_BEGIN$
if(!function_exists('__autoload')){
function __autoload($classname){
    if(!Mvc::ProbClass($classname))
        throw new MvcInvalidOperationException("Mvc auto loader is unable to find '$classname'.");
}}
//$CODESECTION_END$
?>