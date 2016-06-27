<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Service object
 *
 * PHP version 5
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330,Boston,MA 02111-1307 USA
 *
 * @category  Net
 * @package   Net_Nmap
 * @author    Luca Corbo <lucor@ortro.net>
 * @copyright 2008 Luca Corbo
 * @license   GNU/LGPL v2.1
 * @link      http://pear.php.net/packages/Net_Nmap
 */

//require_once 'Net/Nmap/Exception.php';

/**
 * Service object
 *
 * @property string $protocol  The protocol type of the service.
 * @property int    $port      The port number where the service is running on.
 * @property string $name      The name of the service.
 * @property string $product   The product information of the service.
 * @property string $version   The version of the product running as service.
 * @property string $extrainfo The additional information about the product
 *                             running as service.
 * @property string $state     Whether the port is open/closed
 *
 * @category  Net
 * @package   Net_Nmap
 * @author    Luca Corbo <lucor@ortro.net>
 * @copyright 2008 Luca Corbo
 * @license   GNU/LGPL v2.1
 * @link      http://www.ortro.net
 */
class NmapService
{
    /**
     * The list of properties managed by __set and __get methods
     *
     * @var    array   $_properties
     */
    private $_properties = array('product' => null,
        'protocol' => null,
        'port' => null,
        'name' => null,
        'version'  => null,
        'extrainfo'  => null,
        'state' => null);

    /**
     * Overloading of the __get method
     *
     * @param string $key The name of the variable that should be retrieved
     *
     * @throws Net_Nmap_Exception If trying to get an undefined properties.
     * @return mixed The value of the object on success
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->_properties)) {
            return $this->_properties[$key];
        }

        throw new NmapException(
            'Trying to get an undefined properties "' .
            $key . '" for the object ' . __CLASS__
        );
    }

    /**
     * Overloading of the __set method
     *
     * @param string $key   The name of the properties that should be set
     * @param mixed  $value parameter specifies the value that the object
     *                      should set the $key
     *
     * @throws Net_Nmap_Exception If trying to set an undefined properties.
     * @return mixed True on success
     */
    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->_properties)) {
            $this->_properties[$key] = $value;
            return true;
        }

        throw new NmapException(
            'Trying to set an undefined properties "' .
            $key . '" for the object ' . __CLASS__
        );
    }
}
