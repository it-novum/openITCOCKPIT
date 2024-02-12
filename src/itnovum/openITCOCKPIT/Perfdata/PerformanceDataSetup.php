<?php declare(strict_types=1);

namespace itnovum\openITCOCKPIT\Perfdata;

class PerformanceDataSetup {
    /** @var Metric */
    public $metric;
    /** @var Scale */
    public $scale;
    /** @var Threshold */
    public $warn;
    /** @var Threshold */
    public $crit;

    /**
     * I'll return the object in an array base representation.
     * @return array
     */
    public function toArray(): array {
        return [
            'metric' => $this->metric->toArray(),
            'scale'  => $this->scale->toArray(),
            'warn'   => $this->warn->toArray(),
            'crit'   => $this->crit->toArray()
        ];
    }
}