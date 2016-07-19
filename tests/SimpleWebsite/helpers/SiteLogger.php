<?php
class SiteLogger implements ILogger
{
    function write($mixed, $category = null)
    {
        file_put_contents('sitelog.txt', $mixed, FILE_APPEND);
    }
}
?>