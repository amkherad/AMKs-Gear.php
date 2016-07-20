<?php
require_once('../../build.php');
$rstart=microtime(true);
//--------------Required Stages--------------
require_once('../../bin/gear.php');
$engineTime=(microtime(true)-$rstart);
//echo Mvc::GetModule('debug');
$MvcPathUseFullPath=true;
$engine = AppEngine::create();
$engine->start();
//-------------------------------------------
$duration=(microtime(true)-$rstart);
?>
<hr>
<p style="color:red;">
    Create Time: <?=$engine->getCreateExecutionTime();?> <br>
    Start Time: <?=$engine->getStartExecutionTime();?> <br>
    Root Time: <?=$duration;?> <br>
    Engine Time: <?=$engineTime;?> <br>
    Total Time: <?=$duration-$engineTime;?> / <?=$engine->getCreateExecutionTime()+$engine->getStartExecutionTime();?> <br>
</p>