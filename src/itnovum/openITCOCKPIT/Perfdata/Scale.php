<?php declare(strict_types=1);

namespace itnovum\openITCOCKPIT\Perfdata;

class Scale {
    /** @var float */
    public $min;
    /** @var float */
    public $max;
    /** @var string */
    public $type;

    public function __construct(?float $min = null, ?float $max = null, ?string $type = ScaleType::O) {
        $this->min = $min;
        $this->max = $max;
        $this->type = $type;
    }

    public function toArray(): array {
        return [
            'min'  => $this->min,
            'max'  => $this->max,
            'type' => $this->type
        ];
    }
}