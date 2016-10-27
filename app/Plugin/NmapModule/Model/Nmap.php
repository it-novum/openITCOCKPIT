<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

require_once ("System.php");
require_once ("NmapException.php");
require_once ("NmapParser.php");

class Nmap extends NmapModuleAppModel{
    /**
     * Location of the nmap binary
     *
     * @var string  $nmap_path
     * @see Net_Nmap::__construct()
     */
    private $_nmap_binary;

    /**
     * Absolute path to store the Nmap XML output file.
     *
     * @var string
     * @see Net_Nmap::__construct()
     */
    private $_output_file = null;

    /**
     * Delete Nmap output file after parsing
     *
     * @var    bool
     */
    private $_delete_output_file = true;

    /**
     * The hostname/IP failed to resolve
     *
     * @var    array
     */
    private $_failed_to_resolve = array();

    /**
     * Nmap option arguments
     *
     * @var array
     * @link http://nmap.org/book/man-briefoptions.html
     */
    private $_nmap_options = array();

    /**
     * Statistics object from Nmap scan
     *
     * @var Net_Nmap_Stats
     */
    private $_nmap_stats = null;

    private $mySystem = null;
    private $myPEAR = null;
    /**
     * Creates a new Nmap object
     *
     * Available options are:
     *
     * - string  nmap_binary:  The location of the Nmap binary.
     *                         If not specified, defaults to '/usr/bin/nmap'.
     *
     * - string  output_file:  Path to store the Nmap XML output file.
     *                         If not specified, a temporary file is created.
     *
     * @param array $options optional. An array of options used to create the
     *                       Nmap object. All options must be optional and are
     *                       represented as key-value pairs.
     */
    public function __construct(array $options = array())
    {
        if($this->mySystem === null){
            $this->mySystem = new System();
        }
        if($this->myPEAR === null){
            $this->myPEAR = new PEAR();
        }
    }

    public function setOptions(array $options = array())
    {
        if (array_key_exists('nmap_binary', $options)) {
            $this->_nmap_binary = (string)$options['nmap_binary'];
        } else {
            $this->_nmap_binary = $this->mySystem->which('nmap');
        }

        if (array_key_exists('output_file', $options)) {
            $this->_output_file = (string)$options['output_file'];
        }
    }
    /**
     * Prepare the command to execute
     *
     * @param array $targets contains hostnames, IP addresses, networks to scan
     *
     * @return string
     */
    protected function createCommandLine($targets)
    {

        if ($this->_output_file === null) {
            $this->_output_file = tempnam( $this->mySystem->tmpdir(), __CLASS__);
        } else {
            $this->_delete_output_file = false;
        }

        $cmd  = $this->_nmap_binary;
        $cmd .= ' ' . implode(' ', $this->_nmap_options);
        $cmd .= ' -oX ' . escapeshellarg($this->_output_file) . ' ';
        foreach ($targets as $target) {
            $cmd .= escapeshellarg($target) . ' ';
        }
        $cmd .= '2>&1';
        if (OS_WINDOWS) {
            $cmd = '"' . $cmd . '"';
        }

        return $cmd;
    }

    /**
     * Scan the specified target
     *
     * @param array $targets   Array contains hostnames, IP addresses,
     *                         networks to scan
     * @param bool  $with_sudo Boolean to enable or not sudo scan
     *
     * @return true | PEAR_Error
     * @throws Net_Nmap_Exception If Nmap binary does not exist or
     *                            the command failed to execute.
     */
    public function scan($targets, $with_sudo = false)
    {
        $sudo = $with_sudo
            ? 'sudo ' : '';
        
        exec($sudo . $this->createCommandLine($targets), $out, $ret_var);

        if ($ret_var > 0) {
            throw new NmapException(implode(' ', $out));
        } else {
            foreach ($out as $row) {
                preg_match(
                    '@^Failed to resolve given hostname/IP:\s+(.+)\.\s+Note@',
                    $row,
                    $matches
                );

                if (count($matches) > 0) {
                    $this->_failed_to_resolve[] = $matches[1];
                }
            }
            return true;
        }
    }

    /**
     * Get all the discovered hosts
     *
     * @param string $output_file Absolute path of the file to parse (optional)
     *
     * @return ArrayIterator      Returns Hosts Object on success.
     * @throws Net_Nmap_Exception If a parsing error occurred.
     */
    public function parseXMLOutput($output_file = null)
    {
        if ($output_file === null) {
            $output_file = $this->_output_file;
        } else {
            $this->_delete_output_file = false;
        }
        $parse = new NmapParser();
        $parse->setInputFile($output_file);
        $parse->folding = false;

        $res = $parse->parse();
        if ($this->myPEAR->isError($res)) {
            throw new NmapException($res);
        }
        if ($this->_delete_output_file) {
            unlink($this->_output_file);
        }
        $this->_nmap_stats = $parse->getStats();
        return $parse->getHosts();
    }

    /**
     * Get all the hostnames/IPs failed to resolve during scanning operation
     *
     * @return Array    Returns array
     */
    public function getFailedToResolveHosts()
    {
        return $this->_failed_to_resolve;
    }

    /**
     * Get Nmap Statistics
     *
     * @return Net_Nmap_Stats   Return an Nmap Stats Object
     */
    public function getNmapStats()
    {
        return $this->_nmap_stats;
    }

    /**
     * Enable Nmap options
     * Available nmap options are:
     *
     * - boolean os_detection: Enable the OS detection (-O).
     * - boolean service_info: Probe open ports to determine
     *                         service/version info (-sV)
     * - string  port_ranges : Port ranges, only scan
     *                         specified ports (-p <port ranges>)
     *                         Ex: 22; 1-65535; U:53,111,137,T:21-25,80,139,8080
     * - boolean all_options : Enables OS detection and Version detection,
     *                         Script scanning and Traceroute (-A)
     *
     * @param array $nmap_options Nmap options to enable
     *
     * @return void
     * @link http://nmap.org/book/man-briefoptions.html
     * @throws Net_Nmap_Exception If the option argument is not valid.
     */
    public function enableOptions($nmap_options)
    {
        $host_timeout = array_key_exists('host-timeout', $nmap_options);
        $enable_os_detection = array_key_exists('os_detection', $nmap_options);
        $enable_service_info = array_key_exists('service_info', $nmap_options);
        $enable_fast_scan = array_key_exists('fast_scan', $nmap_options);
        $enable_port_ranges  = array_key_exists('port_ranges', $nmap_options);
        $enable_all_options  = array_key_exists('all_options', $nmap_options);

        if($host_timeout){
            $this->_nmap_options[] = '--host-timeout '.$nmap_options['host-timeout'];
        }

        if ($enable_os_detection && $nmap_options['os_detection']) {
            $this->_nmap_options[] = '-O';
        }
        if ($enable_service_info && $nmap_options['service_info']) {
            $this->_nmap_options[] = '-sV';
        }
        if ($enable_fast_scan && $nmap_options['fast_scan']) {
            $this->_nmap_options[] = '-F';
        }elseif ($enable_port_ranges) {
            $port_ranges    = $nmap_options['port_ranges'];
            $allowed_format = '([U,T]\:)*[0-9]+(-[0-9]+)*';

            $regexp = '/^' . $allowed_format . '(,' . $allowed_format . ')*$/';
            if (preg_match($regexp, $port_ranges)) {
                $this->_nmap_options[] = '-p ' . $port_ranges;
            } else {
                throw new NmapException('Port ranges: not valid format.');
            }
        }
        if ($enable_all_options && $nmap_options['all_options']) {
            $this->_nmap_options[] = '-A';
        }
    }
}