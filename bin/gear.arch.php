<?php
//Bundle: Gear

/* Dependencies: */


/* Modules: */
/* Gear default values. */
define('Gear_Version',                              '0.0.1');

define('Gear_Default_ConfigPath',                   'config.ini');
define('Gear_500InternalServerErrorPageName',       '500.php');
define('Gear_PoweredResponseHeader',                'X-Powered-Fx: AMK\'s Gear.php/'.Gear_Version);

define('Gear_Section_AppEngine',                    'AppEngine');
define('Gear_Section_Router',                       'Router');
define('Gear_Section_Controller',                   'Controller');
define('Gear_Section_ActionResolver',               'ActionResolver');
define('Gear_Section_View',                         'View');
define('Gear_Section_Binder',                       'Binder');
define('Gear_Section_Defaults',                     'Defaults');

define('Gear_Key_DefaultArea',                      'area');
define('Gear_Key_DefaultController',                'controller');
define('Gear_Key_DefaultAction',                    'action');
define('Gear_Key_DefaultParams',                    'params');
define('Gear_Key_Engine',                           'Engine');
define('Gear_Key_Loggers',                          'Loggers');
define('Gear_Key_AutoLoading',                      'AutoLoading');
define('Gear_Key_AreaRoot',                         'AreaRoot');
define('Gear_Key_Factory',                          'Factory');
define('Gear_Key_RootPath',                         'RootPath');
define('Gear_Key_Dependencies',                     'Dependencies');
define('Gear_Key_Bundles',                          'Bundles');
define('Gear_Key_PreferredActionPattern',           'PreferredActionPattern');
define('Gear_Key_JsonResultAllowGet',               'JsonResultAllowGet');
define('Gear_Key_LayoutName',                       'Layout');
define('Gear_Key_SharedView',                       'SharedView');
define('Gear_Key_DebugMode',                        'DebugMode');
define('Gear_Key_ApplicationEntry',                 'ApplicationEntry');
define('Gear_Key_RouterUrl',                        'RouterUrl');
define('Gear_Key_ControllerSuffix',                 'ControllerSuffix');
define('Gear_Key_URLPrefix',                        'URLPrefix');

define('Gear_PlaceHolder_Action',                   '[action]');
define('Gear_PlaceHolder_HttpMethod',               '[http_method]');

define('Gear_DefaultRouterFactory',                 'GearDefaultRouteFactory');
define('Gear_DefaultControllerFactory',             'GearDefaultControllerFactory');
define('Gear_DefaultActionResolverFactory',         'GearDefaultActionResolverFactory');
define('Gear_DefaultModelBinderFactory',            'GearDefaultModelBinderFactory');
define('Gear_DefaultViewEngineFactory',             'GearDefaultViewEngineFactory');

define('Gear_DefaultControllerSuffix',              'Controller');

define('Gear_DefaultAreasRootPath',                 'areas');
define('Gear_DefaultSharedRootPath',                '_shared');
define('Gear_DefaultControllersRootPath',           'controller');
define('Gear_DefaultModelsRootPath',                'model');
define('Gear_DefaultViewsRootPath',                 'views');

define('Gear_DefaultLayoutName',                    '_layout');
define('Gear_DefaultPreferredActionPattern',        '[action]__[http_method]');

define('Gear_ServiceRouterEngine',                  'RouterEngineService');
define('Gear_ServiceViewEngineFactory',             'ViewEngineFactoryService');
define('Gear_ServiceViewOutputStream',              'ViewOutputStreamService');
define('Gear_ValidationMessages',                   'ValidationMessages');
class GearActionPartialViewResult
{

}
abstract class GearActionResultBase implements IGearActionResult
{
    private $innerResult;

    public function getInnerResult()
    {
        return $this->innerResult;
    }


    public abstract function executeResult($context, $request, $response);
}
class GearActionViewResult
{

}
/**
 * Class GearAnnotationHelper helps to extract information from doc comments.
 * Format: @annotationName(arg1=23,arg2='test')
 *
 * @package gear\arch\automation\annotations
 */
class GearAnnotationHelper
{
    private $name;
    private $rawValue;
    private $args;


    public function getName()
    {
        return $this->name;
    }

    public function getArg($name, $defaultValue = null)
    {
        return isset($this->args[$name])
            ? $this->args[$name]
            : $defaultValue;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function getValue()
    {
        return $this->rawValue;
    }

    public function __construct($name, $rawArgs)
    {
        $this->args = [];
        $args = preg_split('/(,)(?=(?:[^"]|"[^"]*")*$)/', $rawArgs);

        foreach ($args as $arg) {
            $eqPos = strpos($arg, '=');
            $key = '';
            $value = '';
            if ($eqPos !== false) {
                $fP = trim( substr($arg, 0, $eqPos) , " \t\n\r\0\x0B\"'*" );
                $sP = trim( substr($arg, $eqPos + 1) , " \t\n\r\0\x0B\"'*" );

                $key = $fP;
                $value = $sP;
            } else {
                $key = $arg;
                $value = $arg;
            }

            if (is_numeric($value)) {
                if (is_integer($value)) {
                    $value = intval($value);
                } else {
                    $value = floatval($value);
                }
            } elseif (strtolower($value) == 'false') {
                $value = false;
            } elseif (strtolower($value) == 'true') {
                $value = true;
            }

            $this->args[$key] = $value;
        }
    }

    /**
     * @param $str
     * @return GearAnnotationHelper[]
     */
    public static function exportAnnotations($str)
    {
        $lines = explode(PHP_EOL, $str);

        $annotations = [];
        foreach ($lines as $line) {
            $atSign = strpos($line, '@');
            if ($atSign !== null) {
                $annotations[] = substr($line, $atSign);
            }
        }

        return $annotations;
    }

    /**
     * @param $str
     * @param $name
     * @return GearAnnotationHelper
     */
    public static function exportAnnotation($str, $name)
    {
        $pos = stripos($str, "@$name");
        if ($pos === false) {
            return null;
        }

        $nlPos = stripos($str, PHP_EOL, $pos);
        if ($nlPos === false || $nlPos <= $pos) {
            $annotation = substr($str, $pos);
        } else {
            $oPrant = strpos($str, '(', $pos);
            if ($oPrant !== false) {
                $close = strpos($str, ')', $oPrant);
                $annotation = substr($str, $oPrant + 1, $close - $oPrant - 1);
            } else {
                $annotation = substr($str, $pos, $nlPos - $pos);
            }
        }

        $pStart = strpos($annotation, '(');
        if ($pStart !== false) {
            $pEnd = strpos($annotation, ')');
            if ($pEnd !== false) {
                $annotation = substr($annotation, $pStart + 1, $pEnd - $pStart - 1);
            }
        }

        return new GearAnnotationHelper($name, $annotation);
    }
}
class GearAntiForgeryTokenManager
{
    public static function validateAntiForgeryToken()
    {
        return true;
    }

    public static function generateAntiForgeryToken()
    {
        return '';
    }

    public static function getAntiForgeryToken($createNew = true)
    {

        $antiForgeryToken = self::generateAntiForgeryToken();



        return $antiForgeryToken;
    }
}
class GearAppEngine
{
    const Mvc = 'mvc';

    public static $GearConfigCache;

    /** @var IGearContext */
    private $context;
    /** @var GearConfiguration */
    private $configuration;
    /** @var GearApplication */
    private $applicationEntry;
    /** @var IGearEngineFactory */
    private $controllerFactory;
    /** @var IGearEngineFactory */
    private $actionResolverFactory;

    private $_startExecutionTime;
    private $_createExecutionTime;

    private static
        $createInitializers = [],
        $startInitializers = [];

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

            if (!isset($engine)) {
                $engine = $this->configuration->getValue(Gear_Key_Engine, Gear_Section_AppEngine, 'mvc');
            }

            if ($engine == static::Mvc || is_null($engine))
                $result = self::_startMvc0();
            $this->_startExecutionTime = (microtime(true) - $rStart);

            if ($result == null) {
                throw new GearAppEngineNotFoundException();
            } else {
                return $result;
            }
        } catch (Exception $ex) {
            self::_render500Error($this, $ex);
        }
    }

    public static function getFactory(GearConfiguration $config, $engine, $defaultFactory)
    {
        $factoryClass = $config->getValue(Gear_Key_Factory, $engine, $defaultFactory);

        $factory = new $factoryClass();
        if ($factory == null)
            throw new GearInvalidOperationException();

        return $factory;
    }

    /**
     * @param IGearContext $context
     */
    public static function resolveBundles($context)
    {
        $config = $context->getConfig();
        $bundles = $config->getValue(Gear_Key_Bundles, Gear_Section_AppEngine);

        if (isset($bundles)) {
            $modules = [];
            $directories = [];
            $bundleStrs = explode(',', $bundles);

            foreach ($bundleStrs as $bundle) {

                if ($bundle == null || $bundle == '') continue;
                $bundle = trim($bundle);
                if (substr($bundle, strlen($bundle) - 1) == '*') {
                    $directories[] = substr($bundle, 0, strlen($bundle) - 1);
                } else {
                    $modules[] = $bundle;
                }
            }

            foreach ($directories as $dir) {
                if ($dir == null || $dir == '') continue;
                GearBundle::resolveAllPackageFromDirectory($dir);
            }

            GearBundle::resolveAllPackages($modules);
        }
    }

    /**
     * @param IGearContext $context
     */
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
                if ($dir == null || $dir == '') continue;
                GearBundle::resolveAllUserModuleFromDirectory($dir, true, true);
            }

            GearBundle::resolveAllUserModules($modules, true, true);
        }
    }

    public static function registerCreateInitializer($callback)
    {
        if (!is_callable($callback)) {
            throw new \GearInvalidOperationException("Callable expected.");
        }
        self::$createInitializers[] = $callback;
    }

    public static function registerStartInitializer($callback)
    {
        if (!is_callable($callback)) {
            throw new \GearInvalidOperationException("Callable expected.");
        }
        self::$startInitializers[] = $callback;
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
        $engine = null;
        try {
            $rStart = microtime(true);
            if (is_null($configPath)) {
                $configPath = Gear_Default_ConfigPath;
            }
            $config = GearConfiguration::FromFile($configPath, $type);
            self::$GearConfigCache = $config;

            $debugMode = $config->getValue(Gear_Key_DebugMode, Gear_Section_AppEngine, false);
            if (boolval($debugMode) == true && !defined('DEBUG')) {
                define('DEBUG', 1);
            }

            $autoLoadMode = $config->getValue(Gear_Key_AutoLoading, Gear_Section_AppEngine, null);
            if ($autoLoadMode != null) {
                GearAutoload::register($autoLoadMode);
            }

            $context = new GearAppContext($config);

            GearHttpContext::setCurrent($context);
            self::resolveBundles($context);
            self::resolveDependencies($context);

            /** @var IGearEngineFactory $routeFactory */
            $routeFactory = self::getFactory($config, Gear_Section_Router, Gear_DefaultRouterFactory);
            $route = $routeFactory->createEngine($context);
            $context->registerService(Gear_ServiceRouterEngine, $route);

            /** @var IGearEngineFactory $binderFactory */
            $binderFactory = self::getFactory($config, Gear_Section_Binder, Gear_DefaultModelBinderFactory);
            $binder = $binderFactory->createEngine($context);

            $request = new GearHttpRequest($route);
            $response = new GearHttpResponse();
            $context->setRoute($route);
            $context->setBinder($binder);
            $context->setRequest($request);
            $context->setResponse($response);

            $loggers = $config->getValue(Gear_Key_Loggers, Gear_Section_AppEngine);
            if ($loggers != null) {
                $lgs = explode(',', $loggers);
                foreach ($lgs as $logger) {
                    $logger = trim($logger);
                    $loggerInstance = new $logger();
                    GearLogger::registerLogger($loggerInstance);
                }
            }

            $controllerFactory = self::getFactory($config, Gear_Section_Controller, Gear_DefaultControllerFactory);
            $actionResolverFactory = self::getFactory($config, Gear_Section_ActionResolver, Gear_DefaultActionResolverFactory);
            $viewEngineFactory = self::getFactory($config, Gear_Section_View, Gear_DefaultViewEngineFactory);

            $context->registerService(Gear_ServiceViewEngineFactory, $viewEngineFactory);

            $engine = new self(
                $context,
                $config,
                $routeFactory,
                $controllerFactory,
                $actionResolverFactory);

            header(Gear_PoweredResponseHeader);

            $applicationEntry = $config->getValue(Gear_Key_ApplicationEntry, Gear_Section_AppEngine);
            if (isset($applicationEntry)) {
                /** @var GearApplication $applicationEntryClass */
                $applicationEntryClass = new $applicationEntry();
                $engine->applicationEntry = $applicationEntryClass;

                $applicationEntryClass->appCreate($context, $engine);

                $applicationEntryClass->configRoute($context, $route, $route->getConfigurator());
            }

            $engine->_createExecutionTime = (microtime(true) - $rStart);
            return $engine;
        } catch (Exception $ex) {
            self::_render500Error($engine, $ex);
        }
    }

    static function _render500Error($engine, $ex)
    {
        if ($engine != null) {
            /** @var GearApplication $app */
            $app = $engine->applicationEntry;
            if ($app != null) {
                $app->onExceptionOccurred($ex);
            }
        }

        GearBundle::Arch(Gear_500InternalServerErrorPageName);

        GearInternalServerError::Render($ex);
    }
}
abstract class GearApplication
{
    public function appCreate($context, $appEngine)
    {

    }

    public function configRoute($context, $routeService, $routeConfig)
    {

    }

    public function onExceptionOccurred($ex)
    {

    }
}
class GearArgumentNullException extends \Exception implements IGearMessageException
{
    public function __construct($argument = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($argument == null
            ? 'Argument null exception.'
            : "Argument '$argument' is null.'", $code, $previous);
    }

    function getHttpStatusCode()
    {
        return 500;
    }
}
class GearAutoload
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
                throw new GearInvalidOperationException();
        }
    }

    private static function _probing($className)
    {
        GearBundle::prob($className);
    }

    private static function _userProbing($className)
    {
        GearBundle::resolveUserModule($className, true, true);
    }
}
class GearBatchActionResult extends GearActionResultBase
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
class GearConfiguration
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

    /**
     * @param $value string
     * @param null $section string
     * @param null $defaultValue string
     *
     * @return string
     */
    public function getValue($value, $section = null, $defaultValue = null)
    {
        if (isset($section)) {
            $result = isset($this->c[$section][$value])
                ? $this->c[$section][$value]
                : null;
        } else {
            $result = isset($this->c[$value])
                ? $this->c[$value]
                : null;
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
        return new self(GearPALIniFileHelper::ParseIniFile($path, true), "ini");
    }

    public static function FromXmlFile($path)
    {
        return new self('', "xml");
    }
}
class GearContentResult
{

}
class GearDefaultActionResolverFactory implements IGearEngineFactory
{
    function createEngine($context)
    {
        return new GearDefaultActionResolver();
    }
}
class GearDefaultControllerFactory implements IGearEngineFactory
{
    public function createEngine($context)
    {
        $config = $context->getConfig();
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();

        $controllerName = $mvcContext->getControllerName();
        $areaName = $mvcContext->getAreaName();

        $controllerSuffix = $config->getValue(Gear_Key_ControllerSuffix, Gear_Section_Controller, Gear_DefaultControllerSuffix);

        $controllerRootPath = $config->getValue(Gear_Key_RootPath, Gear_Section_Controller, Gear_DefaultControllersRootPath);

        $controllerPath = $this->getControllerPath(
            $context,
            $config,
            $route,
            $mvcContext,
            $areaName,
            $controllerName,
            $controllerRootPath,
            $controllerSuffix);

        try {
            GearBundle::resolveUserModule($controllerPath);
        } catch (\Exception $ex) {
            throw new GearHttpNotFoundException("Controller '$controllerName' not found on '$controllerPath'.");
        }
        if (!class_exists($controllerName)) {
            throw new GearHttpNotFoundException("Controller '$controllerName' not found.");
        }
        
        return new $controllerName($context);
    }

    public function getControllerPath(
        $context,
        $config,
        $route,
        $mvcContext,
        $areaName,
        &$controllerName,
        $controllerRootPath,
        $controllerSuffix)
    {
        if (substr($controllerName, strlen($controllerName) - 10) != $controllerSuffix)
            $controllerName .= $controllerSuffix;

        $controllerPath = "$controllerRootPath/$controllerName.php";
        if (isset($areaName) && $areaName != '') {
            $controllerPath = "$areaName/$controllerPath";
            $areaRootPath = $config->getValue(Gear_Key_AreaRoot, Gear_Section_Controller, Gear_DefaultAreasRootPath);
            if (isset($areaRootPath) && $areaRootPath != '') {
                $controllerPath = "$areaRootPath/$controllerPath";
            }
        }
        return $controllerPath;
    }
}
class GearDefaultModelBinderEngine implements IGearModelBinderEngine
{
    protected
        $useRequestParams = true
    ;

    public function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext)
    {
        //$constructor = $modelDescriptor->getConstructor();
        //if (isset($constructor)) {
        //    throw new GearInvalidOperationException("ViewModel has a implemented constructor method.");
        //}
        $instance = $modelDescriptor->newInstance();
        if ($instance == null) {
            throw new GearInvalidOperationException('Argument $instance is null.');
        }

        $request = $context->getRequest();

        $sources = [];
        if ($request->isJsonRequest()) {
            $sources[] = json_decode($request->getBody(), true, 512, JSON_OBJECT_AS_ARRAY);
        }
        if ($this->useRequestParams) {
            $sources[] = $request->getCurrentMethodValues();
        }

        $sources[] = $mvcContext->getParams();
        self::_bind($context, $instance, $sources);

        return $instance;
    }

    public function fillModelFromContext($instance, $context, $controller, $mvcContext)
    {
        $request = $context->getRequest();

        $sources = [];
        if ($request->isJsonRequest()) {
            $sources[] = json_decode($request->getBody(), true, 512, JSON_OBJECT_AS_ARRAY);
        }
        if ($this->useRequestParams) {
            $sources[] = $request->getCurrentMethodValues();
        }

        $sources[] = $mvcContext->getParams();
        self::_bind($context, $instance, $sources);
    }

    private static function _bind($context, $instance, $sources)
    {
        $vars = get_class_vars(get_class($instance));
        foreach ($vars as $k => $v) {
            $result = null;
            foreach ($sources as $source) {
                if (!$source || !isset($source)) continue;
                if (GearHelpers::tryGetArrayElementByNameCaseInSensetive($source, $k, $result))
                    $instance->$k = $result;
            }
        }
    }
}
class GearDefaultModelBinderFactory implements IGearEngineFactory
{
    function createEngine($context)
    {
        return new GearDefaultModelBinderEngine();
    }
}
class GearDefaultRouteFactory implements IGearEngineFactory
{
    public function createEngine($context)
    {
        $config = GearAppEngine::$GearConfigCache;
        if($config == null) {
            throw new GearInvalidOperationException();
        }
        return new GearDefaultRouteService($config);
    }
}
class GearDefaultRouteService implements IGearRouteService
{
    private
        $area,
        $controller,
        $action,
        $params,

        $mvcContext;

    /**
     * GearDefaultRouteService constructor.
     *
     * @param $config GearConfiguration
     */
    public function __construct($config)
    {
        $area = $config->getValue(Gear_Key_DefaultArea, Gear_Section_Defaults, '');
        $controller = $config->getValue(Gear_Key_DefaultController, Gear_Section_Defaults, 'home');
        $action = $config->getValue(Gear_Key_DefaultAction, Gear_Section_Defaults, 'index');
        $params = $config->getValue(Gear_Key_DefaultParams, Gear_Section_Defaults, '');

        $this->area = $area;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;

        $this->mvcContext = new GearRouteMvcContext(
            $area,
            $controller,
            $action,
            $params
        );
    }

    function getMvcContext()
    {
        return $this->mvcContext;
    }

    function createUrl($context, $mvcContext, $params)
    {
        throw new GearNotSupportedException();
        //return 'Default route service not support reverse routing.';
    }

    function getConfigurator()
    {
        throw new GearNotSupportedException();
    }

    function enableCache()
    {
    }

    function setUrlProvider($provider)
    {
        throw new GearNotSupportedException();
    }
}
class GearDefaultViewEngineFactory implements IGearEngineFactory
{
    static $instance ;
    function createEngine($context)
    {
        if (self::$instance == null) {
            self::$instance = new GearDefaultViewEngine();
        }
        return self::$instance;
    }
}
class GearDynamicDictionary implements \ArrayAccess
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
class GearEndResponseResult
{

}
class GearErrorStrategy
{
    /**
     * @param \Exception $ex
     * @param $uid
     *
     * @return string
     */
    public static function saveLogAndGetTrace($ex, &$uid)
    {
        $uid = uniqid();
        $message = $ex->getMessage();
        $trace = $ex->getTrace();
        GearLogger::write("TrackId: $uid - Message: $message - Trace: $trace");
        if (defined('DEBUG')) {
            return strval($ex);
        } else {
            return "An error has been occurred. Error unique id: $uid";
        }
    }
}
class GearExecuteActionResult
{

}
class GearExtensibleClass
{
    /** @var array */
    protected static $staticExtensionMethods = [];
    /** @var array */
    protected $extensionMethods = [];
    /** @var array */
    protected static $memberExtensionMethods = [];

    protected $caseSensitive = false;

    /**
     * GearExtensibleClass constructor.
     * @param $caseSensitive bool Indicates the case-sensitiveness of name comparison.
     */
    public function __construct($caseSensitive = false)
    {
        $this->caseSensitive = $caseSensitive;
    }

    public function __call($name, $args)
    {
        $lowerName = strtolower($name);
        if (!$this->caseSensitive) {
            $name = $lowerName;
        }
        if (isset(static::$memberExtensionMethods[$lowerName])) {
            $method = static::$memberExtensionMethods[$lowerName];
            if ($args == null) {
                $args = [$this];
            } else {
                $args = array_merge([$this], $args);
            }
        } elseif (isset($this->extensionMethods[$name])) {
            $method = $this->extensionMethods[$name];
        } elseif (isset(static::$staticExtensionMethods[$lowerName])) {
            $method = static::$staticExtensionMethods[$lowerName];
        } else {
            throw new GearInvalidOperationException("Method '$name' not found.");
        }

        return call_user_func_array($method, $args);
    }

    /**
     * Adds an extension method to extended member methods list.
     * @param $name
     * @param $callableValue
     */
    public static function setMemberExtensionMethod($name, $callableValue)
    {
        $name = strtolower($name);
        static::$memberExtensionMethods[$name] = $callableValue;
    }

    /**
     * Adds a list of extension methods to extended member methods list.
     * @param $dictionaryOfCallable
     */
    public static function setMemberExtensionMethods($dictionaryOfCallable)
    {
        foreach ($dictionaryOfCallable as $name => $callable) {
            $name = strtolower($name);
            static::$memberExtensionMethods[$name] = $callable;
        }
    }

    /**
     * Removes an extension method from extended member methods list.
     * @param $name
     */
    public function removeMemberExtensionMethod($name)
    {
        $name = strtolower($name);
        unset(static::$memberExtensionMethods[$name]);
    }


    /**
     * Adds an extension method to extended methods list.
     * @param $name
     * @param $callableValue
     */
    public function setExtensionMethod($name, $callableValue)
    {
        if ($this->caseSensitive) {
            $name = strtolower($name);
        }
        $this->extensionMethods[$name] = $callableValue;
    }

    /**
     * Adds a list of extension methods to extended methods list.
     * @param $dictionaryOfCallable
     */
    public function setExtensionMethods($dictionaryOfCallable)
    {
        $caseSensitive = $this->caseSensitive;
        foreach ($dictionaryOfCallable as $name => $callable) {
            if ($caseSensitive) {
                $name = strtolower($name);
            }
            $this->extensionMethods[$name] = $callable;
        }
    }

    /**
     * Removes an extension method from extended methods list.
     * @param $name
     */
    public function removeExtensionMethod($name)
    {
        if ($this->caseSensitive) {
            $name = strtolower($name);
        }
        unset($this->extensionMethods[$name]);
    }


    /**
     * Adds an extension method to extended methods list.
     * @param $name
     * @param $callableValue
     */
    public static function setStaticExtensionMethod($name, $callableValue)
    {
        $name = strtolower($name);
        static::$staticExtensionMethods[$name] = $callableValue;
    }

    /**
     * Adds a list of extension methods to extended methods list.
     * @param $dictionaryOfCallable
     */
    public static function setStaticExtensionMethods($dictionaryOfCallable)
    {
        foreach ($dictionaryOfCallable as $name => $callable) {
            $name = strtolower($name);
            static::$staticExtensionMethods[$name] = $callable;
        }
    }

    /**
     * Removes an extension method from extended methods list.
     * @param $name
     */
    public static function removeStaticExtensionMethod($name)
    {
        $name = strtolower($name);
        unset(static::$staticExtensionMethods[$name]);
    }
}
class GearFileResult extends GearActionResultBase
{
    /** @var string */
    public $filePath;
    /** @var string */
    public $mimeType;

    public function __construct($filePath, $mimeType = null)
    {
        $this->filePath = $filePath;
        $this->mimeType = $mimeType;
    }

    public function executeResult($context, $request, $response)
    {
        if (file_exists($this->filePath)) {
            if ($this->mimeType == null) {
                $mime = GearMimeHelper::getMimeFromExtension($this->filePath);
                $response->setContentType($mime);
            } else {
                $response->setContentType($this->mimeType);
            }

            echo file_get_contents($this->filePath);
        } else {
            throw new GearHttpNotFoundException("File '$this->filePath' not found.");
        }
    }
}
class GearFsModuleLocator implements IGearModuleLocator
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
class GearGeneralHelper
{
    public static function parseHeaders($headers)
    {
        $headers = preg_replace('/^\r\n/m', '', $headers);
        $headers = preg_replace('/\r\n\s+/m', ' ', $headers);
        preg_match_all('/^([^: ]+):\s(.+?(?:\r\n\s(?:.+?))*)?\r\n/m', $headers . "\r\n", $matches);

        $result = array();
        foreach ($matches[1] as $key => $value)
            $result[$value] = (array_key_exists($value, $result) ? $result[$value] . "\n" : '') . $matches[2][$key];

        return $result;
    }
}
class GearHeaderResult
{

}
class GearHelpers
{
    public static function tryGetArrayElementByNameCaseInSensetive(&$arr, $key, &$result)
    {
        $key = strtolower($key);
        foreach ($arr as $k => $val)
            if (strtolower($k) == $key) {
                $result = $val;
                return true;
            }
        return false;
    }

    public static function isNullOrWhitespace($string)
    {
        if ((!isset($string)) || $string == null) {
            return true;
        }
        return preg_match('/^[\s]*$/', $string);
    }

    private static function _dumpArray($arr,$indent){
        $size=sizeof($arr);
        echo"<span style=\"color:orange;\">Array($size)</span> => [";
        foreach($arr as $k=>$e){
            echo'<br>';
            for($i=0;$i<$indent;$i++)echo"----";
            echo"<span style=\"color:green;\">'$k'</span> : ";
            if(is_array($e))self::_dumpArray($e,$indent+1);else var_dump($e);
            echo' ,<br>';
        }
        for($i=0;$i<$indent;$i++)echo"----";
        echo']';
    }
    public static function show($var){if(is_array($var))self::_dumpArray($var,1);else var_dump($var);}
}
class GearHtmlHelper extends GearExtensibleClass
{
    /** @var IGearContext */
    private $context;
    /** @var IGearMvcContext */
    private $mvcContext;
    /** @var GearUrlHelper */
    private $url;
    /** @var GearController */
    private $controller;

    /**
     * UrlHelper constructor.
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param GearUrlHelper $urlHelper
     * @param $controller
     */
    public function __construct($context, $mvcContext, $urlHelper, $controller)
    {
        parent::__construct(true);

        $this->context = $context;
        $this->mvcContext = $mvcContext;
        $this->url = $urlHelper;
        $this->controller = $controller;
    }

    /**
     * @return IGearContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return IGearMvcContext
     */
    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    /**
     * @return GearUrlHelper
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return GearController
     */
    public function getController()
    {
        return $this->controller;
    }


    public function partial($name, $model = null, $params = null)
    {
        $context = $this->context;
        $viewEngineFactory = $context->getService(Gear_ServiceViewEngineFactory);

        /** @var IGearViewEngine $viewEngine */
        $viewEngine = $viewEngineFactory->createEngine($context);

        if(!isset($name)){
            $name = $context->getRoute()->getMvcContext()->getActionName();
        }

        $viewEngine->renderPartialView(
            $context,
            $this->controller,
            $name,
            $model
        );

        return null;
    }
    public function partialIfExists($name, $model = null, $params = null)
    {
        try {
            $context = $this->context;
            $viewEngineFactory = $context->getService(Gear_ServiceViewEngineFactory);

            /** @var IGearViewEngine $viewEngine */
            $viewEngine = $viewEngineFactory->createEngine($context);

            if(!isset($name)) {
                $name = $context->getRoute()->getMvcContext()->getActionName();
            }

            $viewEngine->renderPartialView(
                $context,
                $this->controller,
                $name,
                $model
            );

            return null;
        } catch (GearViewFileNotFoundException $ex) {

        }
    }
}
class GearHtmlString
{
    /** @var callable(string) */
    private static $compressor;

    /** @var string */
    private $buffer;
    /** @var bool */
    private $allowCompression;

    /**
     * GearHtmlString constructor.
     * @param string $buffer
     * @param bool $allowCompression
     */
    public function __construct($buffer, $allowCompression = true)
    {
        $this->buffer = $buffer;
        $this->allowCompression = $allowCompression;
    }

    /**
     * Clears the buffer.
     */
    public function clear()
    {
        $this->buffer = '';
    }

    /**
     * Appends string into buffer.
     *
     * @param string $value
     */
    public function append($value)
    {
        $this->buffer .= $value;
    }

    /**
     * Prepends string into buffer.
     *
     * @param string $value
     */
    public function prepend($value)
    {
        $this->buffer = $value . $this->buffer;
    }

    /**
     * Returns a copy of internal buffer.
     *
     * @return string
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * Provides reference access to internal buffer.
     *
     * @return string
     */
    public function &accessBuffer()
    {
        return $this->buffer;
    }

    public function __toString()
    {
        $compressor = self::$compressor;
        if ($this->allowCompression && $compressor != null) {
            return $compressor($this->buffer);
        }
        return $this->buffer;
    }
}
class GearHttpContext
{
    static
        $currentContext;

    public $ending;

    public function End()
    {
        if (is_callable($this->ending)) {
            $c = $this->ending;
            $c();
        }
        exit;
    }

    /**
     * @return IGearContext
     */
    public static function current()
    {
        return self::$currentContext;
    }

    /**
     * @param $context IGearContext
     */
    public static function setCurrent($context)
    {
        self::$currentContext = $context;
    }
}
class GearHttpRequest implements IGearHttpRequest
{
    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getValue($name, $defaultValue = null)
    {
        if (!isset($_REQUEST[$name])) {
            return $defaultValue;
        }
        return $_REQUEST[$name];
    }

    public function getBody()
    {
        return file_get_contents("php://input");
    }
    
    public function getHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            $headers = array();
            foreach($_SERVER as $key => $value)
            {
                if(preg_match("/^HTTP_X_/", $key))
                    $headers[$key] = $value;
            }
            return $headers;
        }
    }
    
    public function getHeader($name, $defaultValue = null)
    {
        $name = str_replace('-', '_', $name);
        $name = strtoupper($name);
        return isset($_SERVER['HTTP_' . $name])
            ? $_SERVER['HTTP_' . $name]
            : $defaultValue;
    }
    
    public function getQueryStrings()
    {
        return $_SERVER['QUERY_STRING'];
    }
    
    public function getQueryString($name, $defaultValue = null)
    {
        return isset($_GET[$name])
            ? $_GET[$name]
            : $defaultValue;
    }
    
    public function getForms()
    {
        return $_POST;
    }
    
    public function getForm($name, $defaultValue = null)
    {
        return isset($_POST[$name])
            ? $_POST[$name]
            : $defaultValue;
    }

    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function getRawUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function getContentType()
    {
        if (isset($_SERVER['CONTENT_TYPE'])) {
            return $_SERVER['CONTENT_TYPE'];
        }
        return 'text/html';
    }

    public function getProtocol()
    {
        return $_SERVER['REQUEST_SCHEME'];
        //$protocol = $_SERVER["SERVER_PROTOCOL"];
        //$slash = strpos($protocol, '/');
        //return substr($protocol, 0, $slash);
    }

    public function accepts()
    {

    }

    public function getAllValues()
    {
        return $_REQUEST;
    }

    public function &getCurrentMethodValues()
    {
        $requestMethod = $this->getMethod();// $_SERVER['REQUEST_METHOD'];
        switch ($requestMethod) {
            case'GET':
                return $_GET;
            //case'POST':return$this->POST;
            default:
                return $_POST;
        }
    }

    /**
     * @return bool
     */
    public function isMultipart()
    {
        return $this->getContentType() == 'multipart';
    }

    /**
     * @return bool
     */
    public function isAjaxRequest()
    {
        return $this->getHeader('X-Requested-With') == 'XMLHttpRequest';
    }

    /**
     * @return bool
     */
    public function isJsonRequest()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'application/json' ||
            $contentType == 'text/json';
    }

    /**
     * @return bool
     */
    public function isXmlRequest()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'application/xml' ||
            $contentType == 'text/xml'/* || $contentType == 'application/xhtml+xml'*/;
    }

    /**
     * @return bool
     */
    public function isUrlEncodedRequest()
    {
        $contentType = strtolower($this->getContentType());
        return
            $contentType == 'x-www-form-urlencoded';

    }
}
class GearInspectableClass
{
    /**
     * @param $name
     *
     * @return mixed
     *
     * @throws GearInvalidOperationException
     */
    public function getProperty($name)
    {
        throw new GearInvalidOperationException("Property '$name' not found.");
    }
    public final function __get($name)
    {
        return property_exists($this, $name)
            ? $this->$name
            : $this->getProperty($name);
    }
    public function __isset($name)
    {
        $result = property_exists($this,$name)
            ? $this->$name
            : $this->getProperty($name);
        return $result == $this
            ? true
            : isset($result);
    }
}
class GearInternalServerError
{
    public static function Render($ex, $eCode = null)
    {
        if ($eCode == null) {
            if ($ex instanceof IGearMessageException) {
                $errorCode = $ex->getHttpStatusCode();
            } else {
                $errorCode = 500;
            }
        } else {
            $errorCode = $eCode;
        }

        if (defined('DEBUG')) {
            GearLogger::write($ex->getMessage() . ' trace:' . GearSerializer::stringify($ex->getTrace()));
        }

        http_response_code($errorCode);
        $errMessage = defined('DEBUG') ? $ex->getMessage() : 'An Error Has Been Occurred!';
        echo "<title>$errorCode - Error</title><h1 style=\"color:red\">$errorCode - $errMessage</h1><br>" .
            (defined('DEBUG') ?
                $ex->getMessage() . '<br>' .
                $ex->getFile() . ' at line: ' . $ex->getLine() . '<br><br>' .
                $ex->getTraceAsString()
                :
                "Sorry! An internal server error has been occured.<br>Please report to website admin.")
            ;
    }
}
class GearInvalidOperationException extends \Exception implements IGearMessageException
{
    public function __construct($message = '', $code = 0, \Exception $previous = null)
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
class GearJsonPResult
{

}
class GearLogger
{
    private static $loggers = [];

    public static function write($mixed, $category = null)
    {
        foreach (self::$loggers as $logger) {
            /** @var $logger IGearLogger */
            $logger->write($mixed, $category);
        }
    }

    public static function registerLogger($logger)
    {
        self::$loggers[] = $logger;
    }
}
class GearMapper
{
    public static function MapTo($targetClassName, $instance, $options = null)
    {

    }

    public static function Copy($destination, $source, $options = null)
    {

    }

    public static function FillFrom($destination, $valueProvider, $options = null)
    {

    }
}
class GearMimeHelper
{
    public static $mimes = [
        'image' => [
            'jpg', 'jpeg', 'png', 'tiff', 'tif', 'gif', 'bmp', 'jpe', 'dib', 'jfif'
        ],
        'text' => [
            'css', 'js', 'xml', 'html', 'xhtml', 'htm', 'mht', ['plain' => 'txt']
        ]
    ];

    public static function getMimeFromExtension($ext)
    {
        $ext = strtolower(GearPath::GetExtension($ext));
        foreach (self::$mimes as $media => $mimes) {
            foreach ($mimes as $key => $value) {
                if ($value == $ext) {
                    if (is_numeric($key)) {
                        return $media.'/'.$value;
                    } else {
                        return $media.'/'.$key;
                    }
                }
            }
        }
        return null;
    }
}
class GearModelBindingContext
{

}
class GearNotSupportedException extends \Exception implements IGearMessageException
{
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message == null
            ? 'Not supported.'
            : $message, $code, $previous);
    }

    function getHttpStatusCode()
    {
        return 500;
    }
}
class GearODataResult
{

}
class GearPALIniFileHelper
{
    public static function ParseIniFile($path, $processSections)
    {
        return parse_ini_file($path, $processSections);
    }
}
class GearPartialViewResult
{

}
class GearPath
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
class GearProfiler
{
    private $start;
    private $end;

    public function startProfiling()
    {
        $this->start = microtime(true);
    }

    public function endProfiling()
    {
        $this->end = microtime(true);
    }

    public function reset()
    {
        $this->start = microtime(true);
    }

    public function getStart()
    {
        if (isset($this->start)) {
            return $this->start;
        } else {
            throw new GearInvalidOperationException("Profiler does not started yet.");
        }
    }

    public function getEnd()
    {
        if (isset($this->end)) {
            return $this->end;
        } else {
            throw new GearInvalidOperationException("Profiler does not finished yet.");
        }
    }

    public function getTotalTime()
    {
        if (!isset($this->start)) {
            throw new GearInvalidOperationException("Profiler does not started yet.");
        }
        if (!isset($this->end)) {
            throw new GearInvalidOperationException("Profiler does not finished yet.");
        }

        return $this->end - $this->start;
    }
}
class GearSerializer
{
    static $jsonSerializer;

    public static function setJsonSerializer($serializer)
    {
        self::$jsonSerializer = $serializer;
    }

    public static function stringify($mixed)
    {
        $result = '';
        if (is_object($mixed)) $result .= get_class($mixed);
        elseif (is_array($mixed)) $result .= self::json($mixed);
        else $result .= strval($mixed);
        return $result;
    }

    public static function json($mixed, $config = null)
    {
        if (self::$jsonSerializer != null) {
            return self::$jsonSerializer->serialize($mixed, $config);
        }
        return json_encode($mixed);
    }

    public static function xml($mixed, $config = null)
    {

    }
}
class GearUrlHelper extends GearExtensibleClass implements IActionUrlBuilder
{
    /** @var IGearContext */
    private $context;
    /** @var IGearMvcContext */
    private $mvcContext;
    /** @var IGearRouteService */
    private $route;
    /** @var string */
    private $urlPrefix;

    /**
     * UrlHelper constructor.
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param IGearRouteService $routeService
     */
    public function __construct($context, $mvcContext, $routeService)
    {
        parent::__construct(true);

        $this->context = $context;
        $this->mvcContext = $mvcContext;
        $this->route = $routeService;

        $config = $context->getConfig();
        $this->urlPrefix = $config->getValue(Gear_Key_URLPrefix, Gear_Section_AppEngine);
    }

    /**
     * @return IGearContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return IGearMvcContext
     */
    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    public function route($routeParams)
    {
        return $this->route->createUrl($this->context, $this->mvcContext, $routeParams);
    }

    public function action($actionName, $controllerName = null, $routeParams = null, $queryString = null)
    {
        if ($controllerName == null) {
            $controllerName = $this->mvcContext->getControllerName();
        }

        $headArray = ['action' => $actionName, 'controller' => $controllerName];
        $areaName = $this->mvcContext->getAreaName();
        if ($areaName != null) {
            $headArray['area'] = $areaName;
        }
        if ($routeParams != null) {
            foreach ($routeParams as $name => $param) {
                if ($param == null || $param == '') {
                    unset($headArray[$name]);
                    unset($routeParams[$name]);
                }
            }
        }
        if (is_array($routeParams)) {
            $routeParams = array_merge($headArray, $routeParams);
        } elseif ($routeParams != null) {
            $routeParams = array_merge($headArray, array($routeParams));
        } else {
            $routeParams = $headArray;
        }

        $url =
            $this->urlPrefix .
            $this->route->createUrl($this->context, $this->mvcContext, $routeParams);

        if (is_array($queryString)) {
            $queries = [];
            foreach ($queryString as $key => $qs) {
                $queries[] = $key.'='.urlencode($qs);
            }
            if (count($queries) > 0) {
                $url .= '?' . (implode('&', $queries));
            }
        }

        return $url;
    }

    public function content($path)
    {
        $firstChar = substr($path, 0, 1);
        if ($firstChar == '/' || $firstChar == '\\') {
            $path = "{$this->urlPrefix}$path";
        } elseif($this->urlPrefix != null) {
            $path = "{$this->urlPrefix}/$path";
        }
        return $path;
    }

    public function asset($path)
    {
        return $this->content('assets/' . $path);
    }
}
class GearViewResult extends GearActionResultBase
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

        /** @var IGearViewEngine $viewEngine */
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
interface IActionUrlBuilder
{
    function action($actionName, $controllerName = null, $routeParams = null, $queryStrings = null);
}
interface IGearActionResolver
{
    /**
     * @param $controller GearController
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $request IGearHttpRequest
     * @param $actionName string
     *
     * @return bool Always returns true.
     */
    function invokeAction($controller,
                          $context,
                          $mvcContext,
                          $request,
                          $actionName);
}
interface IGearActionResult
{
    /**
     * @param $context IGearContext
     * @param $request IGearHttpRequest
     * @param $response IGearHttpResponse
     *
     * @return mixed
     */
    function executeResult($context, $request, $response);

    /**
     * @return IGearInnerActionResult
     */
    function getInnerResult();
}
interface IGearBundleLocator
{
    /**
     * @param $path string
     * @param $require bool
     * @param $once bool
     *
     * @return bool
     */
    function tryLocate($path, $require, $once);
}
interface IGearClassLoader
{
    function createInstance($params);
}
interface IGearContext
{
    /** @return IGearRouteService */
    function getRoute();
    /** @return GearConfiguration */
    function getConfig();
    /** @return IGearHttpRequest */
    function getRequest();
    /** @return IGearHttpResponse */
    function getResponse();
    /** @return IGearModelBinderEngine */
    function getBinder();

    /**
     * Register and holds a variable, passing null as value cause value to remove from context.
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    function setValue($name, $value);

    /**
     * Returns a stored value from context. returns null on not existence.
     *
     * @param $name
     * @return mixed
     */
    function getValue($name);

    /**
     * Registers a public service into context.
     *
     * @param $serviceName string
     * @param $service mixed
     * @return void
     */
    function registerService($serviceName, $service);

    /**
     * Removes a service from context.
     *
     * @param $serviceName
     * @return mixed
     */
    function removeService($serviceName);

    /**
     * Retrieves a public service from context.
     *
     * @param $serviceName string
     * @return mixed Requested service.
     */
    function getService($serviceName);
}
interface IGearEngineFactory
{
    /**
     * @param $context IGearContext
     *
     * @return mixed Target engine.
     */
    function createEngine($context);
}
interface IGearHtmlRenderer
{
    /**
     * @param IGearOutputStream $stream
     * @return mixed
     */
    function renderToStream($stream);
}
interface IGearHttpRequest
{
    /**
     * @param string $name
     * @param mixed|null $defaultValue
     *
     * @return mixed
     */
    function getValue($name, $defaultValue = null);

    /**
     * @return string
     */
    function getBody();
    
    /**
     * @return array
     */
    function getHeaders();
    
    /**
     * @return string
     */
    function getHeader($name);

    /**
     * @return array
     */
    function getQueryStrings();

    /**
     * @return array
     */
    function getForms();
    
    /**
     * @return string
     */
    function getQueryString($name);
    
    /**
     * @return string
     */
    function getForm($name);

    /**
     * @return string
     */
    function getMethod();

    /**
     * @return string
     */
    function getRawUrl();

    /**
     * @return string
     */
    function getContentType();

    /**
     * @return string
     */
    function getProtocol();

    /**
     * @return bool
     */
    function isMultipart();

    /**
     * @return bool
     */
    function isAjaxRequest();

    /**
     * @return bool
     */
    function isJsonRequest();

    /**
     * @return bool
     */
    function isXmlRequest();

    /**
     * @return bool
     */
    function isUrlEncodedRequest();

    /**
     * @return string
     */
    function accepts();

    /**
     * @return array
     */
    function getAllValues();

    /**
     * @return array
     */
    function &getCurrentMethodValues();
}
interface IGearInnerActionResult extends IGearActionResult
{

}
interface IGearLogger
{
    /**
     * @param $mixed mixed
     * @param null $category
     * @return mixed
     */
    function write($mixed, $category = null);
}
interface IGearMessageException
{
    /**
     * @return int
     */
    function getHttpStatusCode();
}
interface IGearModel
{
    function validate(&$errorDictionary);
}
interface IGearModelBinderEngine
{
    /**
     * @param \ReflectionClass $modelDescriptor
     * @param IGearContext $context
     * @param GearController $controller
     * @param IGearMvcContext $mvcContext
     * @return mixed
     */
    function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext);

    /**
     * @param object $instance
     * @param IGearContext $context
     * @param GearController $controller
     * @param IGearMvcContext $mvcContext
     * @return mixed
     */
    function fillModelFromContext($instance, $context, $controller, $mvcContext);
}
interface IGearModuleLocator
{
    function Exists($module, $descriptor, $context);
    function GetAbsolutePath($module, $descriptor, $context);
    function Add($module, $descriptor, $context);
}
interface IGearMvcContext
{
    /**
     * @return string
     */
    function getAreaName();
    /**
     * @return string
     */
    function getControllerName();
    /**
     * @return string
     */
    function getActionName();
    /**
     * @return array
     */
    function getParams();
}
interface IGearOutputStream
{
    function write($mixed);
    function clear();
}
interface IGearRouteService
{
    /**
     * @return mixed Underlying routing service configurator.
     */
    function getConfigurator();
    /**
     * Returns mvc context.
     *
     * @return IGearMvcContext
     */
    function getMvcContext();

    /**
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $params array
     *
     * @return string
     */
    function createUrl($context, $mvcContext, $params);

    /**
     * @param $provider IGearRouteService
     *
     * @return void
     */
    function setUrlProvider($provider);

    /**
     * Enables mvcContext cache.
     *
     * @return void
     */
    function enableCache();
}
interface IGearSerializer
{
    function serialize($mixed, $config);
    function deserialize($json, $config);
}
interface IGearViewEngine
{
    /**
     * @param $context IGearContext
     * @param $controller GearController
     * @param $viewName string
     * @param $model mixed
     *
     * @return IGearActionResult
     */
    function renderView(
        $context,
        $controller,
        $viewName,
        $model
    );

    /**
     * @param IGearContext $context
     * @param GearController $controller
     * @param string $partialViewName
     * @param mixed $model
     *
     * @return IGearActionResult
     */
    function renderPartialView(
        $context,
        $controller,
        $partialViewName,
        $model
    );
}
class GearAppContext implements IGearContext
{
    private
        $route,
        $config,
        $request,
        $response,
        //$binderFactory,
        $binder,
        $services,
        $values;

    public function __construct($config)
    {
        //$this->route = $route;
        $this->config = $config;
        //$this->request = $request;
        //$this->response = $response;
        $this->services = [];
        $this->values = [];

        //$this->binder = $binderFactory->createEngine($this);
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    function getConfig()
    {
        return $this->config;
    }

    function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    function getBinder()
    {
        return $this->binder;
    }

    public function setBinder($binder)
    {
        $this->binder = $binder;
    }

    public function setValue($name, $value)
    {
        if (!isset($value) && isset($this->values[$name])) {
            unset($this->values[$name]);
            return;
        }

        $this->values[$name] = $value;
    }

    public function getValue($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }
        return null;
    }

    public function registerService($serviceName, $service)
    {
        $this->services[$serviceName] = $service;
    }

    public function removeService($serviceName)
    {
        unset($this->services[$serviceName]);
    }

    public function getService($serviceName)
    {
        return isset($this->services[$serviceName])
            ? $this->services[$serviceName]
            : null;
    }
}
class GearAutoClassLoader implements IGearClassLoader
{
    private $className;
    private $namespace;

    public function __construct($className, $namespace)
    {
        $this->className = $className;
        $this->namespace = $namespace;
    }

    public function createInstance($params)
    {
        $alias = "$this->namespace\\$this->className";

        if ($params == null) {
            return new $alias();
        } else {
            return new $alias($params);
        }
    }
}
class GearBundle
{
    static $locators = array();
    static $userRootDirectory;
    static $engineRootDirectory;

    /**
     * @param $locator IGearBundleLocator
     *
     * @throws GearInvalidOperationException
     */
    public static function registerLocator($locator)
    {
        if (!($locator instanceof IGearBundleLocator)) {
            throw new GearInvalidOperationException();
        }
        self::$locators[] = $locator;
    }

    /**
     * @param $module string
     * @param bool|true $require
     * @param bool|true $once
     * @return mixed
     */
    public static function prob($module, $require = true, $once = true)
    {
        $userRoot = self::$userRootDirectory;
        $engineRoot = self::$engineRootDirectory;

        $firstBkSlash = stripos($module, '\\');
        if ($firstBkSlash > 0) {
            $noRoot = str_replace('\\', '/', substr($module, $firstBkSlash));

            $path = "$userRoot/$noRoot.php";
            if (!file_exists($path)) {
                $path = "$engineRoot/$noRoot.php";
                if (!file_exists($path)) {
                    $path = null;
                }
            }
            if ($path != null) {
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
        }

        $path = "$userRoot/$module.php";
        if (!file_exists($path)) {
            $path = "$engineRoot/$module.php";
            if (!file_exists($path)) {
                $path = null;
            }
        }
        if ($path != null) {
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

        foreach (self::$locators as $locator) {
            if ($locator->tryLocate($module, $require, $once)) {
                break;
            }
        }
    }

    public static function resolvePhar($phar)
    {

    }
    
    public static function dependency($module)
    {
        self::prob($module);
    }

    public static function resolvePackage($module)
    {
        $root = self::$userRootDirectory;
        $path = "$root/$module";
        if (file_exists("$path.php")) {
            require_once("$path.php");
        } elseif (file_exists("$path.phar")) {
            self::resolvePhar("$path.phar");
        } else {
            $root = self::$engineRootDirectory;
            $path = "$root/$module";

            if (file_exists("$path.php")) {
                require_once("$path.php");
            } elseif (file_exists("$path.phar")) {
                self::resolvePhar("$path.phar");
            } else {
                throw new GearInvalidOperationException("File '$module' not found.");
            }
        }
    }

    public static function resolveAllPackages($modules)
    {
        if (count($modules) == 0) return;
        $root = self::$engineRootDirectory;

        foreach ($modules as $module) {
            self::resolvePackage($module);
        }
    }

    public static function resolveAllPackageFromDirectory($path)
    {
        $root = self::$engineRootDirectory;
        $dI = new \RecursiveDirectoryIterator("$root/$path");

        foreach (new \RecursiveIteratorIterator($dI) as $file) {
            $fileName = $file->getFilename();
            if ($fileName == '.' || $fileName == '..') continue;
            self::resolvePackage($file->getPathname());
        }
    }

    public static function resolveUserModule($module, $require = true, $once = true)
    {
        $root = self::$userRootDirectory;
        $path = "$root/$module";

        if ($require) {
            if (!file_exists($path)) {
                throw new GearInvalidOperationException("File '$module' not found. path: '$path''");
            }
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
            $path = "$root/$module.php";
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
        $dI = new \RecursiveDirectoryIterator("$root/$path");
        if ($require) {
            if ($once) {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
                    require_once($file->getPathname());
                }
            } else {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
                    require($file->getPathname());
                }
            }
        } else {
            if ($once) {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
                    include_once($file->getPathname());
                }
            } else {
                foreach (new \RecursiveIteratorIterator($dI) as $file) {
                    $fileName = $file->getFilename();
                    if ($fileName == '.' || $fileName == '..') continue;
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

        return require_once(__DIR__ . "/gear/arch/pal/general/$module.php");
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

    public static function setEngineDirectory($engineRootDirectory)
    {
        self::$engineRootDirectory = $engineRootDirectory;
    }
}
class GearDefaultActionResolver implements IGearActionResolver
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

        $context->setValue('ActionName', $actionName);

        //$suppliedArgumentss = array();

        $controllerReflection = new \ReflectionClass($controller);
        try {
            $actionReflection = $controllerReflection->getMethod($actionName);
        } catch(\Exception $ex) {
            throw new \GearHttpNotFoundException("Action '$actionName' not found.");
        }
        $actionParameters = $actionReflection->getParameters();

        $controller->beginExecute($context);

        $controller->checkExecution($context);

        try {
            $response = $context->getResponse();

            $result = self::_execAction(
                $context,
                $mvcContext,
                $controller,
                $controllerReflection,
                $actionReflection,
                $request,
                $response,
                $actionName,
                null,//$suppliedArgumentss,
                $actionParameters);

            self::_executeActionResult(
                $context,
                $request,
                $response,
                $result);

        } catch (\Exception $ex) {
            $controller->onExceptionOccurred($context, $ex);
            throw $ex;
        }

        $controller->endExecute($context);

        return true;
    }

    /**
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param GearController $controller
     * @param \ReflectionClass $controllerReflection
     * @param \ReflectionMethod $actionReflection
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param string $actionName
     * @param mixed $args
     * @param mixed $actionParameters
     * @return mixed
     * @throws GearInvalidOperationException
     */
    public static function _execAction(
        $context,
        $mvcContext,
        $controller,
        $controllerReflection,
        $actionReflection,
        $request,
        $response,
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
            if (!isset($args)) {
                $args = array_merge($mvcContext->getParams(), $context->getRequest()->getAllValues());
            }
            //if (!isset($args)) $args = $context->getRequest()->getAllValues();
            $actionArgs = array();
            foreach ($actionParameters as $p) {
                /** @var $p \ReflectionParameter */
                $value = null;
                if (GearHelpers::TryGetArrayElementByNameCaseInSensetive($args, $p->getName(), $value)) {
                    $actionArgs[] = $value;
                } else {
                    try {
                        $class = $p->getClass();
                    } catch (\Exception$ex) {
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
                        if ($p->isArray()) {
                            throw new GearInvalidOperationException("Action '$actionName' argument uses an undefined class type.");
                        } else {
                            $name = $p->getName();
                            if (isset($args[$name])) {
                                $actionArgs[] = $args[$name];
                            }
                        }
                    }
                }
            }
            //$actionArgs = array_merge($actionArgs, $args);
            $result = call_user_func_array([$controller, $actionName], $actionArgs);
        }
        return $result;
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param IGearActionResult $result
     * @throws GearInvalidOperationException
     */
    private static function _executeActionResult($context, $request, $response, $result)
    {
        if (!isset($result)) return;
        do {
            if ($result instanceof IGearActionResult) {
                $inner = $result->getInnerResult();
                $result = $result->executeResult($context, $request, $response);
            } else {
                $inner = null;
                //if(is_object($result)) {
                //    $response->setContentType('application/json');
                //    $response->write(GearSerializer::json($result));
                //} else {
                    $response->write($result);
                //}
            }
            if ($inner instanceof IGearActionResult) {
                if (!($inner instanceof IGearInnerActionResult)) {
                    throw new GearInvalidOperationException('InnerResult must be an instance of IInnerActionResult.');
                }
                self::_executeActionResult($context, $request, $response, $inner);
            }
        } while ($result instanceof IGearActionResult);
    }
}
class GearDefaultViewEngine implements IGearViewEngine
{
    /**
     * @var array Provides probing locations
     */
    protected
        $probLocations = [
        '/:rootarea/:area/views/:controller',
        '/:rootarea/:area/views/:controller/_shared',
        '/:rootarea/:area/views/:shared',
        '/:rootarea/:area/views',
        '/views/:controller',
        '/views/:controller/_shared',
        '/views/:shared',
        '/views',
    ];

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
            $this,
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
                if ($r instanceof IGearActionResult) {
                    $result[] = $r;
                }
            }
        }
        if (sizeof($result) > 0) {
            return new GearBatchActionResult($result);
        }

        return $execResult;
    }

    public function renderPartialView(
        $context,
        $controller,
        $partialViewName,
        $model
    )
    {
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();
        $controllerName = $mvcContext->getControllerName();

        $execResult = self::_renderView(
            $this,
            0,
            $context,
            $mvcContext,
            $controllerName,
            $controller,
            $partialViewName,
            $model,
            false);

        $result = array();
        if (is_array($execResult)) {
            foreach ($execResult as $r) {
                if ($r instanceof IGearActionResult) {
                    $result[] = $r;
                }
            }
        }
        if (sizeof($result) > 0) {
            return new GearBatchActionResult($result);
        }

        return $execResult;
    }

    /**
     * @param $viewEngine IGearViewEngine
     * @param $indent int
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $controllerName string
     * @param $controller GearController
     * @param $viewName string
     * @param $model mixed
     * @param $useLayout bool
     *
     * @return mixed
     */
    private static function _renderView(
        $viewEngine,
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
        $ext = GearPath::GetExtension($viewPath);
        if ($ext != 'phtml' && $ext != 'php') $viewPath .= '.phtml';
        $viewPath = GearPath::GetUseablePath(GearPath::Combine($viewRoot, $controllerName, $viewPath));

        $layout = $useLayout == true ? $controller->layout : null;
        $viewContent = self::_executeView(
            $config,
            $context,
            $mvcContext,
            $viewEngine,
            $viewPath,
            $viewName,
            $controller,
            $controller->dataBag,
            $controller->getHtml(),
            $controller->getUrl(),
            $controller->helper,
            $useLayout,
            $layout,
            $model,
            $result);
        //ActionResult::ExecuteActionResult($context, $result);

        if (isset($layout)) {
            //$controller->layout = null;

            $output = $context->getService(Gear_ServiceViewOutputStream);
            if ($output == null) {
                $output = new GearHtmlStream();
            }
            $output->write($viewContent);
            $context->registerService(Gear_ServiceViewOutputStream, $output);

            self::_renderView(
                $viewEngine,
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

    protected static function checkFileExists(&$path)
    {
        if (file_exists($path)) {
            if(filetype($path) != 'dir') {
                return true;
            }
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

    /**
     * @param $config GearConfiguration
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $viewEngine IGearViewEngine
     * @param $rootPath string
     * @param $viewName string
     *
     * @return null|string
     *
     * @throws GearViewFileNotFoundException
     */
    protected static function probView(
        $config,
        $context,
        $mvcContext,
        $viewEngine,
        $rootPath,
        $viewName)
    {
        $areaRoot = $config->getValue(Gear_Key_AreaRoot, Gear_Section_View, Gear_DefaultAreasRootPath);
        $area = $mvcContext->getAreaName();
        $controller = $mvcContext->getControllerName();
        $action = $mvcContext->getActionName();
        $shared = $config->getValue(Gear_Key_SharedView, Gear_Section_View, Gear_DefaultSharedRootPath);

        $found = false;
        $viewPath = null;
        $searchedLocs = array();
        foreach ($viewEngine->probLocations as $location) {
            if(!$area) {
                if(stripos($location, ':area')) {
                    continue;
                }
            }
            $location = str_replace(':area', $area, $location);
            $location = str_replace(':rootarea', $areaRoot, $location);
            $location = str_replace(':controller', $controller, $location);
            $location = str_replace(':action', $action, $location);
            $location = str_replace(':shared', $shared, $location);

            $viewPath = "$location/$viewName";
            if (!self::checkFileExists($viewPath)) {
                $dblCheck = getcwd() . '/' . $viewPath;
                if (self::checkFileExists($dblCheck)) {
                    $found = true;
                    $viewPath = $dblCheck;
                    break;
                }
            } else {
                $found = true;
                break;
            }
            $searchedLocs[] = $viewPath;
        }
        if(!$found) {
            throw new GearViewFileNotFoundException($rootPath, " Searched locations were:<br>" . implode('<br>', $searchedLocs));
        }

        return $viewPath;
    }

    /**
     * @param $config GearConfiguration
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $viewEngine IGearViewEngine
     * @param $path string
     * @param $viewName string
     * @param GearController $controller
     * @param $dataBag GearDynamicDictionary
     * @param $html GearHtmlHelper
     * @param $url GearUrlHelper
     * @param $helper GearGeneralHelper
     * @param $layout string
     * @param $model mixed
     * @param $result mixed
     *
     * @return string
     *
     * @throws GearViewFileNotFoundException
     */
    private static function _executeView(
        $config,
        $context,
        $mvcContext,
        $viewEngine,
        $path,
        $viewName,
        $controller,
        $dataBag,
        $html,
        $url,
        $helper,
        $useLayout,
        &$layout,
        &$model,
        &$result)
    {
        $viewPath = self::probView($config, $context, $mvcContext, $viewEngine, $path, $viewName);

        global $Layout, $DataBag, $Model, $Html, $Url, $Helper, $Controller;
        if ($model != null) {
            $Model = $model;
        }
        $layoutBackup = '';
        if ($useLayout) {
            $Layout = $layout;
        } else {
            $layoutBackup = $Layout;
        }
        $DataBag = $dataBag;
        $Html = $html;
        $Url = $url;
        $Helper = $helper;
        $Controller = $controller;

        $level = ob_get_level();
        ob_start();
        $result = require($viewPath);
        $buffer = '';
        while (ob_get_level() > $level)
            $buffer = ob_get_clean() . $buffer;
        //global $Layout;
        if ($useLayout) {
            $layout = $Layout;
        } else {
            $Layout = $layoutBackup;
        }
        return $buffer;
    }
}
class GearErrorResult extends GearActionResultBase
{
    private $error;
    public function __construct($error)
    {
        $this->error = $error;
    }

    public function executeResult($context, $request, $response)
    {
        throw new \Exception($this->error);
    }
}
class GearFxException extends \Exception implements IGearMessageException
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
class GearHttpClient
{
    private
        $url,
        $post,
        $headers,
        $body,
        $requestType;
    
    private $requestExcludedHeaders = [];
    private $responseExcludedHeaders = [];
    
    public $hasReturn = true;
    public $hasReturnHeaders = true;
    public $useSsl = false;
    
    public function __construct(
        $url,
        $body,
        $headers,
        $requestType
    )
    {
        $this->url = $url;
        $this->body = $body;
        $this->headers = $headers;
        $this->requestType = $requestType;
    }

    /**
     * Create a GearHttpClient from GearHttpRequest.
     *
     * @param string $url
     * @param IGearHttpRequest $request
     * @return string
     */
    public static function fromRequest($url, $request)
    {
        return new self(
            $url,
            $request->getBody(),
            $request->getHeaders(),
            $request->getMethod()
        );
    }

    /**
     * Excludes a header from request.
     *
     * @param string $key
     * @return string
     */
    public function excludeRequestHeader($key)
    {
        $this->requestExcludedHeaders[] = $key;
    }
    
    /**
     * Excludes a header from response.
     *
     * @return string
     */
    public function excludeResponseHeader($key)
    {
        $this->responseExcludedHeaders[] = $key;
    }

    /**
     * Add/replace a header to request.
     *
     * @param string $key
     * @param mixed $value
     * @return string
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Execute curl request.
     *
     * @return string
     * @throws \Exception
     */
    public function execute()
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->url);
        //curl_setopt($ch, CURLOPT_POST,  $this->post);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->requestType);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->hasReturn);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($ch, CURLOPT_HEADER, $this->hasReturnHeaders);
        
        if (isDebug()) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }
        
        $requestHeaders = $this->headers;
        
        if ($requestHeaders != null && sizeof($requestHeaders) > 0) {
            $requestHeaders = array_diff_ukey($requestHeaders, array_flip($this->requestExcludedHeaders), 'strcasecmp');
        }
        
        $curlHeaders = [];
        foreach ($requestHeaders as $key => $value) {
            $curlHeaders[] = "$key: $value";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
        
        if ($this->useSsl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        
        $response = curl_exec($ch);    
        //$info = curl_getinfo($ch);
        //\GearLogger::write($info);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        $error = curl_error($ch);
        curl_close($ch);
        if ($response === FALSE)
        {
            if (isDebug()) {
                GearLogger::write($error);
            }
            throw new \Exception($error);
        }
        
        $responseBody = substr($response, $header_size);
        $responseHeaders = $this->hasReturnHeaders ? substr($response, 0, $header_size) : null;
        
        if (isDebug()) {
            GearLogger::write('curl successfull request on '.$this->url);
        }
        
        return [
            'body' => $responseBody,
            'headers' => $responseHeaders
        ];
    }
    
    /**
     * Execute curl request and map result to current response.
     *
     * @return string
     */
    public function executeResponse()
    {
        $result = $this->execute();
        
        $body = $result['body'];
        $rawHeaders = $result['headers'];
        
        $headers = GearGeneralHelper::parseHeaders($rawHeaders);
        $headers = array_diff_ukey($headers, array_flip($this->responseExcludedHeaders), 'strcasecmp');
        
        if ($headers != null) {
            foreach ($headers as $key => $value) {
                header("$key: $value");
            }
        }
        
        echo $body;
        
        return $result;
    }
}
class GearHttpStatusCodeException extends GearFxException
{
    public function __construct($message, $httpStatusCode, $code = 0)
    {
        parent::__construct($message, $httpStatusCode, $code);
    }
}
class GearHttpUnauthorizedException extends GearHttpStatusCodeException
{
    public function __construct($message = null)
    {
        parent::__construct($message == null
            ? 'Unauthorized'
            : $message, 401, 0);
    }
}
class GearInMemoryStream implements IGearOutputStream
{
    private
        $buffer;

    public function write($mixed)
    {
        if (is_string($mixed)) {
            $this->buffer = $this->buffer . $mixed;
        } else {
            $this->buffer = $this->buffer . GearSerializer::stringify($mixed);
        }
    }

    public function clear(){
        $this->buffer = '';
    }

    public function &getBuffer(){
        return $this->buffer;
    }
}
class GearJsonResult extends GearActionResultBase
{
    /** @var mixed */
    protected $content;
    /** @var bool */
    protected $allowGet;

    /**
     * GearJsonResult constructor.
     * @param mixed $content
     * @param bool $allowGet
     */
    public function __construct($content, $allowGet)
    {
        $this->content = $content;
        $this->allowGet = $allowGet;
    }

    public function executeResult($context, $request, $response)
    {
        $method = $request->getMethod();
        $allowGet = $context->getConfig()->getValue(Gear_Key_JsonResultAllowGet, Gear_Section_ActionResolver, false);
        if ($method == 'GET' && !($this->allowGet || $allowGet)) {
            return new GearErrorResult("Action is not configured to serve data as GET http method.");
        }

        $json = $this->createJson($context, $request, $response, $this->content);
        $response->setContentType('application/json');
        $this->writeResult($context, $request, $response, $json);
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param mixed $content
     * @return mixed
     */
    public function createJson($context, $request, $response, $content)
    {
        return $content;
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param string $json
     *
     * @return GearErrorResult
     */
    public function writeResult($context, $request, $response, $json)
    {
        $response->write(GearSerializer::json($json));
    }
}
class GearModel implements IGearModel
{
    public function validate(&$errorDictionary)
    {
        $errorDictionary = array();
        return true;
    }
}
class GearRouteContext
{

}
class GearRouteMvcContext implements IGearMvcContext
{
    private
        $areaName,
        $controllerName,
        $actionName,
        $params;

    public function __construct(
        $areaName,
        $controllerName,
        $actionName,
        $params
    )
    {
        $this->areaName = $areaName;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->params = $params;
    }

    function getAreaName()
    {
        return $this->areaName;
    }

    function getControllerName()
    {
        return $this->controllerName;
    }

    function getActionName()
    {
        return $this->actionName;
    }

    function getParams()
    {
        return $this->params;
    }
}
class GearStatusCodeResult extends GearActionResultBase
{
    protected
        $statusCode,
        $message;

    public function __construct($statusCode, $message = null)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function executeResult($context, $request, $response)
    {
        $response->setStatusCode($this->statusCode);
        $this->writeResult($context, $request, $response);
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     */
    public function writeResult($context, $request, $response)
    {
        if (isset($this->message)) {
            $response->write($this->message);
        }
    }
}
class GearUnauthorizedResult extends GearStatusCodeResult
{
    public function __construct($message = null)
    {
        parent::__construct(401, $message);
    }
}
interface IGearHttpResponse extends IGearOutputStream
{
    function write($mixed);
    function serializeWrite($object, $request);

    function writeInnerStream();

    function setStatusCode($statusCode);
    function setContentType($contentType);
    function setHeader($name, $value);
    function setEncoding($encoding);
}
class GearAppEngineNotFoundException extends GearFxException
{
    public function __construct()
    {
        parent::__construct("Specified app engine not found.", 500);
    }
}
class GearBadRequestResult extends GearStatusCodeResult
{
    public function __construct($message = null)
    {
        parent::__construct(400, $message);
    }
}
class GearEmptyResult extends GearStatusCodeResult
{
    public function __construct($message)
    {
        parent::__construct(204, $message);
    }
}
class GearHtmlStream extends GearInMemoryStream
{

}
class GearHttpForbiddenException extends GearHttpStatusCodeException
{
    public function __construct($message = null)
    {
        parent::__construct($message == null
            ? 'Unauthorized'
            : $message, 403, 0);
    }
}
class GearHttpNotFoundException extends GearHttpStatusCodeException
{
    public function __construct($message = null)
    {
        parent::__construct($message == null
            ? 'Not found'
            : $message, 404, 0);
    }
}
class GearHttpResponse implements IGearHttpResponse
{
    private
        $innerStream;

    /** @var string */
    private $encoding = 'UTF-8';

    /** @var string */
    private $contentType = 'text/html';

    public function __construct()
    {
        $this->innerStream = new GearHtmlStream();
    }

    public function getInnerStream()
    {
        return $this->innerStream;
    }

    public function write($mixed)
    {
        if (is_string($mixed)) {
            echo $mixed;
        } elseif (is_object($mixed) || is_array($mixed)) {
            $this->setContentType('application/json');
            echo GearSerializer::json($mixed);
        } else {
            echo GearSerializer::stringify($mixed);
        }
    }

    public function clear()
    {

    }

    public function serializeWrite($object, $request)
    {
        echo GearSerializer::stringify($object);
    }

    public function writeInnerStream()
    {
        $this->write($this->innerStream->getBuffer());
    }

    public function setStatusCode($statusCode)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        header(Gear_PoweredResponseHeader, true, $statusCode);
    }

    public function setHeader($name, $value)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        header("$name: $value", true);
    }

    public function setContentType($contentType)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        $this->contentType = $contentType;
        $this->_setContentType($contentType, $this->encoding);
    }

    function setEncoding($encoding)
    {
        if (headers_sent()) {
            throw new GearInvalidOperationException();
        }
        $this->encoding = $encoding;
        $this->_setContentType($this->contentType, $encoding);
    }

    private function _setContentType($contentType, $encoding) {
        header("Content-Type: $contentType; charset=$encoding", true);
        //header("Content-Type: $contentType", true);
    }
}
class GearInternalServerErrorResult extends GearStatusCodeResult
{
    /** @var \Exception */
    private $exception;
    /** @var string */
    private $userMessage;

    /**
     * GearInternalServerErrorResult constructor.
     * @param string $message
     * @param \Exception $exception
     */
    public function __construct($message = null, $exception = null)
    {
        $this->exception = $exception;
        $this->userMessage = $message;
        parent::__construct(500, $message);
    }

    public function writeResult($context, $request, $response)
    {
        //parent::writeResult($context, $request, $response);
        $response->setContentType('application/json');

        if (defined('DEBUG')) {
            $response->write(\GearSerializer::json([
                'message' => $this->userMessage,
                'trace' => $this->exception
            ]));
        } else {
            $uuid = uniqid();
            GearLogger::write("$uuid - $this->exception");

            $response->write(\GearSerializer::json([
                'message' => $this->userMessage,
                'traceUid' => $uuid
            ]));
        }
    }
}
class GearNotFoundResult extends GearStatusCodeResult
{
    public function __construct($message = null)
    {
        parent::__construct(404, $message);
    }
}
class GearRedirectResult extends GearStatusCodeResult
{
    /** @var string */
    private $url;

    public function __construct($url, $isPermanent = false)
    {
        $this->url = $url;
        if ($isPermanent) {
            parent::__construct(301, 'Moved Permanently');
        } else {
            parent::__construct(302, 'Found');
        }
    }

    public function writeResult($context, $request, $response)
    {
        return $response->setHeader('Location', $this->url);
    }
}
class GearViewFileNotFoundException extends GearHttpNotFoundException
{
    public function __construct($action, $additionalInfo = null)
    {
        parent::__construct($action == null
            ? "View file not found.$additionalInfo"
            : "View file '$action' not found.$additionalInfo");
    }
}
abstract class GearController extends GearExtensibleClass
{
    /** @var IGearContext */
    protected $context;
    /** @var IGearMvcContext */
    protected $mvcContext;
    /** @var IGearRouteService */
    protected $route;
    /** @var IGearHttpRequest */
    protected $request;
    /** @var IGearHttpResponse */
    protected $response;
    /** @var IGearModelBinderEngine */
    protected $binder;

    /** @var string */
    public $layout;
    /** @var GearDynamicDictionary */
    public $dataBag;
    /** @var GearHtmlHelper */
    private $html;
    /** @var GearUrlHelper */
    private $url;
    /** @var GearGeneralHelper */
    public $helper;

    private
        $beginExecuteHandlers = [],
        $checkExecutionHandlers = [],
        $endExecuteHandlers = [],
        $exceptionHandlers = [];

    /**
     * Creates a controller.
     *
     * @param $context IGearContext
     */
    public function __construct($context)
    {
        parent::__construct(false);

        $this->context = $context;
        $route = $context->getRoute();
        $config = $context->getConfig();
        $mvcContext = $route->getMvcContext();
        $this->route = $route;
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->binder = $context->getBinder();
        $this->mvcContext = $mvcContext;

        $this->layout = $config->getValue(Gear_Key_LayoutName, Gear_Section_View, Gear_DefaultLayoutName);

        $this->dataBag = new GearDynamicDictionary(array());
        $urlHelper = new GearUrlHelper($context, $mvcContext, $route);
        $this->url = $urlHelper;
        $this->html = new GearHtmlHelper($context, $mvcContext, $urlHelper, $this);
    }

    /**
     * @return IGearContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return IGearRouteService
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return IGearHttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return IGearHttpResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return IGearMvcContext
     */
    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    /**
     * @return IGearModelBinderEngine
     */
    public function getBinder()
    {
        return $this->binder;
    }

    /**
     * @return GearUrlHelper
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return GearHtmlHelper
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public function getViewData($key, $defaultValue = null)
    {
        return isset($this->dataBag[$key])
            ? $this->dataBag[$key]
            : $defaultValue;
    }
    /**
     * Checks existence of view data variable.
     *
     * @param string $key
     * @return bool
     */
    public function checkViewData($key)
    {
        return isset($this->dataBag[$key]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setViewData($key, $value)
    {
        $this->dataBag[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool Indicates remove is successful or not (item exists or not).
     */
    public function removeViewData($key)
    {
        if (isset($this->dataBag[$key])) {
            unset($this->dataBag[$key]);
            return true;
        }
        return false;
    }


    /**
     * @param IGearContext $context
     */
    public function beginExecute($context)
    {
        foreach ($this->beginExecuteHandlers as $handler) {
            $handler($context);
        }
    }

    /**
     * @param IGearContext $context
     */
    public function checkExecution($context)
    {
        $this->authorize($context);

        foreach ($this->checkExecutionHandlers as $handler) {
            $handler($context);
        }
    }

    /**
     * @param IGearContext $context
     */
    public function endExecute($context)
    {
        foreach ($this->endExecuteHandlers as $handler) {
            $handler($context);
        }
    }

    /**
     * @param IGearContext $context
     * @param Exception $exception
     */
    public function onExceptionOccurred($context, $exception)
    {
        foreach ($this->exceptionHandlers as $handler) {
            $handler($context, $exception);
        }
    }

    /**
     * @param IGearContext $context
     */
    public function authorize($context)
    {

    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addBeginExecuteHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->beginExecuteHandlers[] = $handler;
    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addEndExecuteHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->endExecuteHandlers[] = $handler;
    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addCheckExecutionHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->checkExecutionHandlers[] = $handler;
    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addExceptionHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->exceptionHandlers[] = $handler;
    }

    /**
     * @param mixed $model
     * @return mixed
     */
    public function bind($model)
    {
        if (!isset($model)) {
            return null;
        }

        $this->binder->fillModelFromContext($model, $this->context, $this, $this->mvcContext);

        return $model;
    }

    //public function LayoutRendering($layout)
    //{
    //}

    /**
     * @param mixed $model
     * @return bool
     */
    public function validateModel($model)
    {
        if ($model == null) {
            return false;
        }
        if (!($model instanceof GearModel)) {
            return false;
        }
        $errors = array();
        $result = boolval($model->validate($errors));
        if ($result == null || !$result) {
            $this->dataBag[Gear_ValidationMessages] = $errors;
        }
        return $result;
    }

    public function validateAntiForgeryToken()
    {
        return GearAntiForgeryTokenManager::validateAntiForgeryToken();
    }

    /**
     * Gets a value from both route values and request parameters.
     *
     * @param string $name
     * @param mixed $defaultValue
     *
     * @return string
     */
    public function getParam($name, $defaultValue = null)
    {
        $params = $this->mvcContext->getParams();
        if (isset($params[$name])) {
            return $params[$name];
        }
        $params = $this->context->getRequest()->getAllValues();
        if (isset($params[$name])) {
            return $params[$name];
        }
        return $defaultValue;
    }


    /**
     * @param mixed $mixed
     * @param bool|false $allowGet
     * @return GearJsonResult
     */
    public function json($mixed, $allowGet = false)
    {
        return new GearJsonResult($mixed, $allowGet);
    }

    /**
     * @param string|null $message
     * @return GearInternalServerErrorResult
     */
    public function serverError($message = null)
    {
        return new GearInternalServerErrorResult($message);
    }

    /**
     * @param string|null $message
     * @return GearBadRequestResult
     */
    public function badRequest($message = null)
    {
        return new GearBadRequestResult($message);
    }

    /**
     * @param string|null $message
     * @return GearNotFoundResult
     */
    public function notFound($message = null)
    {
        return new GearNotFoundResult($message);
    }

    /**
     * @param string|null $message
     * @return GearUnauthorizedResult
     */
    public function unauthorized($message = null)
    {
        return new GearUnauthorizedResult($message);
    }

    /**
     * @param string|null $message
     * @return GearEmptyResult
     */
    public function emptyResult($message = null)
    {
        return new GearEmptyResult($message);
    }

    /**
     * @param string $viewName
     * @param mixed $model
     * @return GearViewResult
     */
    public function view($viewName = null, $model = null)
    {
        return new GearViewResult($this, $viewName, $model);
    }

    /**
     * @param string $viewName
     * @return GearViewResult
     */
    public function viewName($viewName)
    {
        return new GearViewResult($this, $viewName, null);
    }

    /**
     * @param mixed $viewModel
     * @return GearViewResult
     */
    public function viewModel($viewModel)
    {
        return new GearViewResult($this, null, $viewModel);
    }

    /**
     * @param string $actionName
     * @param string|null $controllerName
     * @param string|null $routeParams
     * @return GearRedirectResult
     */
    public function redirectToAction($actionName, $controllerName = null, $routeParams = null, $queryString = null)
    {
        $url = $this->url->action($actionName, $controllerName, $routeParams, $queryString);
        return new GearRedirectResult($url, false);
    }

    /**
     * @param string $actionName
     * @param string|null $controllerName
     * @param string|null $routeParams
     * @return GearRedirectResult
     */
    public function redirectToActionPermanent($actionName, $controllerName = null, $routeParams = null)
    {
        $url = $this->url->action($actionName, $controllerName, $routeParams);
        return new GearRedirectResult($url, true);
    }

    /**
     * @param string $url
     * @return GearRedirectResult
     */
    public function redirectToUrl($url)
    {
        return new GearRedirectResult($url, false);
    }

    /**
     * @param string $url
     * @return GearRedirectResult
     */
    public function redirectToUrlPermanent($url)
    {
        return new GearRedirectResult($url, true);
    }

    /**
     * @param string $fileName
     * @return GearRedirectResult
     */
    public function file($fileName)
    {
        return new GearFileResult($fileName);
    }
}


/* Generals: */
function RenderBody()
{
    $context = GearHttpContext::current();
    if ($context == null) return;

    $output = $context->getService(Gear_ServiceViewOutputStream);
    if($output != null) {
        $response = $context->getResponse();
        if ($response != null) {
            $response->write($output->getBuffer());
        }
    }
}

function isDebug() {
    return defined('DEBUG');
}
GearBundle::setRootDirectory(getcwd());
GearBundle::setEngineDirectory(__DIR__);

