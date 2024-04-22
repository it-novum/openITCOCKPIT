<?php declare(strict_types=1);

namespace App\itnovum\openITCOCKPIT\Perfdata;

use itnovum\openITCOCKPIT\Perfdata\PerformanceDataSetup;
use itnovum\openITCOCKPIT\Core\Views\Service;

abstract class PerformanceDataAdapter {


    /**
     * I will use the given $service and $performanceData to create a PerformanceDataSetup object.
     *
     * ATTENTION: I actually REQUIRE the $performanceData to be passed, since this identifies the exact metric already.
     * Otherwise this method would blow up with additional parameters for the metric and the logic to fech data.
     *
     * @param Service $service
     * @param array $performanceData
     * @return PerformanceDataSetup
     */
    abstract public function getPerformanceData(Service $service, array $performanceData): PerformanceDataSetup;

}
