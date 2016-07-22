<?php

spl_autoload_register(function ($class) {
    GearBundle::resolveUserModule("$class.php");
});

?>