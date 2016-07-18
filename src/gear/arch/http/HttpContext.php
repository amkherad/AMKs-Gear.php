<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class HttpContext{
    static$cc;
    
    public$Application,$Route,$Request,$Response,$Cookie,$Session,$Controller,$View;
    
    public$Ending;
    
    public function __construct(){
        $this->Response=new HttpResponseDirectOut($this);
        $this->Request =new HttpRequest($this,$_GET,$_POST,$_FILES,$_SERVER);
    }
    
    public function End(){if(is_callable($this->Ending)){$c=$this->Ending;$c();}exit;}
    
    public static function Current(){return HttpContext::$cc;}
    public static function Initialize(){
        if(HttpContext::$cc!=null)throw new MvcInvalidOperationException('HttpContext already initialized.');
        HttpContext::$cc=new HttpContext();
    }
};
/*</module>*/
?>