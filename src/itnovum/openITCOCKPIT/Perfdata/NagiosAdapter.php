<?php declare(strict_types=1);

namespace App\itnovum\openITCOCKPIT\Perfdata;

use \InvalidArgumentException;
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
        $inverted = false;
        if (substr($warn, 0, 1) === '@' && substr($crit, 0, 1) === '@') {
            $inverted = true;
        }
        // Split nagios Thresholds
        $warnArr = explode(':', $warn);
        $critArr = explode(':', $crit);
        $scaleArray = explode(':', (string)$performanceData['min']);

        // Make better names.
        $warnLo   = isset($warnArr[0]) && strlen($warnArr[0]) > 0 ? (float)str_replace('@', '', $warnArr[0]) : null;
        $warnHi   = isset($warnArr[1]) && strlen($warnArr[1]) > 0 ? (float)str_replace('@', '', $warnArr[1]) : null;
        $critLo   = isset($critArr[0]) && strlen($critArr[0]) > 0 ? (float)str_replace('@', '', $critArr[0]) : null;
        $critHi   = isset($critArr[1]) && strlen($critArr[1]) > 0 ? (float)str_replace('@', '', $critArr[1]) : null;
        $current  = (float)($performanceData['act'] ?? $performanceData['current']);
        $unit     = (string)$performanceData['unit'];
        $name     = (string)($performanceData['name'] ?? $performanceData['metric']);

        // Interprete Scale
        if (is_numeric($scaleArray[0]) && is_numeric($scaleArray[1])) {
            $scaleMin = (float)$scaleArray[0];
            $scaleMax = (float)$scaleArray[1];
        } else {
            $proposeMin = ScaleType::findMin($critHi, $critLo, $warnHi, $warnLo);
            $proposeMax = ScaleType::findMax($critHi, $critLo, $warnHi, $warnLo);
            if ($proposeMax !== null && $proposeMin !== null) {
                $scaleMin = $proposeMin;
                $scaleMax = $proposeMax;
            } else {
                $scaleMin = null;
                $scaleMax = null;
            }
        }

        // Create Setup
        $setup              = new PerformanceDataSetup();
        $setup->metric      = new Metric($current, $unit, $name);
        $setup->warn        = new Threshold($warnLo, $warnHi);
        $setup->crit        = new Threshold($critLo, $critHi);
        $setup->scale       = new Scale($scaleMin, $scaleMax);

        // Fetch the ScaleType. If not working, reset it to the default one.
        try {
            $setup->scale->type = ScaleType::get($inverted, $setup->warn, $setup->crit);
        } catch (InvalidArgumentException $e) {
            // Ignored
        }

        return $setup;
    }

}