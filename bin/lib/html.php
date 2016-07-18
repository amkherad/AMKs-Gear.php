<?php
//$MVC_LICENSE$
//$CODESECTION_BEGIN$
class HtmlHelperInjection implements IHelper{
	public function Hidden($name=null,$value=null,$attrs=null){$h=new HiddenBuilder($attrs);$h->Attrs(array('name'=>$name,'value'=>$value));return$h;}
	public function TextBox($attrs=null){return new TextBoxBuilder($attrs);}
	public function Label($attrs=null){return new LabelBuilder($attrs);}
	public function ActionLink($url=null,$text=null,$attrs=null){$a=new ActionLinkBuilder($attrs);if(isset($url))$a->Attribute('href',$url);if(isset($text))$a->Text($text);return$a;}
    public function Display($text=null,$attrs=null){$d=new DisplayBuilder($attrs);if(isset($text))$d->Text($text);return$d;}

    public function BeginForm($name=null,$action=null,$method='GET',$htmlAttrs=null,$useLowerCaseAttrs=true){
        $attrs='';
        if(isset($name))$attrs.="name=\"$name\"";
        if(isset($action))$attrs.=" action=\"$action\"";
        if(isset($method))$attrs.=" method=\"$method\"";
        $html="<form$attrs".HtmlElement::SerializeCustomeAttributes($htmlAttrs,$useLowerCaseAttrs).'>';
        $controller=HttpContext::Current()->Controller;
        $html.='<input type="hidden" name="controller" value="'.$controller->Name.'"/>';
        $html.='<input type="hidden" name="action" value="'.$controller->Action.'"/>';
        return$html;
    }
    public function EndForm(){return'</form>';}
}$Html->RegisterHelper(new HtmlHelperInjection());

abstract class HtmlElement implements IHtmlOutput{
    protected$attrs=array();
    public$UseLowerCaseAttributes=true;
    public function __construct($attrs=null){$this->attrs=$attrs;}
    public function __toString(){
        $hs=new HtmlStream();
        $this->RenderToStream($hs,$this);
        return strval($hs->GetBuffer());
    }
    public abstract function RenderToStream(Stream $s,$context);
    
    public function __call($name,$args){
        $this->attrs[($this->UseLowerCaseAttributes?strtolower($name):$name)]=Utils::stringify($args);
        return$this;
    }
    public function Attribute($key,$value){$this->attrs[($this->UseLowerCaseAttributes?strtolower($key):$key)]=$value;return$this;}
    public function Attrs($arr){
        if(!is_array($arr))throw new MvcInvalidOperationException("Only arrays accepted in HtmlElement::Attrs().");
        foreach($arr as $key=>$value)
            $this->attrs[($this->UseLowerCaseAttributes?strtolower($key):$key)]=$value;
        return$this;
    }
    public function GetCustomeAttributes(){
        return self::SerializeCustomeAttributes($this->attrs,$this->UseLowerCaseAttributes);
    }
    public static function SerializeCustomeAttributes($attrs,$useLowerCaseAttrs=true){
        $ret='';
        if(is_array($attrs)){
            foreach($attrs as $key=>$value)
                if(isset($key)&&isset($value))$ret.=' '.($useLowerCaseAttrs?strtolower($key):$key)."=\"$value\"";
            $ret.=' ';
        }
        return$ret;
    }
    
    public function PreferLowerCaseAttributes($val=true){$this->UseLowerCaseAttributes=$val;return$this;}
}
//================================================================
//===================Generic Element Builders=====================
//================================================================
abstract class GenericElement extends HtmlElement{
    public function Name($name){$this->attrs['name']=$name;return$this;}
    public function CssClass($classStr){$this->attrs['class']=isset($this->attrs['class'])?$this->attrs['class']." $classStr":$classStr;return$this;}
    public function Disabled($disabled=true){$this->attrs['disabled']=$disabled?'disabled':null;return$this;}
}
//================================================================
class HiddenBuilder extends GenericElement{
    public function RenderToStream(Stream $s,$context){
        $attrs=$this->GetCustomeAttributes();
        $s->Write("<input type=\"hidden\"$attrs></input>");
    }
    public function Value($value){$this->attrs['value']=$value;return$this;}
}
class TextBoxBuilder extends GenericElement{
    var$multiline,$val,$type;
    public function RenderToStream(Stream $s,$context){
        $t=isset($this->type)?$this->type:'textbox';
        $attrs=$this->GetCustomeAttributes()."type=\"$t\"";
        
        if($this->multiline)
            $s->Write("<textarea $attrs>$this->val</textarea>");
        elseif(isset($this->val))
            $s->Write("<input$attrs value=\"$this->val\"/>");
        else
            $s->Write("<input$attrs/>");
    }
    public function Multiline($multiline=true){$this->multiline=((bool)($multiline));return$this;}
    public function Value($value){$this->val=$value;return$this;}
    public function Type($type){$this->attrs['type']=$type;return$this;}
    public function Placeholder($value){$this->attrs['placeholder']=$value;return$this;}
}
class LabelBuilder extends GenericElement{
    var$val,$forE;
    public function RenderToStream(Stream $s,$context){
        $attrs=$this->GetCustomeAttributes();
        $s->Write("<label$attrs>$this->val</label>");
    }
    public function ForElement($element){$this->attrs['for']=$element;return$this;}
    public function Value($value){$this->val=$value;return$this;}
    public function Type($type){$this->type=$type;return$this;}
}
class ActionLinkBuilder extends GenericElement{
    var$val;
    public function RenderToStream(Stream $s,$context){
        $attrs=$this->GetCustomeAttributes();
        $val=isset($this->val)?$this->val:$this->attrs['href'];
        if(!isset($val))$val='';
        $s->Write("<a$attrs>$val</a>");
    }
    public function Href($href){$this->attrs['href']=$href;return$this;}
    public function Text($text){$this->val=$text;return$this;}
    public function Title($type){$this->attrs['title']=$type;return$this;}
}
class DisplayBuilder extends GenericElement{
    var$val;
    public function RenderToStream(Stream $s,$context){
        $attrs=$this->GetCustomeAttributes();
        $s->Write("<span$attrs>$this->val</span>");
    }
    public function Href($href){$this->attrs['href']=$href;return$this;}
    public function Text($text){$this->val=$text;return$this;}
    public function Title($type){$this->attrs['title']=$type;return$this;}
}
//$CODESECTION_END$
?>