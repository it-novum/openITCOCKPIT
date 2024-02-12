<?php declare(strict_types=1);

namespace App\itnovum\openITCOCKPIT\Perfdata;

use itnovum\openITCOCKPIT\Perfdata\Metric;
use itnovum\openITCOCKPIT\Perfdata\PerformanceDataSetup;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Perfdata\Scale;
use itnovum\openITCOCKPIT\Perfdata\ScaleType;
use itnovum\openITCOCKPIT\Perfdata\Threshold;

final class NagiosAdapter extends PerformanceDataAdapter {


    /**
     * @inheritDoc
     */
    function getPerformanceData(Service $service, array $performanceData = []): PerformanceDataSetup {
        $warn = $performanceData['warn'] ?? $performanceData['warning'] ?? '';
        $crit = $performanceData['crit'] ?? $performanceData['critical'] ?? '';

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
        $scaleMin = isset($performanceData['min']) ? (float)$performanceData['min'] : min($warnLo, $warnHi, $critLo, $critHi);
        $scaleMax = isset($performanceData['max']) ? (float)$performanceData['max'] : max($warnLo, $warnHi, $critLo, $critHi);
        $current  = (float)($performanceData['act'] ?? $performanceData['current']);
        $unit     = (string)$performanceData['unit'];
        $name     = (string)($performanceData['name'] ?? $performanceData['metric']);

        // Create Setup
        $setup              = new PerformanceDataSetup();
        $setup->metric      = new Metric($current, $unit, $name);
        $setup->warn        = new Threshold($warnLo, $warnHi);
        $setup->crit        = new Threshold($critLo, $critHi);
        $setup->scale       = new Scale($scaleMin, $scaleMax, ScaleType::O);
        $setup->scale->type = ScaleType::get($invert, $setup->warn, $setup->crit);
        return $setup;
    }

}