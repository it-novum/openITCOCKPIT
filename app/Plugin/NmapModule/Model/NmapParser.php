<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Parses a Nmap XML output file
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

require_once 'XML/Parser2.php';
require_once 'NmapHost.php';
require_once 'NmapService.php';
require_once 'NmapStats.php';

/**
 * Parses a Nmap XML output file
 *
 * @category  Net
 * @package   Net_Nmap
 * @author    Luca Corbo <lucor@ortro.net>
 * @copyright 2008 Luca Corbo
 * @license   GNU/LGPL v2.1
 * @link      http://pear.php.net/packages/Net_Nmap
 * @link      http://nmap.org/data/nmap.dtd
 */
class NmapParser extends XML_Parser2
{
    /**
     * Container for the host objects
     * @var ArrayIterator
     */
    private $_hosts;

    /**
     * Nmap Stats object
     * @var Net_Nmap_Stats
     */
    private $_stats;

    /**
     * start handler
     *
     * @param resource $parser  xml parser resource
     * @param string   $name    element name
     * @param array    $attribs attributes
     *
     * @return void
     */
    public function startHandler($parser, $name, $attribs)
    {
        switch ($name) {
            case 'nmaprun':
                if (empty($this->_stats)) {
                    $this->_stats = new NmapStats();
                }

                $this->_stats->scanner = @$attribs['scanner'];
                $this->_stats->args    = @$attribs['args'];
                $this->_stats->version = @$attribs['version'];
                $this->_stats->start   = @$attribs['start'];
                $this->_stats->xmloutputversion = @$attribs['xmloutputversion'];
                break;
            case 'host':
                if (!$this->_hosts instanceof ArrayIterator) {
                    $this->_hosts = new ArrayIterator();
                }
                $this->_hosts->append(new NmapHost());
                if ($this->_hosts->count() > 1) {
                    $this->_hosts->next();
                }
                $this->_host = $this->_hosts->current();
                break;
            case 'status':
                $this->_host->setStatus($attribs['state']);
                break;
            case 'address':
                $this->_host->addAddress($attribs['addrtype'], $attribs['addr']);
                break;
            case 'hostname':
                $this->_host->addHostname($attribs['name']);
                break;
            case 'port':
                $this->_service = new NmapService();

                $this->_service->protocol = @$attribs['protocol'];
                $this->_service->port     = @$attribs['portid'];
                break;
            case 'state':
                $this->_service->state    = @$attribs['state'];
                break;
            case 'service':
                $this->_service->name      = @$attribs['name'];
                $this->_service->product   = @$attribs['product'];
                $this->_service->version   = @$attribs['version'];
                $this->_service->extrainfo = @$attribs['extrainfo'];
                if (isset($attribs['ostype'])) {
                    $this->_host->addOS('0', $attribs['ostype']);
                }
                break;
            case 'osmatch':
                $this->_host->addOS($attribs['accuracy'], $attribs['name']);
                break;
            case 'finished':
                $this->_stats->finished = @$attribs['time'];
                break;
            case 'hosts':
                $this->_stats->hosts_up     = @$attribs['up'];
                $this->_stats->hosts_down   = @$attribs['down'];
                $this->_stats->hosts_total  = @$attribs['total'];
                break;
            default:
                $this->currentTag = $name;
                break;
        }
    }

    /**
     * end handler
     *
     * @param resource $parser xml parser resource
     * @param string   $name   element name
     *
     * @return void
     */
    public function endHandler($parser, $name)
    {
        switch ($name) {
            case 'port':
                $this->_host->addService($this->_service);
                break;
            default:
                break;
        }

        $this->currentTag = null;
    }

    /**
     * handle character data
     *
     * @param resource $parser xml parser resource
     * @param string   $data   data
     *
     * @return void | true if $data is empty
     */
    public function cdataHandler($parser, $data)
    {
        $data = trim($data);
        if (empty($data)) {
            return true;
        }
    }

    /**
     * Get all the discovered hosts
     *
     * @return ArrayIterator The discovered hosts
     */
    public function getHosts()
    {
        return $this->_hosts;
    }

    /**
     * Get Nmap Statistics
     *
     * @return Net_Nmap_Stats   Return an Nmap Stats Object
     */
    public function getStats()
    {
        return $this->_stats;
    }
}