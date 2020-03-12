<?php
use App\View\Helper\ButtonGroupHelper;

$html = (new ButtonGroupHelper())
    ->addIconButtonWithSRef('text-primary fas fa-fire', __('New version available!'),'PackageManagerIndex',' ng-show="newVersionAvailable"')
    ->getHtml()
;

echo $html;
