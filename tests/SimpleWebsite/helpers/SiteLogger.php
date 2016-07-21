<?php
class SiteLogger implements IGearLogger
{
    function write($mixed, $category = null)
    {
        file_put_contents('sitelog.log', $mixed, FILE_APPEND);
    }
}
?>