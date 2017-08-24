<?php
namespace Deployment\AMK;

\Module::Load('Negril\IO\Path');
\Module::Load('Negril\IO\Directory');
\Module::Load('Negril\IO\Exceptions\FileNotFound');
//\Module::Load('Debug');

use Negril\IO\Path                      as Path;
use Negril\IO\Exceptions\FileNotFound   as FileNotFoundException;

class Project{
    protected$app,$appName;
    protected$map,$publish,$bundles,$bundle;
    protected$meta,$files;
    protected$projectFile,$mvcPath;
    
    public function getApp(){return$this->app;}
    public function getAppName(){return$this->appName;}
    
    public function getMap(){return$this->map;}
    public function getPublish(){return$this->publish;}
    public function getBundles(){return$this->bundles;}
    public function getBundle(){return$this->bundle;}
    
    public function getMeta(){return$this->meta;}
    public function getFiles(){return$this->files;}
    public function getProjectFile(){return$this->projectFile;}
    public function getMvcPath(){return$this->mvcPath;}
    
    public static function FromIniFile($path){
        if(!file_exists($path))throw new FileNotFoundException($path);
        $iniDir=dirname($path);
        $p=parse_ini_file($path,true);
        
        if(!isset($p['App']))throw new \Exception("Invalid project file, section 'App' not found.");
        $app=$p['App'];
        
        if(!isset($p['Files']))throw new \Exception("Invalid project file, section 'Files' not found.");
        $files=$p['Files'];
        
        unset($p['Files']);
        
        return static::FromDictionary($app,$files,$iniDir,$p);
    }
    public static function FromDictionary($app,$files,$path,$meta){
        if(!isset($app['Name']))throw new Exception("Invalid app array.");
        $appName=$app['Name'];
        
        $dir    =isset($files['Path'])      ?str_replace('%PATH%',$path,$files['Path']):$path;
        $publish=isset($files['Publish'])   ?str_replace('%PATH%',$path,$files['Publish']):Path::Combine($path,'publish');
        $bundles =isset($files['Bundles'])  ?str_replace('%PATH%',$path,$files['Bundles']):Path::Combine($path,'/publish/bundles');
        $mvcPath=isset($files['MvcPath'])   ?str_replace('%PATH%',$path,$files['MvcPath']):$path;
        
        $actions=array();
        $conditions=array();
        $metaes=array();
        
        foreach($files['bin'] as $bin)
            $actions[$bin]='bin';
        foreach($files['out'] as $out)
            $actions[$out]='out';
            
        foreach($files['meta'] as $key=>$element)
            $metaes[$element]=$key;
        
        foreach($files['if'] as $key=>$if)
            $conditions[$if]=$key;
        
        $pm=new DirecotryProjectMap($dir);
        
        foreach($conditions as $member=>$cond)
            $pm->AddMemberConditions($member,$cond);
        
        foreach($actions as $member=>$act)
            $pm->AddMemberMetaData($member,'action',$act);
        
        foreach($metaes as $member=>$meta)
            $pm->AddMemberMetaDataArray($member,'meta',$meta);
        
        foreach($files['exclude'] as $ex)
            $pm->AddMemberExclude($ex);
        
        $p=new static();
        $p->app=$app;
        $p->appName=$appName;
        $p->publish=$publish;
        $p->bundles=$bundles;
        $p->bundle=Path::Combine($bundles,$appName);
        $p->map=$pm;
        $p->meta=$meta;
        $p->files=$files;
        $p->projectFile=$path;
        $p->mvcPath=$mvcPath;
        return$p;
    }
}
abstract class ProjectMember{
    protected$name,$path,$meta,$relativePath;
    
    public function getName(){return$this->name;}
    public function getPath(){return$this->path;}
    public function getMeta(){return$this->meta;}
    public function getRelativePath(){return$this->relativePath;}

    public abstract function isDirectory();
    public abstract function isFile();
}
class ProjectDirectory extends ProjectMember{
    protected$children=array();
    public function __construct(){
        
    }
    
    public static function FromDictionary($dict){
        $dir=new static();
        $dir->name=$dict['name'];
        $dir->path=$dict['path'];
        $dir->meta=$dict['meta'];
        $dir->relativePath=$dict['base'];
        $dir->children=$dict['children'];
        return$dir;
    }
    
    public function getChildren(){
        $ret=array();
        foreach($this->children as $child){
            $ret[]=DirecotryProjectMap::__GetMemberFromInfo($child);
        }
        return$ret;
    }
    
    public function isDirectory(){return true;}
    public function isFile(){return false;}
}
class ProjectFile extends ProjectMember{
    var$splFileInfo;
    public function __construct(){//\SplFileInfo$fileInfo
        //$this->splFileInfo=$fileInfo;
    }
    
    public static function FromDictionary($dict){
        $file=new static();
        $file->name=$dict['name'];
        $file->path=$dict['path'];
        $file->meta=$dict['meta'];
        $file->relativePath=$dict['base'];
        return$file;
    }
    
    public function isDirectory(){return false;}
    public function isFile(){return true;}
    
    public function getSplFileInfo(){return$this->splFileInfo;}
}
abstract class ProjectMap implements \Iterator{
    public abstract function getMetaData();
    
    public abstract function current();
    public abstract function key();
    public abstract function next();
    public abstract function rewind();
    public abstract function valid();
}
class OnMemoryProjectMap extends ProjectMap{
    var$members,$current;
    var$metaData=array();
    public function __construct($initialMembers=null){
        $this->members=isset($initialMember)?array_values($initialMembers):array();
    }
    
    public function getMetaData(){return$this->metaData;}
    
    public function AddMember(ProjectMember$member){if(isset($member))$this->members[]=$member;}
    
    public function current(){ return $this->members[$this->current]; }
    public function key(){ return $this->current; }
    public function next(){ $this->current++; return $this->current < sizeof($this->members); }
    public function rewind(){ $this->current=0; return true; }
    public function valid(){ return $this->current < sizeof($this->members); }
}
class DirecotryProjectMap extends ProjectMap{
    var$path;
    var$includes=array();
    var$excludes=array();
    var$callback;
    var$metaData=array();
    var$memberMetaDatas=array();
    
    var$snap=array(),$isEnd=true,$current;
    
    public function __construct($path){
        $this->path=$path;
    }
    
    public function AddMetaData($key,$value){$this->metaData[$key]=$value;}
    public function SetMetaData($meta){$this->metaData=$meta;}
    public function getMetaData(){return$this->metaData;}
    
    public function AddMemberMetaData($include,$metaName,$metaData){$this->memberMetaDatas[$include][$metaName]=$metaData;}
    public function AddMemberMetaDataArray($include,$metaName,$metaData){$this->memberMetaDatas[$include][$metaName][]=$metaData;}
    public function AddMemberConditions($include,$condition){$this->memberMetaDatas[$include]['conditions'][]=$condition;}
    public function AddMemberInclude($include){$this->includes[]=$include;}
    public function AddMemberExclude($exclude){$this->excludes[]=$exclude;}
    public function AddMemberQueryCallback(Callable$callback){if(!is_callable($callback))throw new Exception('Invalid callback.');$this->callback=$callback;}
    
    private function _expandDir($path,$base,$meta){
        $list=array();
        $fsOs=array_diff(scandir($path),array('.','..'));
        foreach($fsOs as $file){
            $p=Path::Combine($path,$file);
            if(array_search($file,$this->excludes)!==false)continue;
            $meta=isset($this->memberMetaDatas[$file])?$this->memberMetaDatas[$file]:$meta;
            if(is_dir($p))
                $item=array('name'=>$file,'path'=>$p,'base'=>$base,'type'=>'directory','children'=>$this->_expandDir($p,Path::Combine($base,$file),$meta),'meta'=>$meta);
            else
                $item=array('name'=>$file,'path'=>$p,'base'=>$base,'type'=>'file','meta'=>$meta);
            
            $list[]=$item;
        }
        return$list;
    }
    private function _scan(){
        $list=$this->_expandDir($this->path,'',(isset($this->memberMetaDatas['.'])?$this->memberMetaDatas['.']:null));
        $this->snap=$list;
    }
    public static function __GetMemberFromInfo(&$current){
        $type=$current['type'];
        if($type=='directory')
            return ProjectDirectory::FromDictionary($current);
        elseif($type=='file')
            return ProjectFile::FromDictionary($current);
        else throw new \Exception("Invalid file type '$type' on $current.");
    }
    public function current(){return static::__GetMemberFromInfo($this->snap[$this->current]);}
    public function key(){return $this->current;}
    public function next(){$this->current++;return$this->current<sizeof($this->snap);}
    public function rewind(){$this->_scan();$this->current=0;}
    public function valid(){return$this->current<sizeof($this->snap);}
}
?>