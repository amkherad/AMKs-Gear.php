<?php
/* Part of MVC.php read MVC.php license. */

/*****************************************/
class HtmlSections{
    public static$Sections=array();
    public static$SectionStarted=false;
    public static$CurrentSection;
    
    public static function GetSection($section){
        if(!isset(HtmlSections::$Sections[$section]))return null;
        return HtmlSections::$Sections[$section]['content'];
    }
    public static function RenderSection($section){
        if(!isset(HtmlSections::$Sections[$section]))return;
        echo HtmlSections::$Sections[$section]['content'];
        Mvc::SatisfyFaultCondition('htmlsections',$section);
    }
    public static function BeginSection($section){
        if(isset(HtmlSections::$CurrentSection))throw new MvcInvalidOperationException("Another HtmlSection has already been begun.");
        foreach(HtmlSections::$Sections as $section)
            if($section['id']==$section)throw new MvcInvalidOperationException("HtmlSection '$section' has already been begun.");
        ob_start();
        HtmlSections::$CurrentSection=array('id'=>$section,'started'=>true,'content'=>null);
        //Mvc::RegisterFaultCondition('htmlsections',$section,"Call RenderSection('$section') in your view to solve this problem.");
        Mvc::RegisterFaultCondition('htmlsections','BeginSection','Call EndSection() in your view to solve this problem.');
    }
    public static function EndSection(){
        if(!isset(HtmlSections::$CurrentSection))throw new MvcInvalidOperationException("No HtmlSection has been begun.");
        $id=HtmlSections::$CurrentSection['id'];
        HtmlSections::$Sections[$id]=HtmlSections::$CurrentSection;
        HtmlSections::$CurrentSection=null;
        HtmlSections::$Sections[$id]['content']=ob_get_clean();
        Mvc::SatisfyFaultCondition('htmlsections','BeginSection');
    }
}
function RenderSection($section){return HtmlSections::RenderSection($section);}
function BeginSection($section){return HtmlSections::BeginSection($section);}
function EndSection(){return HtmlSections::EndSection();}
/*****************************************/
?>