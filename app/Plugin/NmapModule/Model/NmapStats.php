<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Nmap Stats object
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
 * @author    Ibrahima Barry <ibrahima.br@gmail.com>
 * @copyright 2008 Luca Corbo
 * @license   GNU/LGPL v2.1
 * @since     File available since Release 1.0.4
 * @link      http://pear.php.net/packages/Net_Nmap
 */

//require_once 'Net/Nmap/Exception.php';

/**
 * Nmap Stats object
 *
 * @property string $scanner          Name of Scanner which used
 * @property string $args             Arguments used during the scan
 * @property int    $start            Timestamp of scan starting time
 * @property int    $version          Nmap binary versio
 * @property int    $finished         Timestamp of scan finished time
 * @property int    $hosts_up         Number of hosts which are up
 * @property int    $hosts_down       Number of hosts which are down
 * @property int    $hosts_total      Total of discovered hosts
 * @property int    $xmloutputversion Output XML version
 *
 * @category  Net
 * @package   Net_Nmap
 * @author    Ibrahima Barry <ibrahima.br@gmail.com>
 * @license   GNU/LGPL v2.1
 * @since     File available since Release 1.0.4
 * @link      http://pear.php.net/packages/Net_Nmap
 */
class NmapStats
{
    /**
     * The list of properties managed by __set and __get methods
     *
     * @var    array   $_properties
     */
    private $_properties = array(
        'scanner'          => null,
        'args'             => null,
        'start'            => null,
        'version'          => null,
        'finished'         => null,
        'hosts_up'         => null,
        'hosts_down'       => null,
        'hosts_total'      => null,
        'xmloutputversion' => null
    );

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
        } else {
            throw new NmapException('Trying to get an undefined properties "' .
                $key .
                '" for the object ' .
                __CLASS__);
        }
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
        } else {
            throw new NmapException('Trying to set an undefined properties "' .
                $key .
                '" for the object ' .
                __CLASS__);
        }
    }
}
?>
