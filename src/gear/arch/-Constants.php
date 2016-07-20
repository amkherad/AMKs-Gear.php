<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

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

/*</module>*/