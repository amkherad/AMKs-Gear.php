<?php
//$SOURCE_LICENSE$
define('Gear_IsPackaged', true);

/* Modules: */


/* Gear default values. */
define('Gear_Default_ConfigPath',                   'config.ini');
define('Gear_500InternalServerErrorPageName',       '500.php');

define('Gear_Section_AppEngine',                    'AppEngine');
define('Gear_Section_Router',                       'Router');
define('Gear_Section_Controller',                   'Controller');
define('Gear_Section_ActionResolver',               'ActionResolver');
define('Gear_Section_View',                         'View');
define('Gear_Section_Binder',                       'Binder');
define('Gear_Section_Defaults',                     'Defaults');

define('Gear_Key_Engine',                           'Engine');
define('Gear_Key_Loggers',                          'Loggers');
define('Gear_Key_AutoLoading',                      'AutoLoading');
define('Gear_Key_Factory',                          'Factory');
define('Gear_Key_RootPath',                         'RootPath');
define('Gear_Key_Dependencies',                     'Dependencies');
define('Gear_Key_PreferredActionPattern',           'PreferredActionPattern');
define('Gear_Key_JsonResultAllowGet',               'JsonResultAllowGet');
define('Gear_Key_LayoutName',                       'Layout');
define('Gear_Key_DebugMode',                        'DebugMode');

define('Gear_PlaceHolder_Action',                   '[action]');
define('Gear_PlaceHolder_HttpMethod',               '[http_method]');

define('Gear_DefaultRouterFactory',                 'DefaultRouteFactory');
define('Gear_DefaultControllerFactory',             'DefaultControllerFactory');
define('Gear_DefaultActionResolverFactory',         'DefaultActionResolverFactory');
define('Gear_DefaultModelBinderFactory',            'DefaultModelBinderFactory');
define('Gear_DefaultViewEngineFactory',             'DefaultViewEngineFactory');

define('Gear_DefaultControllersRootPath',           'controller');
define('Gear_DefaultModelsRootPath',                'model');
define('Gear_DefaultViewsRootPath',                 'views');

define('Gear_DefaultLayoutName',                    '_layout');
define('Gear_DefaultPreferredActionPattern',        '[action]__[http_method]');

define('Gear_ServiceViewEngineFactory',             'ViewEngineFactoryService');
define('Gear_ServiceViewOutputStream',              'ServiceViewOutputStream');



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
        $binder,
        $services;

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
        $this->services = [];

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

    function registerService($serviceName, $service)
    {
        $this->services[$serviceName] = $service;
    }
    function removeService($serviceName)
    {
        unset($this->services[$serviceName]);
    }
    function getService($serviceName)
    {
        return isset($this->services[$serviceName])
            ? $this->services[$serviceName]
            : null;
    }
}




class AppEngine
{
    const Mvc = 'mvc';

    private $context;
    private $configuration;
    private $controllerFactory;
    private $actionResolverFactory;

    private $_startExecutionTime;
    private $_createExecutionTime;

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

    public function getCreateExecutionTime()
    {
        return $this->_createExecutionTime;
    }

    public function getStartExecutionTime()
    {
        return $this->_startExecutionTime;
    }

    public function start($engine = null)
    {
        $result = null;
        try {
            $rStart = microtime(true);

            if(!isset($engine)) {
                $engine = $this->configuration->getValue(Gear_Key_Engine, Gear_Section_AppEngine, 'mvc');
            }

            if ($engine == static::Mvc || is_null($engine))
                $result = self::_startMvc0();
            $this->_startExecutionTime = (microtime(true) - $rStart);

            if ($result == null) {
                throw new AppEngineNotFoundException();
            } else {
                return $result;
            }
        } catch (Exception $ex) {
            self::_render500Error($ex);
        }
    }

    public static function getFactory(Configuration $config, $engine, $defaultFactory)
    {
        $factoryClass = $config->getValue(Gear_Key_Factory, $engine, $defaultFactory);

        $factory = new $factoryClass();
        if ($factory == null)
            throw new InvalidOperationException();

        return $factory;
    }

    public static function resolveDependencies($context)
    {
        $config = $context->getConfig();
        $dependencies = $config->getValue(Gear_Key_Dependencies, Gear_Section_AppEngine);

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

    private function _startMvc0()
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

        return 1;
    }

    public static function create($configPath = null, $type = 0)
    {
        try {
            $rStart = microtime(true);
            if (is_null($configPath))
                $configPath = Gear_Default_ConfigPath;
            $config = Configuration::FromFile($configPath, $type);

            $debugMode = $config->getValue(Gear_Key_DebugMode, Gear_Section_AppEngine, false);
            if (boolval($debugMode) == true && !defined('DEBUG')) {
                define('DEBUG', 1);
            }

            $autoLoadMode = $config->getValue(Gear_Key_AutoLoading, Gear_Section_AppEngine, null);
            if ($autoLoadMode != null) {
                Autoload::register($autoLoadMode);
            }

            $routeFactory = self::getFactory($config, Gear_Section_Router, Gear_DefaultRouterFactory);
            $route = $routeFactory->createEngine(null);
            $request = new HttpRequest($route);
            $response = new HttpResponse();
            $binderFactory = self::getFactory($config, Gear_Section_Binder, Gear_DefaultModelBinderFactory);

            $context = new AppContext(
                $route,
                $config,
                $request,
                $response,
                $binderFactory
            );
            HttpContext::setCurrent($context);

            self::resolveDependencies($context);

            $loggers = $config->getValue(Gear_Key_Loggers, Gear_Section_AppEngine);
            if ($loggers != null) {
                $lgs = explode(',', $loggers);
                foreach ($lgs as $logger) {
                    $logger = trim($logger);
                    $loggerInstance = new $logger();
                    Logger::registerLogger($loggerInstance);
                }
            }

            $controllerFactory = self::getFactory($config, Gear_Section_Controller, Gear_DefaultControllerFactory);
            $actionResolverFactory = self::getFactory($config, Gear_Section_ActionResolver, Gear_DefaultActionResolverFactory);
            $viewEngineFactory = self::getFactory($config, Gear_Section_View, Gear_DefaultViewEngineFactory);

            $context->registerService(Gear_ServiceViewEngineFactory, $viewEngineFactory);

            $result = new self(
                $context,
                $config,
                $routeFactory,
                $controllerFactory,
                $actionResolverFactory);

            $result->_createExecutionTime = (microtime(true) - $rStart);
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




class Autoload
{
    public static function register($type)
    {
        $function = null;
        switch (strtolower($type)) {
            case 'prob':
                spl_autoload_register(function ($className) {
                    self::_probing($className);
                });
                break;
            case 'userprobing':
                spl_autoload_register(function ($className) {
                    self::_userProbing($className);
                });
                break;
            default:
                throw new InvalidOperationException();
        }
    }

    private static function _probing($className)
    {
        Bundle::prob($className);
    }

    private static function _userProbing($className)
    {
        Bundle::resolveUserModule($className, true, true);
    }
}



class BatchActionResult extends ActionResultBase
{
    private
        $actions;

    public function __construct($actions)
    {
        $this->actions = $actions;
    }

    public function executeResult($context, $request, $response)
    {
        foreach ($this->actions as $action) {
            $action->executeResult($context, $request, $response);
        }
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

    public static function prob($module, $require = true, $once = true)
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




abstract class Controller// extends InspectableClass
{
    protected
        $context,
        $mvcContext,
        $route,
        $request,
        $response,
        $binder;

    public
        $layout,
        $viewData,
        $html,
        $url,
        $helper
    ;

    public function __construct($context)
    {
        $this->context = $context;
        $route = $context->getRoute();
        $config = $context->getConfig();
        $this->route = $route;
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->binder = $context->getBinder();
        $this->mvcContext = $route->getMvcContext();

        $this->layout = $config->getValue(Gear_Key_LayoutName, Gear_Section_View, Gear_DefaultLayoutName);

        $this->viewData = new DynamicDictionary(array());
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    public function getBinder()
    {
        return $this->binder;
    }

    public function beginExecute()
    {
    }

    public function checkExecution()
    {
        $this->authorize();
    }

    public function endExecute()
    {
    }

    public function onExceptionOccurred($exception)
    {
        echo 'sexy??';
    }

    public function authorize()
    {

    }

    public function Bind($model)
    {
        if (!isset($model)) {
            return null;
        }

        $this->binder->fillModelFromContext($model, $this->context, $this, $this->mvcContext);

        return $model;
    }

    public function LayoutRendering($layout)
    {
    }


    public function Json($mixed, $allowGet = false)
    {
        return new JsonResult($mixed, $allowGet);
    }

    public function View($model = null, $viewName = null)
    {
        return new ViewResult($this, $viewName, $model);
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
        $areaName = $mvcContext->getAreaName();

        if(substr($controllerName, strlen($controllerName) - 10) != 'Controller')
            $controllerName .= 'Controller';

        $controllerPath = "controllers\\".$controllerName.'.php';
        if(isset($areaName)){
            $controllerPath = "$areaName\\$controllerPath";
        }

        Bundle::resolveUserModule($controllerPath);
        return new $controllerName($context);
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

        self::_bind($context, $instance, null);

        return $instance;
    }

    function fillModelFromContext($instance, $context, $controller, $mvcContext)
    {
        self::_bind($context, $instance, null);
    }

    private static function _bind($context, $instance, $source)
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


class DefaultViewEngineFactory implements IEngineFactory
{
    function createEngine($context)
    {
        return new DefaultViewEngine();
    }
}


class DynamicDictionary implements \ArrayAccess
{
    private $v, $a;//a:true all isset=true else if exists.

    public function __construct($p, $a = false)
    {
        $this->v = $p;
        $this->a = $a;
    }

    public function __set($k, $val)
    {
        $this->v[$k] = $val;
    }

    public function &__get($k)
    {
        return $this->v[$k];
    }

    public function __isset($k)
    {
        return $this->a || isset($this->v[$k]);
    }

    public function __unset($k)
    {
        unset($this->v[$k]);
    }

    public function offsetExists($o)
    {
        return $this->a || isset($this->v[$o]);
    }

    public function &offsetGet($o)
    {
        return $this->v[$o];
    }

    public function offsetSet($o, $val)
    {
        $this->v[$o] = $val;
    }

    public function offsetUnset($o)
    {
        unset($this->v[$o]);
    }

    public function setInnerBuffer(&$v)
    {
        $this->v = $v;
    }
}


class EndResponseResult
{

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



class HttpContext
{
    static
        $currentContext;

    public $Ending;

    public function End()
    {
        if (is_callable($this->Ending)) {
            $c = $this->Ending;
            $c();
        }
        exit;
    }

    public static function current()
    {
        return self::$currentContext;
    }

    public static function setCurrent($context)
    {
        self::$currentContext = $context;
    }
}


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

    function registerService($serviceName, $service);
    function removeService($serviceName);
    function getService($serviceName);
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
    function fillModelFromContext($instance, $context, $controller, $mvcContext);
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



interface IOutputStream
{
    function write($mixed);
    function clear();
}


interface IRouteService
{
    function getMvcContext();
}


interface IViewEngine
{
    function renderView(
        $context,
        $controller,
        $viewName,
        $model
    );
}


class InspectableClass
{
    public function GetProperty($n)
    {
        throw new InvalidOperationException("Property '$n' not found.");
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
    public static function Render($ex, $eCode = null)
    {
        if ($eCode == null) {
            if ($ex instanceof IMessageException) {
                $errorCode = $ex->getHttpStatusCode();
            } else {
                $errorCode = 500;
            }
        } else {
            $errorCode = $eCode;
        }

        if (defined('DEBUG')) {
            Logger::write($ex->getMessage() . ' trace:' . Serializer::stringify($ex->getTrace()));
        }

        http_response_code($errorCode);
        $errMessage = (defined('DEBUG') && $errorCode == 500) ? 'Internal Server Error!' : $ex->getMessage();
        echo "<h1 style=\"color:red\">$errorCode - $errMessage</h1><br>" .
            (defined('DEBUG') ?
                $ex->getMessage() . '<br>' .
                $ex->getFile() . ' at line: ' . $ex->getLine() . '<br><br>' .
                $ex->getTraceAsString()
                :
                "Sorry! An internal server error has been occured.<br>Please report to website admin.")
            ;
    }
}


class InvalidOperationException extends \Exception implements IMessageException
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
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



class Path
{
    private static function _combine($path1, $path2)
    {
        $path1 = strval($path1);
        $path2 = strval($path2);
        $sep = substr($path1, strlen($path1) - 1);
        $retval = ($sep == '/' || $sep == '\\')
            ? $path1 : $path1 . '/';
        $sep = substr($path2, 0, 1);
        $retval .= ($sep == '/' || $sep == '\\')
            ? substr($path2, 1) : $path2;
        return $retval;
    }

    public static function Combine($path1)
    {
        if (is_array($path1)) {
            $retval = '';
            $first = true;
            foreach ($path1 as $path) {
                if ($path == null) continue;
                $retval = strval($first)
                    ? $path : self::_combine($retval, $path);
                $first = false;
            }
            return $retval;
        } else {
            $retval = strval($path1);
            $fv = func_num_args();
            for ($i = 1; $i < $fv; $i++) {
                $retval = self::_combine($retval, func_get_arg($i));
            }
            return $retval;
        }
    }

    public static function GetUseablePath($p)
    {
        $f = substr($p, 0, 1);
        if ($f == '/' || $f == '\\') {
            $p = substr($p, 1);
        }
        return $p;
    }

    public static function GetExtension($p)
    {
        $d = strrpos($p, '.');
        if (!is_bool($d) && $d >= 0) {
            return ($d < strlen($p) - 1 ? substr($p, $d + 1) : '');
        }
        return null;
    }
}


class RedirectResult
{

}



class Route
{

}


class RouteCollection implements \ArrayAccess
{
    const
        Optional = 'optional',
        Params = 'params';

    public $MapedRoutes = array();
    public $IgnoredRoutes = array();

    public function MapRoute($name, $url, $defaults = null, $constraints = null)
    {
        $p = explode('/', $url);
        $size = sizeof($p);
        $min = $size;
        $max = $size;

        $r = array('name' => $name, 'url' => $url);

        $escaped = array();
        foreach ($p as $s) {
            if ($s == null) continue;
            preg_match('/(?:{)(.+)(?:})/', $s, $match);
            $escaped[] = $match[1];
        }
        $r['sections'] = $escaped;

        $r['params'] = null;
        $paramsFound = false;

        $defaultsRemover = array();
        if (is_array($defaults)) foreach ($defaults as $k => &$d) {
            foreach ($escaped as $escapedEl) if ($escapedEl == $k) $min--;
            if (is_array($d)) {
                foreach ($d as $element) {
                    $removeFromDefaults = false;
                    if ($element == self::Optional) {
                        $min--;
                        $removeFromDefaults = true;
                    } elseif ($element == self::Params) {
                        if ($paramsFound) throw new InvalidOperationException();
                        $min--;
                        $max = 9999999;
                        $d['name'] = $k;
                        $r['params'] = $d;
                        $paramsFound = true;
                        $removeFromDefaults = true;
                    }
                    if ($removeFromDefaults && array_search($k, $escaped) === false) {
                        $defaultsRemover[] = $k;
                        continue;
                    }
                }
            }
        }
        if ($min < 0) $min = 0;
        //foreach($defaultsRemover as $dr)unset($defaults[$dr]);

        $r['defaults'] = $defaults;
        $r['constraints'] = $constraints;

        $r['urlMinParts'] = $min;
        $r['urlMaxParts'] = $max;

        $this->MapedRoutes[$name] = $r;;
    }

    public function IgnoreRoute($url, $constraints = null)
    {
        array_push($this->IgnoredRoutes, array('url' => $url, 'constraints' => $constraints));
    }

    private function _doesMatch($arr, $p, $path)
    {
        $count = sizeof($p);
        if ($count >= $arr['urlMinParts'] && $count <= $arr['urlMaxParts']) {
            return true;
        }
        return false;
    }

    public function GetRoute($path)
    {
        $p = array();
        foreach (explode('/', $path) as $e) if ($e != '') $p[] = $e;
        $size = sizeof($p);
        foreach ($this->MapedRoutes as $r) {
            if ($this->_doesMatch($r, $p, $path)) {
                $result = array();
                $rSections = $r['sections'];
                $rDefaults = $r['defaults'];
                $rParams = $r['params'];
                $removes = array();
                for ($i = 0; $i < $size; $i++) {
                    $name = isset($rSections[$i]) ? $rSections[$i] : $rParams['name'];
                    if ($name != $rParams['name'])
                        $result[$name] = isset($p[$i]) ? $p[$i] : $rDefaults[$i];
                    else {
                        $removes[$name] = true;
                        $result/*[$name]*/
                        [] = isset($p[$i]) ? $p[$i] : $rDefaults[$i];
                    }
                }
                foreach ($rDefaults as $k => $def) {
                    if (!isset($result[$k]) && !isset($removes[$k]))
                        $result[$k] = $def;
                }
                return $result;
            }
        }
        return null;
    }

    public function GetVirtualPath($context, $p)
    {//  controller - action - arg1
        $path = implode('/', $p);
        //foreach($this->MapedRoutes as $r){
        //    if($this->_doesMatch($r,$p,$path)){
        //        return Path::Combine(Uri::GetRoot(),$path);
        //    }
        //}
        return Path::Combine(Uri::GetRoot(), $path);
    }

    public function __set($k, $val)
    {
        throw new InvalidOperationException("");
    }

    public function __get($k)
    {
        return null;
    }

    public function __isset($k)
    {
        return false;
    }

    public function __unset($k)
    {
    }

    public function offsetExists($o)
    {
        return false;
    }

    public function offsetGet($o)
    {
        return null;
    }

    public function offsetSet($o, $val)
    {
        throw new InvalidOperationException("");
    }

    public function offsetUnset($o)
    {
    }
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
    private
        $controller,
        $viewName,
        $model;

    public function __construct($controller, $viewName, $model)
    {
        $this->controller = $controller;
        $this->viewName = $viewName;
        $this->model = $model;
    }

    public function executeResult($context, $request, $response)
    {
        $viewEngineFactory = $context->getService(Gear_ServiceViewEngineFactory);

        $viewEngine = $viewEngineFactory->createEngine($context);

        $viewName = $this->viewName;
        if(!isset($viewName)){
            $viewName = $context->getRoute()->getMvcContext()->getActionName();
        }

        return $viewEngine->renderView(
            $context,
            $this->controller,
            $viewName,
            $this->model
        );
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

        $preferedAction = $config->getValue(Gear_Key_PreferredActionPattern, Gear_Section_Controller, Gear_DefaultPreferredActionPattern);
        $preferedAction = str_replace(Gear_PlaceHolder_Action, $actionName, $preferedAction);
        $preferedAction = str_replace(Gear_PlaceHolder_HttpMethod, $method, $preferedAction);

        if (method_exists($controller, $preferedAction)) {
            $actionName = $preferedAction;
        }

        $suppliedArgumentss = array();

        $controllerReflection = new \ReflectionClass($controller);
        $actionReflection = $controllerReflection->getMethod($actionName);
        $actionParameters = $actionReflection->getParameters();

        $controller->beginExecute();

        $controller->checkExecution();

        try {
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

        } catch (\Exception $ex) {
            $controller->onExceptionOccurred($ex);
            throw $ex;
        }

        $controller->endExecute();
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
                        throw new InvalidOperationException("Action '$actionName' argument uses an undefined class type.");
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
        if (!isset($result)) return;
        do {
            if ($result instanceof IActionResult) {
                $inner = $result->getInnerResult();
                $result = $result->executeResult($context, $request, $response);
            } else {
                $inner = null;
                $response->write($result);
            }
            if ($inner instanceof IActionResult) {
                if (!($inner instanceof IInnerActionResult)) {
                    throw new InvalidOperationException('InnerResult must be an instance of IInnerActionResult.');
                }
                self::_executeActionResult($context, $request, $response, $inner);
            }
        } while ($result instanceof IActionResult);
    }
}




class DefaultViewEngine implements IViewEngine
{
    public function renderView(
        $context,
        $controller,
        $viewName,
        $model
    )
    {
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();
        $controllerName = $mvcContext->getControllerName();

        $execResult = self::_renderView(
            0,
            $context,
            $mvcContext,
            $controllerName,
            $controller,
            $viewName,
            $model,
            true);

        $result = array();
        if (is_array($execResult)) {
            foreach ($execResult as $r) {
                if ($r instanceof IActionResult) {
                    $result[] = $r;
                }
            }
        }
        if (sizeof($result) > 0) {
            return new BatchActionResult($result);
        }

        return $execResult;
    }

    private static function _renderView(
        $indent,
        $context,
        $mvcContext,
        $controllerName,
        $controller,
        $viewName,
        $model,
        $useLayout)
    {
        $config = $context->getConfig();
        $viewRoot = $config->getValue(Gear_Key_RootPath, Gear_Section_View, Gear_DefaultViewsRootPath);

        $viewPath = strtolower($viewName);
        $ext = Path::GetExtension($viewPath);
        if ($ext != 'phtml' && $ext != 'php') $viewPath .= '.phtml';
        $viewPath = Path::GetUseablePath(Path::Combine($viewRoot, $controllerName, $viewPath));

        $layout = $useLayout == true ? $controller->layout : null;
        $viewContent = self::_executeView(
            $viewPath,
            $viewName,
            $controller->viewData,
            $controller->html,
            $controller->url,
            $controller->helper,
            $layout,
            $model,
            $result);
        //ActionResult::ExecuteActionResult($context, $result);

        if (isset($layout)) {
            $controller->layout = null;

            $output = $context->getService(Gear_ServiceViewOutputStream);
            if($output == null) {
                $output = new HtmlStream();
            }
            $output->write($viewContent);
            $context->registerService(Gear_ServiceViewOutputStream, $output);

            self::_renderView(
                $indent + 1,
                $context,
                $mvcContext,
                null,
                $controller,
                $layout,
                $model,
                false);

            $output->clear();

        } else {
            $context->getResponse()->write($viewContent);
        }
        return $result;
    }

    private static function _checkFileExists(&$path)
    {
        if (file_exists($path)) {
            return true;
        }

        if (file_exists("$path.phtml")) {
            $path = "$path.phtml";
            return true;
        }
        if (file_exists("$path.php")) {
            $path = "$path.php";
            return true;
        }

        return false;
    }

    private static function _executeView(
        $path,
        $viewName,
        $viewData,
        $html,
        $url,
        $helper,
        &$layout,
        &$model,
        &$result)
    {
        $viewPath = dirname($path) . '/' . $viewName;
        if (!self::_checkFileExists($viewPath)) {
            if (!self::_checkFileExists($viewPath)) {
                $dblCheck = getcwd() . '/' . $viewPath;
                if (!self::_checkFileExists($dblCheck)) {
                    throw new ViewFileNotFoundException($path);
                }
                //$path = $dblCheck;
            }
        }

        global $Layout, $ViewData, $Model, $Html, $Url, $Helper;
        $Model = $model;
        $Layout = $layout;
        $ViewData = $viewData;
        $Html = $html;
        $Url = $url;
        $Helper = $helper;

        $level = ob_get_level();
        ob_start();
        $result = require($viewPath);
        $buffer = '';
        while (ob_get_level() > $level)
            $buffer = ob_get_clean() . $buffer;
        //global $Layout;
        $layout = $Layout;
        return $buffer;
    }
}


class EmptyResult extends ActionResultBase implements IInnerActionResult
{
    public function executeResult($context, $request, $response)
    {
        return null;
    }
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


class HttpStatusCodeException extends FxException
{
    public function __construct($message, $httpStatusCode, $code = 0)
    {
        parent::__construct($message, $httpStatusCode, $code);
    }
}


class HttpUnauthorizedException extends HttpStatusCodeException
{
    public function __construct($message = null)
    {
        parent::__construct($message == null
            ? 'Unauthorized'
            : $message, 403, 0);
    }
}


interface IHttpResponse extends IOutputStream
{
    function write($mixed);
    function serializeWrite($object, $request);

    function writeInnerStream();
}


class InMemoryStream implements IOutputStream
{
    private
        $buffer;

    public function write($mixed)
    {
        if (is_string($mixed)) {
            $this->buffer = $this->buffer . $mixed;
        } else {
            $this->buffer = $this->buffer . Serializer::stringify($mixed);
        }
    }

    public function clear(){
        $this->buffer = '';
    }

    public function &getBuffer(){
        return $this->buffer;
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


class AppEngineNotFoundException extends FxException
{
    public function __construct()
    {
        parent::__construct("Specified app engine not found.", 500);
    }
}


class HtmlStream extends InMemoryStream
{

}


class HttpNotFoundException extends HttpStatusCodeException
{
    public function __construct($message = null)
    {
        parent::__construct($message == null
            ? 'Not found'
            : $message, 404, 0);
    }
}



class HttpResponse implements IHttpResponse
{
    private
        $innerStream;

    public function __construct()
    {
        $this->innerStream = new HtmlStream();
    }

    public function getInnerStream()
    {
        return $this->innerStream;
    }

    public function write($mixed)
    {
        if (is_string($mixed)) {
            echo $mixed;
        } else {
            echo Serializer::stringify($mixed);
        }
    }
    public function clear()
    {

    }

    public function serializeWrite($object, $request)
    {
        echo Serializer::stringify($object);
    }

    public function writeInnerStream()
    {
        $this->write($this->innerStream->getBuffer());
    }
}


class ViewFileNotFoundException extends HttpNotFoundException
{
    public function __construct($action)
    {
        parent::__construct($action == null
            ? "404 - View file not found."
            : "404 - View file '$action' not found.");
    }
}



/* Generals: */


Bundle::setRootDirectory(getcwd());

function RenderBody()
{
    $context = HttpContext::current();
    $output = $context->getService(Gear_ServiceViewOutputStream);
    if($output != null) {
        $context->getResponse()->write($output->getBuffer());
    }
}



