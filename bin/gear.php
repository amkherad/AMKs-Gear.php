<?php

define('Gear_IsPackaged', true);


define("Gear_Default_ConfigPath",                   "config.ini");
define("Gear_500InternalServerErrorPageName",       "500.php");



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
    const Mvc = 'mvc';

    private $context;
    private $configuration;

    private function __construct($context, $config)
    {
        $this->context = $context;
        $this->configuration = $config;
    }

    public function Start($engine = null)
    {
        try {
            if ($engine == static::Mvc || is_null($engine))
                return self::_startMvc();

            throw new AppEngineNotFoundException();
        } catch (Exception $ex) {

        }
    }

    private function _startMvc()
    {

    }


    public static function Create($configPath = null, $type = 0)
    {
        try {
            if (is_null($configPath))
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
        } catch (Exception $ex) {
            self::_render500Error($ex);
        }
    }

    static function _render500Error($ex)
    {
        Bundle::Arch(Gear_500InternalServerErrorPageName);

        InternalServerError::Render($ex);
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


use gear\arch\pal\file\IniFile;
use gear\arch\pal\file\IniFileHelper;

class Configuration
{
    private $c;

    private function __construct($configArray, $type)
    {
        $this->c = $configArray;
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
        Bundle::Pal('file\PALIniFileHelper');
        return new self(PALIniFileHelper::ParseIniFile($path, true), "ini");
    }

    public static function FromXmlFile($path)
    {
        return new self('', "xml");
    }
}


class Controller extends InspectableClass
{

}


class ControllerFactory implements IControllerFactory
{

    function CreateController($controllerName, $actionName, $context)
    {
        // TODO: Implement CreateController() method.
    }

    function Exists($controllerName, $actionName, $context)
    {
        // TODO: Implement Exists() method.
    }
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


class FxException extends \Exception implements IMessageException
{
    private $httpStatusCode;
    public function __construct($message, $httpStatusCode = 500, $code = 0)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code);
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
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


interface IControllerFactory
{
    function CreateController($controllerName, $actionName, $context);
    function Exists($controllerName, $actionName, $context);
}


interface IMessageException
{
    function getHttpStatusCode();
}


interface IModuleLocator
{
    function Exists($module, $descriptor, $context);
    function GetAbsolutePath($module, $descriptor, $context);
    function Add($module, $descriptor, $context);
}


interface IMvcContext
{
    function ControllerName();
    function ActionName();
    function GetParams();
}



interface IRouteService
{

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


class InternalServerError
{
    public static function Render($ex, $errorCode = 500)
    {
        if (defined('DEBUG')) {
            self::_log($ex->getMessage() . ' trace:' . Utils::stringify($ex->getTrace()));
        }
        if ($ex instanceof MvcMessageException) {
            echo "<h2>$ex->Title</h2><h3 style=\"color:red;\">{$ex->getMessage()}</h3>";
        } else {
            $errMessage = (defined('DEBUG') && $errorCode == 500) ? 'Internal Server Error!' : $ex->getMessage();
            echo "<center><h1 style=\"color:red\">$errorCode - $errMessage</h1><br>" .
                (defined('DEBUG') ?
                    $ex->getMessage() . '<br>' .
                    $ex->getFile() . ' at line: ' . $ex->getLine() . '<br><br>' .
                    $ex->getTraceAsString()
                    :
                    "Sorry! An internal server error has been occured.<br>Please report to website admin.")
                . '</center>';
        }
    }
}



class PALIniFileHelper
{
    public static function ParseIniFile($path, $processSections)
    {
        return parse_ini_file($path, $processSections);
    }
}



class AppEngineNotFoundException extends FxException
{
    public function __construct()
    {
        parent::__construct("Specified app engine not found.", 500);
    }
}

