<?php
//Bundle: Gear.ViewHelpers

/* Dependencies: */


/* Modules: */
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
class GearHtmlSections
{
    public static $sections = array();
    public static $sectionStarted = false;
    public static $currentSection;

    public static function getSection($sectionName)
    {
        if (!isset(self::$sections[$sectionName])) return null;
        return self::$sections[$sectionName]['content'];
    }

    public static function renderSection($sectionName)
    {
        if (!isset(self::$sections[$sectionName])) return;
        echo self::$sections[$sectionName]['content'];
    }

    public static function beginSection($sectionName)
    {
        if (isset(self::$currentSection)) throw new GearInvalidOperationException("Another html section has already been started.");
        foreach (self::$sections as $section)
            if ($section['id'] == $sectionName) throw new GearInvalidOperationException("Html section '$sectionName' has already been started.");
        ob_start();
        self::$currentSection = array('id' => $sectionName, 'started' => true, 'content' => null);
    }

    public static function endSection($sectionName = null)
    {
        if (!isset(self::$currentSection)) throw new GearInvalidOperationException("No html section has been started.");
        $id = self::$currentSection['id'];
        self::$sections[$id] = self::$currentSection;
        self::$currentSection = null;
        self::$sections[$id]['content'] = ob_get_clean();
    }
}
class HiddenBuilder
{

}
abstract class HtmlElement
{
    /** @var array|null */
    protected $attrs = [];
    public $useLowerCaseAttributes = true;

    /**
     * HtmlElement constructor.
     * @param array $attrs
     */
    public function __construct($attrs = null)
    {
        if ($attrs != null) {
            $this->attrs = $attrs;
        }
    }

    public function __toString()
    {
        $hs = new GearHtmlStream();
        $this->renderToStream($hs);
        return strval($hs->getBuffer());
    }

    /**
     * @param IGearOutputStream $stream
     * @return mixed
     */
    public abstract function renderToStream($stream);

    public function __call($name, $args)
    {
        $this->attrs[($this->useLowerCaseAttributes ? strtolower($name) : $name)] = GearSerializer::stringify($args);
        return $this;
    }

    public function attribute($key, $value)
    {
        $this->attrs[($this->useLowerCaseAttributes ? strtolower($key) : $key)] = $value;
        return $this;
    }

    public function attributes($arr)
    {
        if (!is_array($arr)) {
            throw new GearInvalidOperationException("Only arrays accepted in HtmlElement::Attrs().");
        }
        foreach ($arr as $key => $value) {
            $this->attrs[($this->useLowerCaseAttributes ? strtolower($key) : $key)] = $value;
        }
        return $this;
    }

    public function getCustomAttributes()
    {
        return self::serializeCustomeAttributes($this->attrs, $this->useLowerCaseAttributes);
    }

    public static function serializeCustomAttributes($attrs, $useLowerCaseAttrs = true)
    {
        $ret = '';
        if (is_array($attrs)) {
            foreach ($attrs as $key => $value) {
                if (isset($key) && isset($value)) {
                    $ret .= ' ' . ($useLowerCaseAttrs ? strtolower($key) : $key) . "=\"$value\"";
                }
            }
            $ret .= ' ';
        }
        return $ret;
    }

    public function preferLowerCaseAttributes($val = true)
    {
        $this->useLowerCaseAttributes = $val;
        return $this;
    }
}


/* Generals: */
GearHtmlHelper::setStaticExtensionMethods([
    'renderSection' => [GearHtmlSections::class, 'renderSection']/*function ($sectionName) {
        GearHtmlSections::renderSection($sectionName);
    }*/,
    'beginSection' => [GearHtmlSections::class, 'beginSection']/*function ($sectionName) {
        GearHtmlSections::beginSection($sectionName);
    }*/,
    'endSection' => [GearHtmlSections::class, 'endSection']/*function ($sectionName = null) {
        GearHtmlSections::endSection($sectionName);
    }*/,


    'renderScript' => function () {
        GearHtmlSections::renderSection('Scripts');
    },
    'beginScript' => function () {
        GearHtmlSections::beginSection('Scripts');
    },
    'endScript' => function () {
        GearHtmlSections::endSection('Scripts');
    },

    'renderStyle' => function () {
        GearHtmlSections::renderSection('Styles');
    },
    'beginStyle' => function () {
        GearHtmlSections::beginSection('Styles');
    },
    'endStyle' => function () {
        GearHtmlSections::endSection('Styles');
    },

    'renderHtml' => function () {
        GearHtmlSections::renderSection('Html');
    },
    'beginHtml' => function () {
        GearHtmlSections::beginSection('Html');
    },
    'endHtml' => function () {
        GearHtmlSections::endSection('Html');
    },

    'antiForgeryToken' => function () {
        return '';
    },
    'validationMessageFor' => function ($name) {
        return '';
    },

    'valueOf' => function ($name) {
        global $Model;
        if ($Model != null ) {
            return $Model->$name;
        }
        return '';
    }
]);

