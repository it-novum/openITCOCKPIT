<?php declare(strict_types=1);

namespace itnovum\openITCOCKPIT\Perfdata;

class Threshold {
    /** @var float|null */
    public $low;
    /** @var float|null */
    public $high;

    public function __construct(?float $low = null, ?float $high = null) {
        $this->low  = $low;
        $this->high = $high;
    }

    public function toArray(): array {
        return [
            'low'  => $this->low,
            'high' => $this->high,
        ];
    }
}