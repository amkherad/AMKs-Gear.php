<?php

$BUILD_MODULE_CONTENT_BEGIN = '/*<module>*/';
$BUILD_MODULE_CONTENT_END = '/*</module>*/';

$BUILD_rootDirectory = dirname(__FILE__);

$BUILD_root = dirname(__FILE__) . "\\src";
$BUILD_archRoute = "$BUILD_root\\gear\\arch";

$BUILD_output = "$BUILD_rootDirectory\\bin";
$BUILD_outputName = 'gear.php';
$BUILD_outputCompressedName = 'gear.c.php';

$BUILD_outputPharName = 'gear.phar';
$BUILD_outputPharCompressedName = 'gear.c.phar';

function BUILD_getAllModulesIn($path)
{
    $dI = new RecursiveDirectoryIterator($path);
    $files = array();
    foreach (new RecursiveIteratorIterator($dI) as $dir) {
        $fName = $dir->getFilename();
        $path = $dir->getPath();
        $dir = $dir->getPathname();
        //if($path=='.'&&$fName==$fileName)continue;
        if ($fName == '.' || $fName == '..') continue;
        //if($dir==$zipFile)continue;
        //$files[]=$dir;
        $files[] = $dir;
    }
    return $files;
}

function BUILD_getModuleContent($path, $begin, $end)
{
    $fileContent = file_get_contents($path);

    $startsAt = strpos($fileContent, $begin);

    if (!is_numeric($startsAt) || $startsAt < 0) return '';
    $startsAt += strlen($begin);

    $endsAt = strpos($fileContent, $end, $startsAt);

    if (is_numeric($endsAt) && $endsAt >= 0)
        $result = substr($fileContent, $startsAt, $endsAt - $startsAt);
    else
        $result = '';

    return $result;
}

$BUILD_modules = BUILD_getAllModulesIn($BUILD_archRoute);

usort($BUILD_modules,
    function($a, $b) {
        return basename($a) > basename($b)
            ? 1 : -1;
    });

$BUILD_totalContent = '';
foreach ($BUILD_modules as $dir) {
    $BUILD_totalContent .= BUILD_getModuleContent($dir, $BUILD_MODULE_CONTENT_BEGIN, $BUILD_MODULE_CONTENT_END) . "\n";
}

$BUILD_totalContentNormal = "<?php\n
define('Gear_IsPackaged', true);
" . $BUILD_totalContent;

$BUILD_totalContentCompressed = "<?php\n
define('Gear_IsPackaged', true);
define('Gear_IsCompressedBundle', true);
" . $BUILD_totalContent;

file_put_contents("$BUILD_output\\$BUILD_outputName", $BUILD_totalContentNormal);
file_put_contents("$BUILD_output\\$BUILD_outputCompressedName", $BUILD_totalContentCompressed);