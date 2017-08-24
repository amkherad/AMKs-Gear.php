<?php
namespace Deployment\AMK;

\Module::Load('Negril\IO\Path');
\Module::Load('Negril\IO\Exceptions\FileNotFound');
\Module::Load('Util\Helpers\Phar');
require_once('project.php');

use Negril\IO\Path              as Path;
use Negril\IO\Directory         as Directory;
use Util\Helpers\Phar           as PharHelper;
use Negril\IO\Exceptions\FileNotFound   as FileNotFoundException;

final class Deploy{
    const IndexFileContent="<?php \$rstart=microtime(true); require_once('phar://bundles/%APPNAME%.phar'); \$MvcPathUseFullPath=true; Mvc::Start(); ?>";
    const _dot_htaccess_File="RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} !(\.css\.js|\.jpg|\.jpeg|\.png|\.gif|\.flv|\.swf|\.mp4|\.mp3|\.tiff|\.svc|\.mpg|\.mpeg|\.ogg|\.wav|\.wmp|\.wmf|\.avi|\.html|\.htm)$
RewriteRule ^.*$ index.php

DirectoryIndex index.php
ErrorDocument 404 /?controller=error&action=_404";
    const DenyFromAll="Deny from all";
    
    public static function Publish($publishPath){
        
    }
    public static function PublishProject($projectPath,$publishPath){
        $project = Project::FromIniFile($projectPath);
        
        $files=$project->getFiles();
        $meta=$project->getMeta();
        $mvcPath=$project->getMvcPath();
        $mvcFile=Path::Combine($mvcPath,'mvc.php');
        $mvcDir =Path::Combine($mvcPath,'mvc');
        if(!file_exists($mvcFile))
            throw new FileNotFoundException("Mvc file at '$mvcPath' not found.");
        
        $pub=$project->getPublish();
        if(!file_exists($pub)) mkdir($pub);
        if(!file_exists($pub)) throw new Exception("Unable to create directory '$pub'.");
        
        $sections=array();
        
        self::_initBundle($sections,$project->getBundle());
        $bundle=$sections['bundle'];
        if(isset($files['InternalMvc']) && $files['InternalMvc']){
            $bundle->addFile($mvcFile,'mvc.php');
            if(file_exists($mvcDir))
                PharHelper::AddDirectoryToPhar($bundle,$mvcDir,'/mvc/');
            $bundle->setStub($bundle->createDefaultStub('mvc.php','mvc.php'));
        }
        
        foreach($project->getMap() as $member)
            self::_takeCareOfMember($project,$sections,$member,$pub);
        
        file_put_contents(Path::Combine($pub,'index.php'),str_replace('%APPNAME%',$project->getAppName(),self::IndexFileContent));
        file_put_contents(Path::Combine($pub,'.htaccess'),str_replace('%APPNAME%',$project->getAppName(),self::_dot_htaccess_File));
        $bundlePath=$project->getBundles();
        if(file_exists($bundlePath)) file_put_contents(Path::Combine($bundlePath,'.htaccess'),self::DenyFromAll);
    }
    private static function _takeCareOfMember($project,&$sections,$member,$cwdir){
        $meta=$member->getMeta();
        $action=$meta['action'];
        $name=$member->getName();
        $path=$member->getPath();
        $relativePath=$member->getRelativePath();
        $output=Path::Combine($relativePath,$name);
        $sOutput=Path::Combine($cwdir,$name);
        if(isset($meta['conditions']) && !self::_satisfyConditions($member,$sOutput,$meta))return;
        switch($action){
            case'out':
                if($member->isDirectory()){
                    Directory::RecursiveCopy($sOutput,$path);
                    if(isset($meta['meta'])&&array_search('deny',$meta['meta'])!==false)file_put_contents(Path::Combine($sOutput,'.htaccess'),self::DenyFromAll);
                }else
                    copy($path,$output);
                break;
            case'bin':
                self::_addToBundle($sections,$member,$path,$name,$relativePath,$output,$meta);
                break;
            default: throw new \Exception("Invalid project member '$name' action '$action'.");
        }
    }
    private static function _satisfyConditions($member,$path,$meta){
        $conds=$meta['conditions'];
        $result=false;
        foreach($conds as $cond)
            switch($cond){
                case'notexists'     : $result=!file_exists($path);break;
                case'exists'        : $result= file_exists($path);break;
                default: throw new \Exception("Invalid condition '$cond' encountered.");
            }
        return$result;
    }
    private static function _addToBundle(&$sections,$member,$path,$name,$relativePath,$output,$meta){
        $bundle     =$sections['bundle'];
        //$bundleBin  =$sections['bundle.bin'];
        if($member->isDirectory()){
            PharHelper::AddDirectoryToPhar($bundle,     $path,$output);
            //PharHelper::AddDirectoryToPhar($bundleBin,  $path,$name);
        }
        else{
            $bundle->addFile($path,$output);
            //$bundleBin->addFile($path,$name);
        }
    }
    private static function _initBundle(&$sections,$path){
        $dir=dirname($path);
        if(!file_exists($dir)) mkdir($dir);
        $bundle     =new \Phar("$path.phar");
        //$bundleBin  =new \Phar("$path.bin.phar");
        
        //$bundleBin->compressFiles(\Phar::GZ);
        
        if(file_exists("$path.phar.tar.gz")) unlink("$path.phar.tar.gz");
        $bundle->convertToExecutable(\Phar::TAR,\Phar::GZ);
        
        $sections['bundle']     =$bundle;
        //$sections['bundle.bin'] =$bundleBin;
    }
    
    public static function GetProjectFiles(){
        
    }
    public static function GetProjectBundles(){
        
    }
}
?>