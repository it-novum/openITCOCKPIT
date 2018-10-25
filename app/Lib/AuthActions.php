<?php

class AuthActions {

    /**
     * Holds the rights config.
     * Format:
     *        'controller' => array(
     *            'action1' => array(role1, role2)
     *        ),
     *        'controller2' => array(
     *            '*' => array('role3')
     *        )
     * @var string
     */
    protected $_rightsConfig = [];

    /**
     * Constructor
     *
     * @param array $rightsConfig The controller-actions/rights configuration
     */
    public function __construct(array $rightsConfig) {
        $this->_rightsConfig = $rightsConfig;
    }

    /**
     * Checks whether the user has access to certain controller action
     *
     * @param array $user
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAuthorized(array $user, $plugin, $controller, $action) {
        $isAuthorized = false;
        if (isset($user['role']) && !empty($controller) && !empty($action)) {
            $controller = Inflector::underscore($controller);

            $key = $controller;
            if (!empty($plugin)) {
                $key = $plugin . '.' . $key;
            }

            if (isset($this->_rightsConfig[$key]['*']) && $this->_rightsConfig[$key]['*'] == '*') {
                $isAuthorized = true;
            } else if (isset($this->_rightsConfig[$key]['*'])
                && in_array($user['role'], $this->_rightsConfig[$key]['*'])
            ) {
                $isAuthorized = true;
            } else if (isset($this->_rightsConfig[$key][$action]) && $this->_rightsConfig[$key][$action] == '*') {
                $isAuthorized = true;
            } else if (isset($this->_rightsConfig[$key][$action])
                && in_array($user['role'], $this->_rightsConfig[$key][$action])
            ) {

                $isAuthorized = true;
            }
        }

        return $isAuthorized;
    }

    /**
     * Checks whether the user is allowed to access a specific URL
     *
     * @param array $user
     * @param array|string $url
     *
     * @return void
     * @author Robert Scherer
     */
    public function urlAllowed(array $user, $url) {
        if (empty($url)) {
            return false;
        }
        if (is_array($url)) {
            $url = Router::url($url);
            // strip off the base path
            $url = Router::normalize($url);
        }
        $route = Router::parse($url);
        if (empty($route['controller']) || empty($route['action'])) {
            return false;
        }

        return $this->isAuthorized($user, $route['controller'], $route['action']);
    }
}