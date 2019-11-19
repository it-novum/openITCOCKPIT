<?php
Router::redirect('/', '/ng/#!/dashboards/index');
//Router::redirect('/', '/ng/#!/dashboards/index', ['status' => 302]);
//Router::connect('/', ['controller' => 'angular', 'action' => 'index', 'dashboards']);
Router::connect('/ng', ['controller' => 'angular', 'action' => 'index']);
Router::connect('/widget/:plugin/:controller/:action/*', ['widget' => true]);
Router::connect('/widget/:controller/:action/*', ['widget' => true]);

//REST API settings
Router::mapResources([
    'containers',
    'tenants',
    'commands',
    'timeperiods',
    'contacts',
    'contactgroups',
    'hosts',
    'hostgroups',
    'services',
    'servicegroups',
    'hostescalations',
    'serviceescalations',
    'hostdependencies',
    'servicedependencies',
    'locations',
    'servicetemplates',
    'hosttemplates',
    'users',
    'calendars',
    'servicetemplategroups',
    'MapModule.maps',
    'MapModule.mapeditors',
    'EventcorrelationModule.eventcorrelations',
    'AutoreportModule.autoreports',
    'MkModule.mkservicedata',
]);

// Caution: Do NOT mix controller names with those controller names from plugins! It's a bug and may not work.
// Alternatively use `Router::mapResources('')` multiple times but without passing an array as argument.

Router::mapResources([
    'distribute_module.satellites',
]);

Router::resourceMap('login', [['action' => 'login', 'method' => 'POST', 'id' => false]]);
//Check out http://book.cakephp.org/2.0/en/development/rest.html and https://chrome.google.com/webstore/detail/advanced-rest-client/hgmloofddffdnphfgcellkdfbfbjeloo?hl=en-US

// Allows .xml und .json extention for view rendering without having a view file.
// Explicitly mentioning `json` and `xml` allows to use the 'Accept' header instead of file extensions in the URL.
Router::parseExtensions();
//Router::setExtensions(['json', 'xml', 'pdf']);

CakePlugin::routes();
require OLD_CAKE . 'Config' . DS . 'routes.php';
