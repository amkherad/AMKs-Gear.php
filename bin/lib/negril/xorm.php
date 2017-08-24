<?php
Mvc::Module('negril\xorm\xorm');

if($opts=$Config['xorm'])
    Negril\XORM::$Instance->SetOptions($Config['xorm']);
?>