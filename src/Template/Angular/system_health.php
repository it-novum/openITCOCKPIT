<?php

use App\View\Helper\ButtonGroupHelper;

/**
 * @param string $filename
 * @return string
 */
function getBufferedFileContent(string $filename): string {
    ob_start();
    require $filename;
    return ob_get_contents();
}

$menu = getBufferedFileContent('system_health_menu.php');

$btnHelper = (new ButtonGroupHelper('Display of system health notifications'))
    ->addButtonWithTogglingMenu('fas fa-heartbeat {{ class }}', __('System health'))
    ->addRaw($menu)
    ->addButtonWithTooltip('{{systemHealth.errorCount}}', '{{ bgClass }}', __('# of status notifications'));

$html = $btnHelper->getHtml();
echo $html;
ob_get_flush();
