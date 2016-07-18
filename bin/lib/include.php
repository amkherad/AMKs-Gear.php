<?php
//$MVC_LICENSE$
//$CODESECTION_BEGIN$
if(!class_exists('Module')){
class Module{
    public static function Load($name){
        require_once(__DIR__ .'/'. strtolower(str_replace('\\','/',$name)).'.php');
    }
}}
//$CODESECTION_END$
?>