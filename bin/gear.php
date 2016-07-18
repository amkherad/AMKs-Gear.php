<?php

define('Gear_IsPackaged', true);


define("Gear_Default_ConfigPath",                   "config.ini");



class AppContext implements IContext
{
    private
        $route,
        $config,
        $request,
        $response;

    public function __construct(
        $route,
        $config,
        $request,
        $response)
    {
        $this->route = $route;
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
    }

    public function GetRoute()
    {
        return $this->route;
    }

    function GetConfig()
    {
        return $this->config;
    }

    function GetRequest()
    {
        return $this->request;
    }

    function GetResponse()
    {
        return $this->response;
    }
}


class AppEngine
{
    const Mvc = "mvc";

    private $context;
    private $configuration;

    private function __construct($context, $config)
    {
        $this->context = $context;
        $this->configuration = $config;
    }

    public function Start($engine)
    {

    }


    public static function Create($configPath = null, $type = 0)
    {
        if(is_null($configPath))
            $configPath = Gear_Default_ConfigPath;
        $config = Configuration::FromFile($configPath, $type);

        $route = null;
        $request = null;
        $response = null;

        return new self(
            new AppContext(
                $route,
                $config,
                $request,
                $response
            ),
            $config);
    }
}


class Bundle
{
    static $locator;

    public static function setLocator($locator)
    {
        self::$locator = $locator;
    }

    public static function Pal($module)
    {
        if (defined('Gear_IsPackaged')) return null;

        //TODO: improve algorithm.

        $phpVersion = phpversion();

        return require_once(dirname(__FILE__) . "\\gear\\arch\\pal\\general\\$module.php");
    }

    public static function Arch($module)
    {
        if (defined('Gear_IsPackaged')) return;
        //TODO: improve algorithm.

    }
}



class Configuration
{
    private $c;

    private function __construct($configFile, $type)
    {
        $bundle = Bundle::Pal('file\IniFile');
        print_r($bundle);
        echo phpversion ();
    }

    public function getSection($section)
    {
        return isset($this->c[$section])
            ? $this->c[$section]
            : null;
    }

    public static function FromFile($path, $type = 0)
    {
        return $type == 0
            ? self::FromIniFile($path)
            : self::FromXmlFile($path);
    }

    public static function FromIniFile($path)
    {
        return new self($path, "ini");
    }

    public static function FromXmlFile($path)
    {
        return new self($path, "xml");
    }
}


class Controller extends InspectableClass
{

}




class FsModuleLocator implements IModuleLocator
{
    function Exists($module, $descriptor, $context)
    {
        // TODO: Implement Exists() method.
    }

    function GetAbsolutePath($module, $descriptor, $context)
    {
        // TODO: Implement GetAbsolutePath() method.
    }

    function Add($module, $descriptor, $context)
    {
        // TODO: Implement Include() method.
    }
}



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


interface IActionResult
{
    function Execute(IContext $context);
}


interface IContext
{
    function GetRoute();
    function GetConfig();
    function GetRequest();
    function GetResponse();
}




interface IModuleLocator
{
    function Exists($module, $descriptor, $context);
    function GetAbsolutePath($module, $descriptor, $context);
    function Add($module, $descriptor, $context);
}


interface IRouteService
{

}


class IniFileHelper
{
    public static function ParseIniFile($path, $processSections)
    {
        return parse_ini_file($path, $processSections);
    }
}


class InspectableClass
{
    public function GetProperty($n)
    {
        throw new MvcInvalidOperationException("Property '$n' not found.");
    }
    public final function __get($n)
    {
        return property_exists($this, $n)
            ? $this->$n
            : $this->GetProperty($n);
    }
    public function __isset($n)
    {
        $result = property_exists($this,$n)
            ? $this->$n
            : $this->GetProperty($n);
        return $result == $this
            ? true
            : isset($result);
    }
}


