<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\app;

use gear\arch\core\AppContext;
use gear\arch\app\AppEngineNotFoundException;
use \Exception;

/*</namespaces>*/

/*<bundles>*/
Bundle::Arch('app/AppEngineNotFoundException');
/*</bundles>*/

/*<module>*/

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

/*</module>*/
?>