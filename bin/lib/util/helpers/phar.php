<?php
namespace Util\Helpers;

\Module::Load('Negril\IO\Exceptions\FileNotFound');

use Negril\IO\Path                      as Path;
use Negril\IO\Exceptions\FileNotFound   as FileNotFoundException;

class Phar{
    public static function Dump($path,$content=true,$html=true){
        $p = new \Phar($path, 0);
        // Phar extends SPL's DirectoryIterator class
        foreach (new \RecursiveIteratorIterator($p) as $file) {
            // $file is a PharFileInfo class, and inherits from SplFileInfo
            echo $file->getFileName()."\n";
            var_dump($file->getMetadata());
            if($content)
                echo file_get_contents($file->getPathName())."\n";
            echo($html?"\n":"<br>\n");
        }
        if(isset($p['internal/file.php'])){
            var_dump($p['internal/file.php']->getMetadata());
        }
    }
    public static function AddDirectoryToPhar($phar,$path,$pharPath=null){
        if(!isset($phar)) throw new FileNotFoundException($phar);
        if(isset($pharPath)) $phar->addEmptyDir($pharPath);
        
        self::_addDirectoryToPhar($phar,$path,$pharPath);
    }
    private static function _addDirectoryToPhar($phar,$path,$pharPath){
        $fsOs=array_diff(scandir($path),array('.','..'));
        foreach($fsOs as $file){
            if($file=='.'||$file=='..')continue;
            $fPath=Path::Combine($path,$file);
            $oPath=Path::Combine($pharPath,$file);
            if(is_dir($fPath)){
                $phar->addEmptyDir($oPath);
                self::_addDirectoryToPhar($phar,$fPath,$oPath);
            }else{
                $phar->addFile($fPath,$oPath);
            }
        }
    }
}
?>