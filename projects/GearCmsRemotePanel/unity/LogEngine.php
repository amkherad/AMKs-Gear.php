<?php
class LogEngine implements IGearLogger
{
    function write($mixed, $category = null)
    {
        file_put_contents('sitelog.log', "$mixed\r\n", FILE_APPEND);
    }
}