<?php
//$SOURCE_LICENSE$

/*<namespaces>*/
namespace gear\arch\app;
use gear\arch\core\AppContext;
/*</namespaces>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
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
/*</module>*/
?>