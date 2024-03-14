<?php declare(strict_types=1);

namespace itnovum\openITCOCKPIT\Perfdata;

class Metric {
    /** @var float */
    public $value;
    /** @var string */
    public $unit;
    /** @var string */
    public $name;

    public function __construct(?float $value = null, ?string $unit = null, ?string $name = null) {
        $this->value = $value;
        $this->unit = $unit;
        $this->name = $name;
    }

    public function toArray(): array {
        return [
            'value' => $this->value,
            'unit'  => $this->unit,
            'name'  => $this->name
        ];
    }
}