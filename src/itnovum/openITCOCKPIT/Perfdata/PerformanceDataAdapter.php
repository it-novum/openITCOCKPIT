<?php declare(strict_types=1);

namespace App\itnovum\openITCOCKPIT\Perfdata;

use itnovum\openITCOCKPIT\Perfdata\PerformanceDataSetup;
use itnovum\openITCOCKPIT\Core\Views\Service;

abstract class PerformanceDataAdapter {


    /**
     * @param Service $service
     * @param array $performanceData
     * @return PerformanceDataSetup
     */
    abstract function getPerformanceData(Service $service, array $performanceData = []): PerformanceDataSetup;

}