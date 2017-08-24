<?php
namespace Negril\IO;

\Module::Load('Negril\IO\Path');

use Negril\IO\Path as Path;

class Directory{
    public static function RecursiveCopy($dest,$src){
        $dir=opendir($src);
        if($dir===false) throw new \Exception("Source directory '$src' not found.");
        if(!file_exists($dest)) @mkdir($dest);
        while(false!==($file=readdir($dir))){
            $sourceFile=Path::Combine($src,$file);
            $destFile=Path::Combine($dest,$file);
            if(($file!='.')&&($file!='..')){
                if(is_dir($sourceFile))
                    static::RecursiveCopy($destFile,$sourceFile);
                else
                    copy($sourceFile,$destFile);
            }
        }
        closedir($dir);
    }
}
?>