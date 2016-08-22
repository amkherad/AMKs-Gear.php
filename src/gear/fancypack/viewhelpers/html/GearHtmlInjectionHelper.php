<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\viewhelpers\html;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearHtmlInjectionHelper
{
    public function hidden($name = null, $value = null, $attrs = null)
    {
        $h = new HiddenBuilder($attrs);
        $h->attrs(array('name' => $name, 'value' => $value));
        return $h;
    }

    public function TextBox($attrs = null)
    {
        return new TextBoxBuilder($attrs);
    }

    public function Label($attrs = null)
    {
        return new LabelBuilder($attrs);
    }

    public function actionLink($url = null, $text = null, $attrs = null)
    {
        $a = new ActionLinkBuilder($attrs);
        if (isset($url)) $a->Attribute('href', $url);
        if (isset($text)) $a->Text($text);
        return $a;
    }

    public function display($text = null, $attrs = null)
    {
        $d = new DisplayBuilder($attrs);
        if (isset($text)) $d->Text($text);
        return $d;
    }

    public function BeginForm($name = null, $action = null, $method = 'GET', $htmlAttrs = null, $useLowerCaseAttrs = true)
    {
        $attrs = '';
        if (isset($name)) $attrs .= "name=\"$name\"";
        if (isset($action)) $attrs .= " action=\"$action\"";
        if (isset($method)) $attrs .= " method=\"$method\"";
        $html = "<form$attrs" . HtmlElement::SerializeCustomeAttributes($htmlAttrs, $useLowerCaseAttrs) . '>';
        $controller = HttpContext::Current()->Controller;
        $html .= '<input type="hidden" name="controller" value="' . $controller->Name . '"/>';
        $html .= '<input type="hidden" name="action" value="' . $controller->Action . '"/>';
        return $html;
    }

    public function EndForm()
    {
        return '</form>';
    }
}
/*</module>*/
?>