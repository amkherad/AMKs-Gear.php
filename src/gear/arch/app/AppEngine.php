<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\app;

use gear\arch\Bundle;
use gear\arch\core\AppContext;
use gear\arch\core\Configuration;
use gear\arch\core\InvalidOperationException;
use gear\arch\app\AppEngineNotFoundException;
use \Exception;
use gear\arch\http\HttpRequest;
use gear\arch\http\HttpResponse;
use gear\arch\Logger;
use gear\arch\Autoload;
use gear\arch\http\HttpContext;
use gear\arch\InternalServerError;

/*</namespace.current>*/

/*<bundles>*/
Bundle::Arch('app/AppEngineNotFoundException');
Bundle::Arch('-Defaults');
/*</bundles>*/

/*<module>*/

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

/*</module>*/
?>