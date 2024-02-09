<?php declare(strict_types=1);

namespace itnovum\openITCOCKPIT\Perfdata;


class ScaleType {
    /**
     * 🟦
     * @var string
     */
    public const O = 'O';
    /**
     * 🟨🟩
     * @var string
     */
    public const W_O = 'W<O';
    /**
     * 🟥🟨🟩
     * @var string
     */
    public const C_W_O = 'C<W<O';
    /**
     * 🟩🟨
     * @var string
     */
    public const O_W = 'O<W';
    /**
     * 🟩🟨🟥
     * @var string
     */
    public const O_W_C = 'O<W<C';
    /**
     * 🟥🟨🟩🟨🟥
     * @var string
     */
    public const C_W_O_W_C = 'C<W<O<W<C';
    /**
     * 🟩🟨🟥🟨🟩
     * @var string
     */
    public const O_W_C_W_O = 'O<W<C<W<O';

    /**
     *
     */
    public const ALL = [
        self::O,
        self::W_O,
        self::C_W_O,
        self::O_W,
        self::O_W_C,
        self::C_W_O_W_C,
        self::O_W_C_W_O,
    ];

    private static function validateOrder($a) {
        $last = null;
        $values = func_get_args();
        foreach ($values as $value) {
            if ($value === null) {
                return false;
            }

            if ($value >= $last) {
                $last = $value;
            } else {
                return false;
            }
        }
        return true;
    }

    public static function get(bool $invert, Threshold $warn, Threshold $crit): string {
        if (false) {
            // Just for legibility
        } else if ($invert && self::validateOrder($crit->low, $warn->low, $warn->high, $crit->high)) {
            return ScaleType::O_W_C_W_O;
        } else if (!$invert && self::validateOrder($crit->low, $warn->low, $warn->high, $crit->high)) {
            return ScaleType::C_W_O_W_C;
        } else if (self::validateOrder($warn->low, $crit->low)) {
            return ScaleType::O_W_C;
        } else if (self::validateOrder($crit->low, $warn->low)) {
            return ScaleType::C_W_O;
        } else if ($warn->high && $crit->high === null) {
            return ScaleType::O_W;
        } else if ($warn->low && $crit->low === null) {
            return ScaleType::W_O;
        } else if ($warn->low === null && $warn->high === null && $crit->low === null && $crit->high === null) {
            return ScaleType::O;
        }

        throw new \InvalidArgumentException("This setup is unknown to me. See details.");
    }
}