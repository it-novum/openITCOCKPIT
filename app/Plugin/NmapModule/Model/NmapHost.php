<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Host object
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

/**
 * Host object
 *
 * @category  Net
 * @package   Net_Nmap
 * @author    Luca Corbo <lucor@ortro.net>
 * @copyright 2008 Luca Corbo
 * @license   GNU/LGPL v2.1
 * @link      http://pear.php.net/packages/Net_Nmap
 */
class NmapHost
{

    /**
     * Status of the Host
     * @var string
     */
    private $_status;

    /**
     * Contains all discovered addresses grouped by type
     * Example:
     * Array ([mac]  => Array ([0] => 00:19:E3:07:D5:37)
     *        [ipv4] => Array ([0] => 192.168.1.100
     *                         [1] => 192.168.1.112))
     * @var array
     */
    private $_addresses = array();

    /**
     * Contains all discovered hostnames
     * @var array
     */
    private $_hostnames = array();

    /**
     * Contains the name of the discovered Operating System
     * and the relative accuracy.
     * Elements are sorted by decreasing accuracy
     * @var array
     */
    private $_os = array();

    /**
     * Contains all discovered Service objects
     * @var ArrayIterator
     */
    private $_services;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->_services = new ArrayIterator();
    }

    /**
     * Returns the value of the discovered address
     * By default the first ipv4 address is returned
     *
     * @param string $type  Type of address (i.e. mac, ipv4, ipv6)
     * @param int    $index The index number of the parameter to obtain
     *
     * @return string
     */
    public function getAddress($type = 'ipv4', $index = 0)
    {
        return $this->_addresses[$type][$index];
    }

    /**
     * Add an address to the addresses container
     *
     * @param string $type  Type of address (i.e. mac, ipv4, ipv6)
     * @param string $value Address value
     *
     * @return void
     */
    public function addAddress($type, $value)
    {
        $this->_addresses = array_merge_recursive(
            $this->_addresses,
            array($type => array($value))
        );
    }

    /**
     * Returns the value of the discovered hostname
     * By default the first hostname is returned
     *
     * @param int $index The index number of the parameter to obtain
     *
     * @return string
     */
    public function getHostname($index = 0)
    {
        if (count($this->_hostnames) == 0) {
            return 'unknown';
        }
        return $this->_hostnames[$index];
    }

    /**
     * Add a hostname to the hostnames container
     *
     * @param string $value hostaname value
     *
     * @return void
     */
    public function addHostname($value)
    {
        $this->_hostnames[] = $value;
    }

    /**
     * Add a service object to the services container
     *
     * @param object $service Service object
     *
     * @return void
     */
    public function addService($service)
    {
        $this->_services->append($service);
    }

    /**
     * Returns the discovered services
     *
     * @return ArrayIterator
     */
    public function getServices()
    {
        return $this->_services;
    }

    /**
     * Add an the accuracy ant the OS name to the OS container
     *
     * @param string $accuracy accuracy value
     * @param string $name     OS name value
     *
     * @return void
     */
    public function addOS($accuracy, $name)
    {
        $this->_os[] = array('accuracy'=> $accuracy, 'name' => $name);
    }

    /**
     * Returns the name of discovered OS with the highest accuracy value or
     * the "Too many fingerprint" message if no OS is matched.
     *
     * @return string
     */
    public function getOS()
    {
        if (count($this->_os) == 0) {
            return 'Too many fingerprints match this host to give specific OS details';
        }
        return $this->_os[0]['name'];
    }

    /**
     * Returns the OS container the discovered OS
     * All informations are sorted by decreasing accuracy
     *
     * @return array
     */
    public function getAllOS()
    {
        return $this->_os;
    }

    /**
     * Set the status of the Host
     *
     * @param string $status Host status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * Returns the status of the Host
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }
}
