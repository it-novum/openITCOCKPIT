<?php

namespace App\itnovum\openITCOCKPIT\Supervisor;

/**
 * Wrapper class that has the goal to provide simmilar features such as `systemctl status nginx`
 * but via supervisor
 */
class Supervisorctl {

    /**
     * @param string $serviceName
     * @return bool|string
     * @throws ApiException
     */
    public function start(string $serviceName) {
        $SupervisorApi = $this->getSupervisorApiEndpointByServiceName($serviceName);
        return $SupervisorApi->startProcess($serviceName);
    }

    /**
     * @param string $serviceName
     * @return bool|string
     * @throws ApiException
     */
    public function stop(string $serviceName) {
        $SupervisorApi = $this->getSupervisorApiEndpointByServiceName($serviceName);
        return $SupervisorApi->stopProcess($serviceName);
    }

    /**
     * @param string $serviceName
     * @return bool|string
     * @throws ApiException
     */
    public function restart(string $serviceName) {
        $SupervisorApi = $this->getSupervisorApiEndpointByServiceName($serviceName);
        $SupervisorApi->stopProcess($serviceName);
        return $SupervisorApi->startProcess($serviceName);
    }

    /**
     * @param string $serviceName
     * @return array|string
     * @throws ApiException
     */
    public function status(string $serviceName) {
        $SupervisorApi = $this->getSupervisorApiEndpointByServiceName($serviceName);
        return $SupervisorApi->getProcessInfo($serviceName);
    }

    /**
     * @param string $serviceName
     * @return XMLRPCApi
     * @throws \RuntimeException
     */
    public function getSupervisorApiEndpointByServiceName(string $serviceName): XMLRPCApi {
        $username = env('SUPERVISOR_USER', 'supervisord');
        $password = env('SUPERVISOR_PASSWORD', 'password');

        switch ($serviceName) {
            case 'naemon':
                // Tell the Naemon Container to start|stop|restart Naemon
                $url = sprintf(
                    'http://%s:%s/RPC2',
                    env('OITC_NAEMON_HOSTNAME', 'naemon'),
                    env('SUPERVISOR_PORT', 9001)
                );
                $SupervisorApi = new XMLRPCApi($username, $password, $url);
                break;

            default:
                // Service is running in the same container as openITCOCKPIT itslef
                $SupervisorApi = new XMLRPCApi($username, $password, 'http://127.0.0.1:9001/RPC2');
                break;
        }

        if (!isset($SupervisorApi) || !($SupervisorApi instanceof XMLRPCApi)) {
            throw new \RuntimeException('No SupervisorAPI endpoint found for given service_name');
        }

        return $SupervisorApi;
    }

}

