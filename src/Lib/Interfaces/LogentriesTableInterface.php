<?php


namespace App\Lib\Interfaces;


use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\LogentryFilter;

interface LogentriesTableInterface {

    /**
     * @param LogentryFilter $LogentryFilter
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getLogentries(LogentryFilter $LogentryFilter, $PaginateOMat = null);

}
