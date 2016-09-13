<?php

define('BUILD_Module_Content_Begin', '/*<module>*/');
define('BUILD_Module_Content_End', '/*</module>*/');

define('BUILD_NamespaceCurrent_Content_Begin', '/*<namespace.current>*/');
define('BUILD_NamespaceCurrent_Content_End', '/*</namespace.current>*/');
define('BUILD_NamespaceUse_Content_Begin', '/*<namespace.use>*/');
define('BUILD_NamespaceUse_Content_End', '/*</namespace.use>*/');
define('BUILD_NamespaceUse3rdParty_Content_Begin', '/*<namespace.use-3rdparty>*/');
define('BUILD_NamespaceUse3rdParty_Content_End', '/*</namespace.use-3rdparty>*/');

define('BUILD_Bundles_Content_Begin', '/*<bundles>*/');
define('BUILD_Bundles_Content_End', '/*</bundles>*/');

define('BUILD_Requires_Content_Begin', '/*<requires>*/');
define('BUILD_Requires_Content_End', '/*</requires>*/');

define('BUILD_Generals_Content_Begin', '/*<generals>*/');
define('BUILD_Generals_Content_End', '/*</generals>*/');

define('BUILD_Includes_Content_Begin', '/*<includes>*/');
define('BUILD_Includes_Content_End', '/*</includes>*/');

$BUILD_filesLog = false;

function BUILD_getAllModulesIn($path, $fileFormats)
{
    global $BUILD_filesLog;
    $dI = new RecursiveDirectoryIterator($path);
    $files = array();
    foreach (new RecursiveIteratorIterator($dI) as $dir) {
        if (strtolower($dir->getExtension()) != 'php') continue;
        $fName = $dir->getFilename();
        $path = $dir->getPath();
        $dir = $dir->getPathname();
        //if($path=='.'&&$fName==$fileName)continue;
        if ($fName == '.' || $fName == '..') continue;
        //if($dir==$zipFile)continue;
        //$files[]=$dir;
        if ($BUILD_filesLog) {
            echo "$dir<br>";
        }
        $files[] = $dir;
    }
    return $files;
}

function BUILD_getModuleContent($path)
{
    return file_get_contents($path);
}

function BUILD_getSection(&$content, $begin, $end)
{
    $startsAt = strpos($content, $begin);

    if (!is_numeric($startsAt) || $startsAt < 0) return '';
    $startsAt += strlen($begin);

    $endsAt = strpos($content, $end, $startsAt);

    if (is_numeric($endsAt) && $endsAt >= 0)
        $result = substr($content, $startsAt, $endsAt - $startsAt);
    else
        $result = '';

    return $result;
}


function GetFileName($p)
{
    $d = strrpos($p, '.');
    if (!is_bool($d) && $d >= 0) return ($d < strlen($p) - 1 ? substr($p, 0, $d) : '');
    return null;
}

function BUILD_expandFiles($files, $requireStart, $requireEnd)
{
    $result = [];
    foreach ($files as $file) {
        $record = [
            'path' => $file,
            'name' => GetFileName(basename($file)),
            'content' => BUILD_getModuleContent($file)
        ];

        $tempRequires = explode("\n", BUILD_getSection($record['content'], $requireStart, $requireEnd));
        $allRequires = [];
        foreach ($tempRequires as $r) {
            if ($r == '' || is_null($r) || $r == "\r" || $r == "\n") continue;
            $allRequires[] = trim(trim($r, '/'));
        }
        $record['requires'] = $allRequires;
        $result[] = $record;
    }
    return $result;
}

function BUILD_satisfiedAllRequires($includedModules, $module)
{
    foreach ($module['requires'] as $require) {
        $exists = false;
        foreach ($includedModules as $mod) {
            if ($mod['name'] == $require) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            return false;
        }
    }
    return true;
}

function BUILD_resolveDependencies($bundleId, $modules)
{
    $BUILD_exportedModules = [];
    $totalModulesCount = count($modules);
    for ($i = 0; $i < $totalModulesCount; $i++) {
        $module = $modules[$i];
        if (!isset($module['requires']) || count($module['requires']) == 0 || $module['requires'] == null) {
            $BUILD_exportedModules[] = $module;
            unset($modules[$i]);
        }
    }
    $totalModulesCount = count($modules);
    while ($totalModulesCount > 0) {
        $isAdded = false;
        foreach ($modules as $index => $module) {
            if (BUILD_satisfiedAllRequires($BUILD_exportedModules, $module)) {
                unset($modules[$index]);
                array_push($BUILD_exportedModules, $module);
                $isAdded = true;
            }
        }
        $totalModulesCount = count($modules);
        if (!$isAdded) {
            echo "<div style=\"color:red;\"><h4>BUILD ERRORS ($bundleId)<br>==========================</h4><br><p>";
            foreach ($modules as $module) {
                echo $module['name'] . '<br>';
            }
            echo '</p><hr></div>';
            break;
        }
        //break;
    }
    return $BUILD_exportedModules;
}

function BUILD_makeBundle(
    $bundleId,
    $outputName,
    $compressedOutputName,
    $rootPath,
    $outputPath,
    $fileFormats,
    $additionalFiles,
    $generateHeaders = true
)
{
    $BUILD_modules = BUILD_getAllModulesIn($rootPath, $fileFormats);
    foreach ($additionalFiles as $addFile) {
        $BUILD_modules[] = $addFile;
    }
    $BUILD_modules = BUILD_expandFiles($BUILD_modules, BUILD_Requires_Content_Begin, BUILD_Requires_Content_End);

    usort($BUILD_modules,
        function ($a, $b) {
            return basename($a['path']) > basename($b['path'])
                ? 1 : -1;
        });

    $BUILD_exportedModules = BUILD_resolveDependencies($bundleId, $BUILD_modules);

    $BUILD_totalModule = '';
    $BUILD_totalGenerals = '';
    $BUILD_totalBundles = '';
    $BUILD_3rdpartyUse = [];
    foreach ($BUILD_exportedModules as $dir) {
        $moduleContent = $dir['content'];

        $moduleBody = BUILD_getSection($moduleContent, BUILD_Module_Content_Begin, BUILD_Module_Content_End);
        $moduleGenerals = BUILD_getSection($moduleContent, BUILD_Generals_Content_Begin, BUILD_Generals_Content_End);
        $moduleNamespaceCurrent = BUILD_getSection($moduleContent, BUILD_NamespaceCurrent_Content_Begin, BUILD_NamespaceCurrent_Content_End);
        $moduleNamespaceUse = BUILD_getSection($moduleContent, BUILD_NamespaceUse_Content_Begin, BUILD_NamespaceUse_Content_End);
        $moduleBundles = BUILD_getSection($moduleContent, BUILD_Bundles_Content_Begin, BUILD_Bundles_Content_End);

        $moduleNamespaceUse3rdParty = '';
        $moduleNamespaceUse3rdParty_Source = BUILD_getSection($moduleContent, BUILD_NamespaceUse3rdParty_Content_Begin, BUILD_NamespaceUse3rdParty_Content_End);
        $moduleNamespaceUse3rdParty_Lines = explode("\n", $moduleNamespaceUse3rdParty_Source);
        foreach($moduleNamespaceUse3rdParty_Lines as $line) {
            if (!isset($BUILD_3rdpartyUse[$line])) {
                $BUILD_3rdpartyUse[$line] = true;
                $moduleNamespaceUse3rdParty = "$line\n";
            }
        }

        $moduleBody = trim($moduleBody);
        $moduleGenerals = trim($moduleGenerals);
        $moduleBundles = trim($moduleBundles);
        $moduleNamespaceUse3rdParty = trim($moduleNamespaceUse3rdParty);
        if ($moduleNamespaceUse3rdParty != '') {
            if ($moduleBody != '') $BUILD_totalModule .= "$moduleNamespaceUse3rdParty
$moduleBody\n";
        } else {
            if ($moduleBody != '') $BUILD_totalModule .= "$moduleBody\n";
        }
        if ($moduleGenerals != '') $BUILD_totalGenerals .= "$moduleGenerals\n";
        if ($moduleBundles != '') $BUILD_totalBundles .= "$moduleBundles\n";
    }

    $bundleIdUnderlined = str_replace('.', '', $bundleId);

    $header = '';
    $headerCompressed = '';
    if ($generateHeaders) {
        $header = "
define('{$bundleIdUnderlined}_IsPackaged', true);
";
        $headerCompressed = "
define('{$bundleIdUnderlined}_IsPackaged', true);
define('{$bundleIdUnderlined}_IsCompressedBundle', true);
";
    }

    $BUILD_totalContentNormal = "<?php
//Bundle: $bundleId

/* Modules: */
$BUILD_totalModule

/* Generals: */
$BUILD_totalGenerals
";

    $BUILD_totalContentCompressed = "<?php
//Bundle: $bundleId

/* Modules: */
$BUILD_totalModule

/* Generals: */
$BUILD_totalGenerals
";

    file_put_contents("$outputPath\\$outputName", $BUILD_totalContentNormal);
    //file_put_contents("$outputPath\\$compressedOutputName", $BUILD_totalContentCompressed);
}


/****************************************************************
 *****************************************************************
 *****************************************************************
 *****************************************************************
 *****************************************************************/

$BUILD_rootDirectory = dirname(__FILE__);

$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear',
    'gear.arch.php',
    'gear.arch.c.php',
    "$BUILD_rootDirectory/src/gear/arch",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    [
        "$BUILD_rootDirectory/src/gear/gearinclude.php"
    ]
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.Data',
    'gear.data.php',
    'gear.data.c.php',
    "$BUILD_rootDirectory/src/gear/data/core",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.Orm.RedBeanPhp',
    'gear.rb-lib.php',
    'gear.rb-lib.c.php',
    "$BUILD_rootDirectory/src/gear/plugins/orm/redbeanphp/lib/redbean",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    [],
    false
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.Orm.RedBeanPhp',
    'gear.orm-rb.php',
    'gear.orm-rb.c.php',
    "$BUILD_rootDirectory/src/gear/plugins/orm/redbeanphp/core",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.Authentication',
    'gear.auth.php',
    'gear.auth.c.php',
    "$BUILD_rootDirectory/src/gear/auth",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.Authentication.RedBeanPhp',
    'gear.auth-rb.php',
    'gear.auth-rb.c.php',
    "$BUILD_rootDirectory/src/gear/plugins/orm/redbeanphp/auth",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.Cms',
    'gear.cms.php',
    'gear.cms.c.php',
    "$BUILD_rootDirectory/src/gear/cms",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.Appfix',
    'gear.appfix.php',
    'gear.appfix.c.php',
    "$BUILD_rootDirectory/src/gear/appfix",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Kunststube',
    'gear.kunststube.php',
    'gear.kunststube.c.php',
    "$BUILD_rootDirectory/src/gear/_3rdparty/kunststube",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.FancyPack',
    'gear.fancypack.php',
    'gear.fancypack.c.php',
    "$BUILD_rootDirectory/src/gear/fancypack/core",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'Gear.ViewHelpers',
    'gear.view-helpers.php',
    'gear.view-helpers.c.php',
    "$BUILD_rootDirectory/src/gear/fancypack/viewhelpers",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'jQueryDataTables',
    'gear.jdt.php',
    'gear.jdt.c.php',
    "$BUILD_rootDirectory/src/gear/fancypack/jdt",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);
$BUILD_filesLog = false;
BUILD_makeBundle(
    'jQueryTableSorter',
    'gear.jts.php',
    'gear.jts.c.php',
    "$BUILD_rootDirectory/src/gear/fancypack/jts",
    "$BUILD_rootDirectory/bin",
    [
        'php'
    ],
    []
);