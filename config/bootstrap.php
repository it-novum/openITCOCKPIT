<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

require __DIR__ . '/version.php';

if (!defined('PLUGIN')) {
    define('PLUGIN', ROOT . DS . 'plugins' . DS);
}

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use App\Lib\Constants;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Database\TypeFactory;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

//Define openITCOCKPIT constants
$Constnats = new Constants();

/*
 * See https://github.com/josegonzalez/php-dotenv for API details.
 *
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.example to `config/.env` and set/modify the
 * variables as required.
 *
 * The purpose of the .env file is to emulate the presence of the environment
 * variables like they would be present in production.
 *
 * If you use .env files, be careful to not commit them to source control to avoid
 * security risks. See https://github.com/josegonzalez/php-dotenv#general-security-information
 * for more information for recommended practices.
*/
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
    Configure::load('email', 'default', false);
    Configure::load('datasource', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

if (!defined('ENVIRONMENT')) {
    if (Configure::read('debug') === true) {
        define('ENVIRONMENT', 'development');
    } else {
        define('ENVIRONMENT', 'production');
    }
}


/*
 * Load an environment local configuration file to provide overrides to your configuration.
 * Notice: For security reasons app_local.php will not be included in your git repo.
 */
if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
    // disable router cache during development
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ErrorTrap(Configure::read('Error')))->register();
} else {
    (new ExceptionTrap(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Email::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * Setup detectors for mobile and tablet.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/4/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
TypeFactory::build('time');
TypeFactory::build('date');
TypeFactory::build('datetime');
TypeFactory::build('timestamp');

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);
//Inflector::rules('transliteration', ['/å/' => 'aa']);


//Configure CakePHP and WkHtmlToPdf
//default path of apt-get install wkhtmltopdf (Mostly old and broken)
$WkHtmlToPdfBinary = '/usr/bin/wkhtmltopdf';
if (file_exists('/usr/local/bin/wkhtmltopdf')) {
    //Installed from deb file from https://wkhtmltopdf.org/ (newer version and working)
    $WkHtmlToPdfBinary = '/usr/local/bin/wkhtmltopdf';
}
Configure::write('CakePdf', [
    'engine' => [
        'className' => 'CakePdf.WkHtmlToPdf',
        // Mac OS X / Linux is usually like:
        'binary'    => $WkHtmlToPdfBinary,
        // On Windows environmnent you NEED to use the path like
        // old fashioned MS-DOS Paths, otherwise you will keep getting:
        // WKHTMLTOPDF didn't return any data
        // 'binary' => 'C:\\Progra~1\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
        // 'cwd' => 'C:\\Progra~1\\wkhtmltopdf\\bin',
        'options'   => [
            'print-media-type'         => true,
            'outline'                  => true,
            'dpi'                      => 96,
            'enable-local-file-access' => true,
        ],
    ],
]);
