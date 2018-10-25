<?php

/**
 * Used for detecting the environment.
 * @package default
 */
class Environments {
    const DEVELOPMENT = 'development';
    const DEVELOPMENT_TEST = 'development_test';
    const STAGING = 'staging';
    const PRODUCTION = 'production';

    /**
     * Returns the current environment
     * @return string
     */
    public static function detect() {
        if (file_exists(__DIR__ . '/../tmp/testing.txt')) {
            $environment = self::DEVELOPMENT_TEST;
        } else if (PHP_SAPI == 'cli') {
            $environment = Environments::_detectCli();
        } else {
            $environment = Environments::_detectHttp();
        }

        return $environment;
    }

    /**
     * Detects the environment when called via Console
     * @return string
     */
    protected static function _detectCli() {
        $environment = self::DEVELOPMENT;
        $uname = php_uname('n');
        if (strpos($uname, 'master') !== false) {
            $environment = self::PRODUCTION;
        } else if (strpos($uname, 'stage') !== false) {
            $environment = self::STAGING;
        }

        return $environment;
    }

    /**
     * Detects the environment when called via HTTP
     * @return string
     */
    protected static function _detectHttp() {
        $host = env('HTTP_HOST');

        if (substr($host, 0, 4) == 'dev.') {
            $environment = self::DEVELOPMENT;
        } else if (substr($host, 0, 4) == 'dev-') {
            $environment = self::DEVELOPMENT;
        } else if (substr($host, 0, 8) == 'staging.') {
            $environment = self::STAGING;
        } else {
            $environment = self::PRODUCTION;
        }

        return $environment;

    }
}