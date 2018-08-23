<?php use itnovum\openITCOCKPIT\Core\RFCRouter; ?>

<div class="pull-right" style="padding-right: 25px;">
    <ol class="menustats">
        <?php if ($this->Acl->hasPermission('index', 'Hosts', '')): ?>
            <li>
                <a href="/hosts/index<?php echo RFCRouter::queryString([
                    'sort'      => 'Hoststatus.last_state_change',
                    'direction' => 'desc'
                ]); ?>" style="color:#bbb;">
                    <i class="fa fa-hdd-o fa-lg"></i>
                </a>
            </li>
            <li>
                <a href="/hosts/index<?php echo RFCRouter::queryString([
                    'filter'    => [
                        'Hoststatus.current_state' => [1 => 'down']
                    ],
                    'sort'      => 'Hoststatus.last_state_change',
                    'direction' => 'desc'
                ]); ?>" class="btn btn-danger btn-xs">
                    {{ hoststatusCount['1'] }}
                </a>
            </li>
            <li>
                <a href="/hosts/index<?php echo RFCRouter::queryString([
                    'filter'    => [
                        'Hoststatus.current_state' => [2 => 'unreachable']
                    ],
                    'sort'      => 'Hoststatus.last_state_change',
                    'direction' => 'desc'
                ]); ?>" class="btn btn-default btn-xs">
                    {{ hoststatusCount['2'] }}
                </a>
            </li>
        <?php else: ?>
            <li>
                <i class="fa fa-hdd-o fa-lg"></i>
            </li>
            <li>
                <button class="btn btn-danger btn-xs">
                    {{ hoststatusCount['1'] }}
                </button>
            </li>
            <li>
                <button class="btn btn-default btn-xs">
                    {{ hoststatusCount['2'] }}
                </button>
            </li>
        <?php endif; ?>
    </ol>
    <ol class="menustats">
        <?php if ($this->Acl->hasPermission('index', 'services', '')): ?>
            <li>
                <a href="/services/index<?php echo RFCRouter::queryString([
                    'sort'      => 'Servicestatus.last_state_change',
                    'direction' => 'desc'
                ]); ?>" style="color:#bbb;">
                    <i class="fa fa-cog fa-lg"></i>
                </a>
            </li>
            <li>
                <a href="/services/index<?php echo RFCRouter::queryString([
                    'filter'    => [
                        'Servicestatus.current_state' => [1 => 'warning']
                    ],
                    'sort'      => 'Servicestatus.last_state_change',
                    'direction' => 'desc'
                ]); ?>" class="btn btn-warning btn-xs">
                    {{ servicestatusCount['1'] }}
                </a>
            </li>
            <li>
                <a href="/services/index<?php echo RFCRouter::queryString([
                    'filter'    => [
                        'Servicestatus.current_state' => [2 => 'critical']
                    ],
                    'sort'      => 'Servicestatus.last_state_change',
                    'direction' => 'desc'
                ]); ?>" class="btn btn-danger btn-xs">
                    {{ servicestatusCount['2'] }}
                </a>
            </li>
            <li>
                <a href="/services/index<?php echo RFCRouter::queryString([
                    'filter'    => [
                        'Servicestatus.current_state' => [3 => 'unknown']
                    ],
                    'sort'      => 'Servicestatus.last_state_change',
                    'direction' => 'desc'
                ]); ?>" class="btn btn-default btn-xs">
                    {{ servicestatusCount['3'] }}
                </a>
            </li>
        <?php else: ?>
            <li>
                <i class="fa fa-cog fa-lg"></i>
            </li>
            <li>
                <button class="btn btn-warning btn-xs">
                    {{ servicestatusCount['1'] }}
                </button>
            </li>
            <li>
                <button class="btn btn-danger btn-xs">
                    {{ servicestatusCount['2'] }}
                </button>
            </li>
            <li>
                <button class="btn btn-default btn-xs">
                    {{ servicestatusCount['3'] }}
                </button>
            </li>
        <?php endif; ?>
    </ol>
</div>