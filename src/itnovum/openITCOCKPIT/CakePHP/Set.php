<?php
/**
 * Library of array functions for Cake.
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
 * @package       Cake.Utility
 * @since         CakePHP(tm) v 1.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace itnovum\openITCOCKPIT\CakePHP;

use Cake\Utility\Text;

/**
 * Class used for manipulation of arrays.
 *
 * @package       Cake.Utility
 */
class Set {

    /**
     * Gets a value from an array or object that is contained in a given path using an array path syntax, i.e.:
     * "{n}.Person.{[a-z]+}" - Where "{n}" represents a numeric key, "Person" represents a string literal,
     * and "{[a-z]+}" (i.e. any string literal enclosed in brackets besides {n} and {s}) is interpreted as
     * a regular expression.
     *
     * @param array $data Array from where to extract
     * @param string|array $path As an array, or as a dot-separated string.
     * @return mixed An array of matched items or the content of a single selected item or null in any of these cases: $path or $data are null, no items found.
     * @link https://book.cakephp.org/2.0/en/core-utility-libraries/set.html#Set::classicExtract
     */
    public static function classicExtract($data, $path = null) {
        if (empty($path)) {
            return $data;
        }
        if (is_object($data)) {
            if (!($data instanceof ArrayAccess || $data instanceof Traversable)) {
                $data = get_object_vars($data);
            }
        }
        if (empty($data)) {
            return null;
        }
        if (is_string($path) && strpos($path, '{') !== false) {
            $path = Text::tokenize($path, '.', '{', '}');
        } else if (is_string($path)) {
            $path = explode('.', $path);
        }
        $tmp = [];

        if (empty($path)) {
            return null;
        }

        foreach ($path as $i => $key) {
            if (is_numeric($key) && (int)$key > 0 || $key === '0') {
                if (isset($data[$key])) {
                    $data = $data[$key];
                } else {
                    return null;
                }
            } else if ($key === '{n}') {
                foreach ($data as $j => $val) {
                    if (is_int($j)) {
                        $tmpPath = array_slice($path, $i + 1);
                        if (empty($tmpPath)) {
                            $tmp[] = $val;
                        } else {
                            $tmp[] = Set::classicExtract($val, $tmpPath);
                        }
                    }
                }
                return $tmp;
            } else if ($key === '{s}') {
                foreach ($data as $j => $val) {
                    if (is_string($j)) {
                        $tmpPath = array_slice($path, $i + 1);
                        if (empty($tmpPath)) {
                            $tmp[] = $val;
                        } else {
                            $tmp[] = Set::classicExtract($val, $tmpPath);
                        }
                    }
                }
                return $tmp;
            } else if (strpos($key, '{') !== false && strpos($key, '}') !== false) {
                $pattern = substr($key, 1, -1);

                foreach ($data as $j => $val) {
                    if (preg_match('/^' . $pattern . '/s', $j) !== 0) {
                        $tmpPath = array_slice($path, $i + 1);
                        if (empty($tmpPath)) {
                            $tmp[$j] = $val;
                        } else {
                            $tmp[$j] = Set::classicExtract($val, $tmpPath);
                        }
                    }
                }
                return $tmp;
            } else {
                if (isset($data[$key])) {
                    $data = $data[$key];
                } else {
                    return null;
                }
            }
        }
        return $data;
    }

}
