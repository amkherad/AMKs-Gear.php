<?php
namespace Resource;

\Module::Load('Negril\IO\Exceptions\FileNotFound');
\Module::Load('Negril\IO\Path');
\Module::Load('Util\Helpers\MimeHelper');

use Negril\IO\Exceptions\FileNotFound   as FileNotFoundException;
use Negril\IO\Path                      as Path;
use Util\Helpers\MimeHelper             as MimeHelper;

class MvcResourceModificationSignature{
    public $DateTime;
    public $ETag;
    
    public function IsModifiedEx($modification,$etag){
        return !(($this->ETag===$etag) && ($this->DateTime===$modification));
    }
    public function IsModified(MvcResourceModificationSignature$sig){
        return !(($this->ETag===$sig->ETag) && ($this->DateTime===$sig->DateTime));
    }
}

class MvcResourceHandler{
    private$root,$resource,$file;
    public function __construct($res,$root=null){
        $this->resource=$res;
        $this->root=$root;
        $this->file=isset($root)?Path::Combine($root,$res):$res;
        
        if(!file_exists($this->file))throw new FileNotFoundException($this->file);
    }
    
    public function getModificationSignature(){
        $sig=new MvcResourceModificationSignature();
        
        $sig->ETag='"'.md5($this->file).':AMK"';
        $sig->DateTime=filemtime($this->file);
        
        return$sig;
    }
    public static function getModificationSignatureFromHeaders(&$Headers){
        $sig=new MvcResourceModificationSignature();
        
        $sig->ETag      =isset($_SERVER['HTTP_IF_NONE_MATCH'])?trim($_SERVER['HTTP_IF_NONE_MATCH']):false;
        $sig->DateTime  =isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])?strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']):false;
        
        return$sig;
    }
    
    public function getMimeType(){
        return MimeHelper::GetMimeTypeFromExtension($this->file);
    }
    public function readContent(&$mimeType=null){
        if(!file_exists($this->file))throw new FileNotFoundException($this->file);
        try{
            $f=fopen($this->file,"r");
            
            $mimeType=MimeHelper::GetMimeTypeFromExtension($this->file);
            
            $buffer=fread($f,filesize($this->file));
        }finally{
            fclose($f);
        }
        return$buffer;
    }
    public function returnNotModified($sig,$exitRequest=false){
        $sig=isset($sig)?$sig:$this->getModificationSignature();
        \HttpContext::Current()->Response->SetHeader("ETag: $sig->ETag",304);
        
        if($exitRequest)
            \HttpContext::Current()->End();
    }
    public function returnContent($exitRequest=false){
        $context=\HttpContext::Current();
        $r=$context->Response;
        
        $content=$this->readContent($mime);
        
        $r->SetContentType($mime);
        $sig=$this->getModificationSignature();
        $r->SetHeader("ETag: $sig->ETag");
        $r->SetHeader('Last-Modified: '.gmdate("D, d M Y H:i:s",$sig->DateTime).' GMT');
        $r->Write($content);
        
        if($exitRequest)
            $context->End();
    }
}
?>