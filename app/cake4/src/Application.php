<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App;

use App\Lib\PluginManager;
use App\Middleware\AppAuthenticationMiddleware;
use App\Policy\RequestPolicy;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface {

    /**
     * {@inheritDoc}
     */
    public function bootstrap(): void {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        $PluginManager = new PluginManager($this);

        $this->addPlugin('Acl');
        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');

        if (PHP_SAPI === 'cli') {
            try {
                $this->addPlugin('Bake');
            } catch (MissingPluginException $e) {
                // Do not halt if the plugin is missing
            }

            $this->addPlugin('Migrations');
        }


        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            //$this->addPlugin(\DebugKit\Plugin::class);
        }
    }

    /**
     * Define the routes for an application.
     *
     * Use the provided RouteBuilder to define an application's routing, register scoped middleware.
     *
     * @param \Cake\Routing\RouteBuilder $routes A route builder to add routes into.
     * @return void
     */
    public function routes($routes): void {
        // Register scoped middleware for use in routes.php
        $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
            'httpOnly' => true
        ]));

        parent::routes($routes);
    }

    /**
     * Returns a service provider instance.
     *
     * @param ServerRequestInterface $request
     * @return AuthenticationServiceInterface
     * @throws \Exception
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface {
        $service = new AuthenticationService([
            //Do not redirect!
            'unauthenticatedRedirect' => null, //'/users/login'
        ]);
        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'email',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password'
        ];
        // Load identifiers
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields
        ]);
        // Load the authenticators, you want session first
        $expireAt = new \DateTime();
        $expireAt->setTimestamp(time() + (3600 * 24 * 31)); // In one month
        $service->loadAuthenticator('Authentication.Cookie', [
            'rememberMeField' => 'remember_me',
            'cookie'          => [
                'expire' => $expireAt
            ]
        ]);
        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator('Authentication.Form', [
            'fields'   => $fields,
            'loginUrl' => '/users/login',
        ]);
        return $service;
    }

    /**
     * @param ServerRequestInterface $request
     * @return AuthorizationServiceInterface
     */
    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface {
        $mapResolver = new MapResolver();
        $mapResolver->map(ServerRequest::class, RequestPolicy::class);
        return new AuthorizationService($mapResolver);
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue): MiddlewareQueue {
        $middlewareQueue
            ->add(new BodyParserMiddleware())

            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(ErrorHandlerMiddleware::class)

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime')
            ]))
            // Add routing middleware.
            // Routes collection cache enabled by default, to disable route caching
            // pass null as cacheConfig, example: `new RoutingMiddleware($this)`
            // you might want to disable this cache in case your routing is extremely simple
            ->add(new RoutingMiddleware($this))

            //Add the authentication middleware
            //Response a 403 to .json requests and redirect .html requests to login page
            ->
            add(new AppAuthenticationMiddleware($this, [
                //Only redirect .html requests if login is invalid - no json requests
                'htmlUnauthenticatedRedirect' => '/users/login'
            ]))
            ->add(new AuthorizationMiddleware($this))
            ->add(new RequestAuthorizationMiddleware());

        return $middlewareQueue;
    }
}
