<?php

define('Gear_IsPackaged', true);

/* Modules: */


/* Gear default values. */
define('Gear_Default_ConfigPath',                   'config.ini');
define('Gear_500InternalServerErrorPageName',       '500.php');

define('Gear_IniSection_AppEngine',                 'AppEngine');
define('Gear_IniSection_Router',                    'Router');
define('Gear_IniSection_Controller',                'Controller');
define('Gear_IniSection_ActionResolver',            'ActionResolver');
define('Gear_IniSection_View',                      'View');
define('Gear_IniSection_Binder',                    'Binder');

define('Gear_IniKey_Loggers',                       'Loggers');
define('Gear_IniKey_Factory',                       'Factory');
define('Gear_IniKey_Dependencies',                  'Dependencies');
define('Gear_IniKey_PreferredActionPattern',        'PreferredActionPattern');
define('Gear_IniKey_JsonResultAllowGet',            'JsonResultAllowGet');

define('Gear_IniPlaceHolder_Action',                '[action]');
define('Gear_IniPlaceHolder_HttpMethod',            '[http_method]');

define('Gear_DefaultRouterFactory',                 'DefaultRouteFactory');
define('Gear_DefaultControllerFactory',             'DefaultControllerFactory');
define('Gear_DefaultActionResolverFactory',         'DefaultActionResolverFactory');
define('Gear_DefaultModelBinderFactory',            'DefaultModelBinderFactory');

define('Gear_DefaultPreferredActionPattern',        '[action]__[http_method]');



class ActionPartialViewResult
{

}



abstract class ActionResultBase implements IActionResult
{
    private $innerResult;

    public function getInnerResult()
    {
        return $this->innerResult;
    }


    public abstract function executeResult($context, $request, $response);
}


class ActionViewResult
{

}



class AppContext implements IContext
{
    private
        $route,
        $config,
        $request,
        $response,
        $binderFactory,
        $binder;

    public function __construct(
        $route,
        $config,
        $request,
        $response,
        $binderFactory)
    {
        $this->route = $route;
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
        $this->binderFactory = $binderFactory;

        $this->binder = $binderFactory->createEngine($this);
    }

    public function getRoute()
    {
        return $this->route;
    }

    function getConfig()
    {
        return $this->config;
    }

    function getRequest()
    {
        return $this->request;
    }

    function getResponse()
    {
        return $this->response;
    }

    function getBinder()
    {
        return $this->binder;
    }
}



class AppEngine
{
    const Mvc = 'mvc';

    private $context;
    private $configuration;
    private $controllerFactory;
    private $actionResolverFactory;

    private function __construct(
        $context,
        $config,
        $routeFactory,
        $controllerFactory,
        $actionResolverFactory)
    {
        $this->context = $context;
        $this->configuration = $config;
        $this->controllerFactory = $controllerFactory;
        $this->actionResolverFactory = $actionResolverFactory;
    }

    public function start($engine = null)
    {
        try {
            if ($engine == static::Mvc || is_null($engine))
                return self::_startMvc();

            throw new AppEngineNotFoundException();
        } catch (Exception $ex) {
            self::_render500Error($ex);
        }
    }

    public static function getFactory(Configuration $config, $engine, $defaultFactory)
    {
        $factoryClass = $config->getValue(Gear_IniKey_Factory, $engine, $defaultFactory);

        $factory = new $factoryClass();
        if ($factory == null)
            throw new InvalidOperationException();

        return $factory;
    }

    public static function resolveDependencies($context)
    {
        $config = $context->getConfig();
        $dependencies = $config->getValue(Gear_IniKey_Dependencies, Gear_IniSection_AppEngine);

        if (isset($dependencies)) {
            $modules = [];
            $directories = [];
            $dependents = explode(',', $dependencies);

            foreach ($dependents as $depend) {

                if ($depend == null || $depend == '') continue;
                $depend = trim($depend);
                if (substr($depend, strlen($depend) - 1) == '*') {
                    $directories[] = substr($depend, 0, strlen($depend) - 1);
                } else {
                    $modules[] = $depend;
                }
            }

            foreach ($directories as $dir) {
                if ($depend == null || $depend == '') continue;
                Bundle::resolveAllUserModuleFromDirectory($dir, true, true);
            }

            Bundle::resolveAllUserModules($modules, true, true);
        }
    }

    private function _startMvc()
    {
        $controller = $this->controllerFactory->createEngine(
            $this->context
        );

        $actionResolver = $this->actionResolverFactory->createEngine($this->context);

        $context = $this->context;
        $route = $context->getRoute();
        $request = $context->getRequest();
        $mvcContext = $route->getMvcContext();

        $actionName = $mvcContext->getActionName();

        $actionResolver->invokeAction(
            $controller,
            $context,
            $mvcContext,
            $request,
            $actionName);
    }

    public static function create($configPath = null, $type = 0)
    {
        try {
            if (is_null($configPath))
                $configPath = Gear_Default_ConfigPath;
            $config = Configuration::FromFile($configPath, $type);

            $routeFactory = self::getFactory($config, Gear_IniSection_Router, Gear_DefaultRouterFactory);
            $route = $routeFactory->createEngine(null);
            $request = new HttpRequest($route);
            $response = new HttpResponse();
            $binderFactory = self::getFactory($config, Gear_IniSection_Binder, Gear_DefaultModelBinderFactory);

            $context = new AppContext(
                $route,
                $config,
                $request,
                $response,
                $binderFactory
            );

            self::resolveDependencies($context);

            $loggers = $config->getValue(Gear_IniKey_Loggers, Gear_IniSection_AppEngine);
            if ($loggers != null) {
                $lgs = explode(',', $loggers);
                foreach ($lgs as $logger) {
                    $logger = trim($logger);
                    $loggerInstance = new $logger();
                    Logger::registerLogger($loggerInstance);
                }
            }

            $controllerFactory = self::getFactory($config, Gear_IniSection_Controller, Gear_DefaultControllerFactory);
            $actionResolverFactory = self::getFactory($config, Gear_IniSection_ActionResolver, Gear_DefaultActionResolverFactory);

            $result = new self(
                $context,
                $config,
                $routeFactory,
                $controllerFactory,
                $actionResolverFactory);

            return $result;
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
    static $userRootDirectory;

    public static function setLocator($locator)
    {
        self::$locator = $locator;
    }

    public static function fallback($module, $require = true, $once = true)
    {

    }

    public static function resolveUserModule($module, $require = true, $once = true)
    {
        $root = self::$userRootDirectory;
        $path = "$root\\$module";

        if ($require) {
            return $once
                ? require_once($path)
                : require($path);
        } else {
            return $once
                ? include_once($path)
                : include($path);
        }
    }

    public static function resolveAllUserModules($modules, $require = true, $once = true)
    {
        if (count($modules) == 0) return;
        $root = self::$userRootDirectory;

        foreach ($modules as $module) {
            $path = "$root\\$module.php";
            if ($require) {
                $result = $once
                    ? require_once($path)
                    : require($path);
            } else {
                $result = $once
                    ? include_once($path)
                    : include($path);
            }
        }
    }

    public static function resolveAllUserModuleFromDirectory($path, $require = true, $once = true)
    {
        $root = self::$userRootDirectory;
        $dI = new RecursiveDirectoryIterator("$root\\$path");
        if ($require) {
            if ($once) {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    require_once($file->getPathname());
                }
            } else {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    require($file->getPathname());
                }
            }
        } else {
            if ($once) {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    include_once($file->getPathname());
                }
            } else {
                foreach (new RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if($fileName == '.' || $fileName == '..') continue;
                    include($file->getPathname());
                }
            }
        }
    }

    public static function Pal($module)
    {
        if (defined('Gear_IsPackaged')) return null;

        //TODO: improve algorithm.

        $phpVersion = phpversion();

        return require_once(__DIR__ . "\\gear\\arch\\pal\\general\\$module.php");
    }

    public static function Arch($module)
    {
        if (defined('Gear_IsPackaged')) return;
        //TODO: improve algorithm.

    }

    public static function setRootDirectory($userRootDirectory)
    {
        self::$userRootDirectory = $userRootDirectory;
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

    public function getValue($value, $section = null, $defaultValue = null)
    {
        if (isset($section)) {
            $result = isset($this->c[$section][$value])
                ? $this->c[$section][$value]
                : $defaultValue;
        } else {
            $result = isset($this->c[$value])
                ? $this->c[$value]
                : $defaultValue;
        }
        return $result == null
            ? $defaultValue
            : $result;
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



class ContentResult
{

}




class Controller extends InspectableClass
{
    public function beginExecute()
    {
    }

    public function Json($mixed, $allowGet = false)
    {
        return new JsonResult($mixed, $allowGet);
    }
}



class DefaultActionResolverFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultActionResolver();
    }
}


class DefaultControllerFactory implements IEngineFactory
{
    function createEngine($context)
    {
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();

        $controllerName = $mvcContext->getControllerName();

        if(substr($controllerName, strlen($controllerName) - 10) != 'Controller')
            $controllerName .= 'Controller';
        Bundle::resolveUserModule("controllers\\".$controllerName.'.php');
        return new $controllerName();
    }
}


class DefaultModelBinder implements IModelBinder
{
    public function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext)
    {
        $constructor = $modelDescriptor->getConstructor();
        if (isset($constructor)) throw new MvcInvalidOperationException("ViewModel has a implemented constructor method.");
        $instance = $modelDescriptor->newInstance();
        if ($instance == null) throw new InvalidOperationException('Argument $instance is null.');

        _bind($instance);

        return $instance;
    }

    function _bind($context, $instance, $source)
    {
        if (!isset($source)) {
            $source =  $context->getRequest()->getCurrentMethodValues();
        }
        $vars = get_class_vars(get_class($instance));
        foreach ($vars as $k => $v) {
            $result = null;
            if (Helpers::TryGetArrayElementByNameCaseInSensetive($source, $k, $result))
                $instance->$k = $result;
        }
    }
}



class DefaultModelBinderFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultModelBinder();
    }
}


class DefaultRouteFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultRouter();
    }
}



class DefaultRouter implements IRouteService
{
    function getMvcContext()
    {
        return new RouteMvcContext();
    }
}


class EndResponseResult
{

}



class ErrorResult extends ActionResultBase
{
    private $error;
    public function __construct($error)
    {
        $this->error = $error;
    }

    public function executeResult($context, $request, $response)
    {
        throw new Exception($this->error);
    }
}


class ExecuteActionResult
{

}



class FileResult
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


class HeaderResult
{

}



class Helpers
{
    public static function TryGetArrayElementByNameCaseInSensetive(array&$arr, $key, &$result)
    {
        $key = strtolower($key);
        foreach ($arr as $k => $val)
            if (strtolower($k) == $key) {
                $result = $val;
                return true;
            }
        return false;
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


class HttpRequest implements IHttpRequest
{
    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getValue($name)
    {
        return $_REQUEST[$name];
    }

    function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    function accepts()
    {

    }

    function getAllValues()
    {
        return $_REQUEST;
    }

    public function &getCurrentMethodValues()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        switch ($requestMethod) {
            case'GET':
                return $_GET;
            //case'POST':return$this->POST;
            default:
                return $_POST;
        }
    }
}




class HttpResponse implements IHttpResponse
{
    public function write($mixed)
    {
        echo Serializer::stringify($mixed);
    }
    public function serializeWrite($object, $request)
    {

    }
}



interface IActionResolver
{
    function invokeAction($controller,
                          $context,
                          $mvcContext,
                          $request,
                          $actionName);
}


interface IActionResult
{
    function executeResult($context, $request, $response);
    function getInnerResult();
}


interface IContext
{
    function getRoute();
    function getConfig();
    function getRequest();
    function getResponse();
    function getBinder();
}


interface IEngineFactory
{
    function createEngine($context);
}


interface IHttpRequest
{
    function getValue($name);
    function getMethod();
    function accepts();
    function getAllValues();
    function &getCurrentMethodValues();
}


interface IHttpResponse
{
    function write($mixed);
    function serializeWrite($object, $request);
}


interface IInnerActionResult extends IActionResult
{

}


interface ILogger
{
    function write($mixed, $category = null);
}


interface IMessageException
{
    function getHttpStatusCode();
}


interface IModelBinder
{
    function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext);
}



interface IModuleLocator
{
    function Exists($module, $descriptor, $context);
    function GetAbsolutePath($module, $descriptor, $context);
    function Add($module, $descriptor, $context);
}


interface IMvcContext
{
    function getAreaName();
    function getControllerName();
    function getActionName();
    function getParams();
}



interface IRouteService
{
    function getMvcContext();
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
            Logger::write($ex->getMessage() . ' trace:' . Utils::stringify($ex->getTrace()));
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



class InvalidOperationException extends \Exception implements IMessageException
{
    public function __construct($message, $code, Exception $previous)
    {
        parent::__construct($message == null
            ? 'Invalid operation exception.'
            : $message, $code, $previous);
    }

    function getHttpStatusCode()
    {
        return 500;
    }
}


class JsonResult extends ActionResultBase
{
    private
        $content,
        $allowGet;

    public function __construct($content, $allowGet)
    {
        $this->content = $content;
        $this->allowGet = $allowGet;
    }

    public function executeResult($context, $request, $response)
    {
        $method = $request->getMethod();
        $allowGet = $context->getConfig()->getValue(Gear_IniKey_JsonResultAllowGet, Gear_IniSection_ActionResolver, false);
        if ($method == 'GET' && !($this->allowGet || $allowGet)) {
            return new ErrorResult("Action is not configured to serve data as GET http method.");
        }

        $response->write(json_encode($this->content));
    }
}



class Logger
{
    private static $loggers = [];

    public static function write($mixed, $category = null)
    {
        foreach (self::$loggers as $logger) {
            $logger->write($mixed, $category);
        }
    }

    public static function registerLogger($logger)
    {
        self::$loggers[] = $logger;
    }
}


class ODataResult
{

}



class PALIniFileHelper
{
    public static function ParseIniFile($path, $processSections)
    {
        return parse_ini_file($path, $processSections);
    }
}


class PartialViewResult
{

}



class RedirectResult
{

}



class RouteMvcContext implements IMvcContext
{
    function getAreaName()
    {
        return '';
    }

    function getControllerName()
    {
        return 'home';
    }

    function getActionName()
    {
        return 'index';
    }

    function getParams()
    {
        return '';
    }
}



class Serializer
{
    public static function stringify($mixed)
    {
        $result = '';
        if (is_object($mixed)) $result .= get_class($mixed);
        elseif (is_array($mixed)) foreach ($mixed as $element) $result .= self::stringify($element);
        else $result .= strval($mixed);
        return $result;
    }

    public static function json($mixed, $config = null)
    {

    }

    public static function xml($mixed, $config = null)
    {

    }
}



class StatusCodeResult
{

}



class ViewResult extends ActionResultBase
{
    public function executeResult($context, $request, $response)
    {
        $execResult=$this->controller->View->RenderView($this->controllerName,$this->action,$this->model);
        $result=array();
        if(is_array($execResult))
            foreach($execResult as $r)
                if($r instanceof ActionResult)
                    $result[]=$r;
        if(sizeof($result)>0) return new BatchActionResult($result);
        return true;
    }
}


class AppEngineNotFoundException extends FxException
{
    public function __construct()
    {
        parent::__construct("Specified app engine not found.", 500);
    }
}


class DefaultActionResolver implements IActionResolver
{
    public function invokeAction(
        $controller,
        $context,
        $mvcContext,
        $request,
        $actionName)
    {
        $config = $context->getConfig();
        $method = $request->getMethod();

        $preferedAction = $config->getValue(Gear_IniKey_PreferredActionPattern, Gear_IniSection_Controller, Gear_DefaultPreferredActionPattern);
        $preferedAction = str_replace(Gear_IniPlaceHolder_Action, $actionName, $preferedAction);
        $preferedAction = str_replace(Gear_IniPlaceHolder_HttpMethod, $method, $preferedAction);

        if (method_exists($controller, $preferedAction)) {
            $actionName = $preferedAction;
        }

        $suppliedArgumentss = array();

        $controllerReflection = new ReflectionClass($controller);
        $actionReflection = $controllerReflection->getMethod($actionName);
        $actionParameters = $actionReflection->getParameters();

        $controller->beginExecute();

        $result = self::_execAction(
            $context,
            $mvcContext,
            $controller,
            $controllerReflection,
            $actionReflection,
            $actionName,
            $suppliedArgumentss,
            $actionParameters);

        self::_executeActionResult(
            $context,
            $request,
            $context->getResponse(),
            $result);
    }

    public static function _execAction(
        $context,
        $mvcContext,
        $controller,
        $controllerReflection,
        $actionReflection,
        $actionName,
        $args,
        $actionParameters)
    {
        $binder = $context->getBinder();
        if (sizeof($actionParameters) == 0) {
            if (!isset($args))
                $result = $controller->$actionName();
            else
                $result = call_user_func_array([$controller, $actionName], $args);
        } else {
            if (!isset($args)) $args = $context->getAllValues();
            $actionArgs = array();
            foreach ($actionParameters as $p) {
                if (Helpers::TryGetArrayElementByNameCaseInSensetive($args, $p->getName(), $value))
                    $actionArgs[] = $value;
                else {
                    try {
                        $class = $p->getClass();
                    } catch (Exception$ex) {
                        $class = null;
                    }
                    if (isset($class)) {
                        $actionArgs[] = $binder->getModelFromContext(
                            $class,
                            $context,
                            $controller,
                            $mvcContext
                        );
                    } else {
                        throw new MvcInvalidOperationException("Action '$actionName' argument uses an undefined class type.");
                    }
                }
            }
            $actionArgs = array_merge($actionArgs, $args);
            $result = call_user_func_array([$controller, $actionName], $actionArgs);
        }
        return $result;
    }

    private static function _executeActionResult($context, $request, $response, $result)
    {
        while ($result instanceof IActionResult) {
            $inner = $result->getInnerResult();
            $result = $result->executeResult($context, $request, $response);
            if ($inner instanceof IActionResult) {
                if (!($inner instanceof IInnerActionResult)) {
                    throw new InvalidOperationException('InnerResult must be an instance of IInnerActionResult.');
                }
                self::_executeActionResult($context, $inner);
            }
        }
    }
}


class EmptyResult extends ActionResultBase implements IInnerActionResult
{
    public function executeResult($context, $request, $response)
    {
        return null;
    }
}



/* Generals: */


Bundle::setRootDirectory(getcwd());



