<?php
require_once('../../build.php');
$rstart=microtime(true);
//--------------Required Stages--------------
require_once('../../bin/gear.arch.php');
$engineTime=(microtime(true)-$rstart);
//echo Mvc::GetModule('debug');
$MvcPathUseFullPath=true;
$engine = GearAppEngine::create();
$engine->start();
//-------------------------------------------
$duration=(microtime(true)-$rstart);
?>