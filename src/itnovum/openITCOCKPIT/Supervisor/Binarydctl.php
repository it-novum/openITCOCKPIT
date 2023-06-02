<?php

namespace App\itnovum\openITCOCKPIT\Supervisor;

/**
 * Wrapper for the HTTP API interface of the Binaryd micro web server
 * This is used to execute one-shot commands inside a remote container
 */
class Binarydctl {

    /**
     * @param string $serviceName
     * @return BinarydAPI
     * @throws \RuntimeException
     */
    public function getBinarydApiEndpointByServiceName(string $serviceName): BinarydAPI {
        switch ($serviceName) {
            case 'naemon-verify':
            case 'naemon-stats':
                // Tell the Naemon Container run naemon verify or naemon-stats via the binaryd HTTP API
                $url = sprintf(
                    'http://%s:%s/RPC2',
                    env('OITC_NAEMON_HOSTNAME', 'naemon'),
                    9099
                );
                $Binaryd = new BinarydAPI($url);
                break;


            default:
                throw new \RuntimeException("Unknown command");
                break;
        }

        if (!isset($Binaryd) || !($Binaryd instanceof BinarydAPI)) {
            throw new \RuntimeException('No BinarydAPI endpoint found for given service_name');
        }

        return $Binaryd;
    }

}
