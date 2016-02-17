<?php
//Set the GID to itcockpit group, that we dont get any permission problems in the backgroud
//$group_info = posix_getgrnam('itcockpit');
//posix_setgid($group_info['gid']);

App::uses('Component', 'Controller');
App::uses('ConstantsComponent', 'Controller/Component');
App::uses('PhpReader', 'SilentPhpReader');

$Constants = new ConstantsComponent();

if(php_sapi_name() != 'cli'){
	Cache::config('default', array('engine' => 'File'));
}else{
	Configure::write('Cache.disable', true);
}

CakePlugin::loadAll();

//HtmlPurifier Config
//CakePlugin::load('HtmlPurifier', array('bootstrap' => true));
//$config = HTMLPurifier_Config::createDefault();
//Purifier::config('StandardConfig', $config);

// FIXME: App::uses() doesn't seem to work in this context
require_once APP . 'Lib/AppExceptionRenderer.php';

App::uses('Utils', 'Lib');

// load files from the config directory.
config(
	'Environments',
	'Status',
	'Types'
);


Configure::load('app');
Configure::load('countries');

define('ENVIRONMENT', Environments::detect());

switch(ENVIRONMENT) {
	case Environments::STAGING:
	case Environments::PRODUCTION:
		Configure::write('debug', 0);
		break;
}
//Configure::write('debug', 0);
define('PROTOCOL', (env('HTTPS') ? 'https' : 'http'));

// Enable Debugging on the fly
if(isset($_GET['cdbg']) || env('HTTP_CKDBG') !== null) {
	Configure::write('debug', 2);
}

// Localization settings
Configure::write('Config.language', 'en-us');
setlocale(LC_ALL, 'en_US');
date_default_timezone_set('Europe/Berlin');
Configure::write('Config.timezone', 'Europe/Berlin');

// Add a new option for the reader which does not throw an Exception if the file does not exist.
// Anything else is exactly the same!
App::uses('SilentPhpReader', 'Configure');
Configure::config('silent', new SilentPhpReader());

// Simple function to print multiple data types into the debug log
function dlog() {
	$args = func_get_args();
	foreach($args as $arg) {
		if(!is_string($arg)) {
			$arg = print_r($arg, true);
		}
		CakeLog::write(LOG_DEBUG, print_r($arg, true));
	}
}


Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher',
));

// Logging Setup
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

CakeLog::config('otherFile', array(
	'engine' => 'Database'
));

// Avoid broken permissions for the interface
if(php_sapi_name() == 'cli'){
	// Caching Setup
	//Cache::config('default', array(
	//	'engine' => 'File',
	//	'duration' => '+24hour',
	//	'probability' => 100,
	//	'path' => TMP . 'cache_cli/',
	//	'prefix' => 'app_',
	//	'lock' => false,
	//	'serialize' => true
	//));
    //
	//Cache::config('short', array(
	//	'engine' => 'File',
	//	'duration' => '+1hour',
	//	'probability' => 100,
	//	'path' => TMP . 'cache_cli/',
	//	'prefix' => 'app_',
	//	'lock' => false,
	//	'serialize' => true
	//));
}else{
	// Caching Setup
	Cache::config('default', array(
		'engine' => 'File',
		'duration' => '+24hour',
		'probability' => 100,
		'path' => CACHE,
		'prefix' => 'app_',
		'lock' => false,
		'serialize' => true
	));

	Cache::config('short', array(
		'engine' => 'File',
		'duration' => '+1hour',
		'probability' => 100,
		'path' => CACHE,
		'prefix' => 'app_',
		'lock' => false,
		'serialize' => true
	));
}

/*
	Loading custom Plugin config.php files
*/

$modulePlugins = array_filter(CakePlugin::loaded(), function($value) {
	return strpos($value, 'Module') !== false;
});
foreach($modulePlugins as $pluginName) {
	if(file_exists(ROOT.'/app/Plugin/'.$pluginName.'/Config/config.php')){
		Configure::load($pluginName . '.' . 'config');
	}
}

Configure::load('rrd');
//define('MY_RIGHTS', 0);
define('MY_DATEFORMAT', 'dd.mm.yyyy');
define('PHP_DATEFORMAT', 'd/m/Y');

CakePlugin::load('CakePdf', [
	'bootstrap'=>true,
	'routes' =>true]
);
