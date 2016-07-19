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

/*</module>*/