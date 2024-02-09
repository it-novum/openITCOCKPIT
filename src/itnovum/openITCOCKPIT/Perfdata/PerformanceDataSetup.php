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

    /**
     * I will solely take care to create a PerformanceDataSetup instance from the given $performanceData.
     *
     * Please remind that the given $performanceData will only be compatible, if it comes from Nagios.
     * Other implementations may be adapted later on.
     *
     * Also, mind that the given $performanceData may be incomplete.
     *
     * @param array $performanceData
     * @return self
     */
    public static function fromNagios(array $performanceData): self {
        $warn = $performanceData['warn'] ?? '';
        $crit = $performanceData['crit'] ?? '';

        // Check if the inverted threshold is required.
        $invert = false;
        if (substr($warn, 0, 1) === '@' && substr($crit, 0, 1) === '@') {
            $invert = true;
        }
        // Split nagios Thresholds
        $warnArr = explode(':', $warn);
        $critArr = explode(':', $crit);

        // Make better names.
        $warnLo   = isset($warnArr[0]) && strlen($warnArr[0]) > 0 ? (float)str_replace('@', '', $warnArr[0]) : null;
        $warnHi   = isset($warnArr[1]) && strlen($warnArr[1]) > 0 ? (float)str_replace('@', '', $warnArr[1]) : null;
        $critLo   = isset($critArr[0]) && strlen($critArr[0]) > 0 ? (float)str_replace('@', '', $critArr[0]) : null;
        $critHi   = isset($critArr[1]) && strlen($critArr[1]) > 0 ? (float)str_replace('@', '', $critArr[1]) : null;
        $scaleMin = isset($performanceData['min']) ? (float)$performanceData['min'] : -30;
        $scaleMax = isset($performanceData['max']) ? (float)$performanceData['max'] : 30;
        $current  = (float)$performanceData['act'];
        $unit     = (string)$performanceData['unit'];
        $name     = (string)$performanceData['name'];

        // Create Setup
        $setup              = new self();
        $setup->metric      = new Metric($current, $unit, $name);
        $setup->warn        = new Threshold($warnLo, $warnHi);
        $setup->crit        = new Threshold($critLo, $critHi);
        $setup->scale       = new Scale($scaleMin, $scaleMax, ScaleType::O);
        $setup->scale->type = ScaleType::get($invert, $setup->warn, $setup->crit);
        return $setup;
    }
}