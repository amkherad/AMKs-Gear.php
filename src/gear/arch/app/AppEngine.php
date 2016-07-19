<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\app;

use gear\arch\Bundle;
use gear\arch\core\AppContext;
use gear\arch\app\AppEngineNotFoundException;
use \Exception;
use gear\arch\http\HttpRequest;
use gear\arch\Logger;

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

/*</module>*/
?>