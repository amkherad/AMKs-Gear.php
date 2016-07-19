<?php
require_once('../../build.php');
$rstart=microtime(true);
//--------------Required Stages--------------
require_once('../../bin/gear.php');
define('DEBUG',1);
//echo Mvc::GetModule('debug');
$MvcPathUseFullPath=true;
AppEngine::create()->start(AppEngine::Mvc);
//-------------------------------------------
$duration=(microtime(true)-$rstart);
?>
<hr>
<p style="color:red;">
    Total Time: <?=$duration;?>
</p>