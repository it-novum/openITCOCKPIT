<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * recieves a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Route\InflectedRoute;
use Cake\Routing\RouteBuilder;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */
/** @var \Cake\Routing\RouteBuilder $routes */
// If you need lowercased and underscored URLs while migrating from a CakePHP 2.x application, you can instead use the InflectedRoute class.
// https://book.cakephp.org/3/en/development/routing.html#route-elements
$routes->setRouteClass(InflectedRoute::class);

//CakePHP 4 default
//$routes->setRouteClass(DashedRoute::class);

$routes->setExtensions(['json', 'html', 'pdf', 'png', 'zip']);

$routes->scope('/', function (RouteBuilder $builder) {
    // Register scoped middleware for in scopes.
    $csrf = new CsrfProtectionMiddleware([
        'httponly' => true,
        'secure'   => true
    ]);

    // Token check will be skipped when callback returns `true`.
    $csrf->skipCheckCallback(function ($request) {
        // Skip token check for API URLs.
        if (get_class($request) !== 'Cake\\Http\\ServerRequest') {
            // I'm not sure if $request can be something else because the docs is not clear about this
            return false;
        }

        if (strtolower($request->getParam('controller')) === 'hosts' && $request->getParam('action') === 'index') {
            // Disable CSRF check for /hosts/index ITC-2640
            return true;
        }

        // Keep CSRF checks enabled
        return false;
    });

    $builder->registerMiddleware('csrf', $csrf);

    /*
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
     */
    if (
        (!isset($_SERVER['HTTP_AUTHORIZATION']) || (isset($_SERVER['HTTP_AUTHORIZATION']) && strpos($_SERVER['HTTP_AUTHORIZATION'], 'X-OITC-API') === false)) &&
        (!isset($_SERVER['QUERY_STRING']) || (isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'apikey') === false))) {

        $builder->applyMiddleware('csrf');
    }

    /*
     * Fireup the AngularJS application
     */
    $builder->connect('/', ['controller' => 'Pages', 'action' => 'index']);

    /*
     * Backwards compatibility because Firefox has cached /ng redirect forever
     */
    $builder->connect('/ng', ['controller' => 'Pages', 'action' => 'index']);

    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $builder->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /*
     * Connect catchall routes for all controllers.
     *
     * The `fallbacks` method is a shortcut for
     *
     * ```
     * $builder->connect('/:controller', ['action' => 'index']);
     * $builder->connect('/:controller/:action/*', []);
     * ```
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $builder->fallbacks();
});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
