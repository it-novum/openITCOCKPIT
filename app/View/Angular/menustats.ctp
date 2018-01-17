<div class="pull-right" style="padding-right: 25px;">
    <ol class="menustats">
        <li>
            <a href="/hosts/index<?php echo Router::queryString([
                'sort' => 'Hoststatus.last_state_change',
                'direction' => 'desc'
            ]); ?>" style="color:#bbb;">
                <i class="fa fa-hdd-o fa-lg"></i>
            </a>
        </li>
        <li>
            <a href="/hosts/index<?php echo Router::queryString([
                'filter' => [
                    'Hoststatus.current_state' => [1 => 'down']
                ],
                'sort' => 'Hoststatus.last_state_change',
                'direction' => 'desc'
            ]); ?>" class="btn btn-danger btn-xs">
                {{ hoststatusCount['1'] }}
            </a>
        </li>
        <li>
            <a href="/hosts/index<?php echo Router::queryString([
                'filter' => [
                    'Hoststatus.current_state' => [2 => 'unreachable']
                ],
                'sort' => 'Hoststatus.last_state_change',
                'direction' => 'desc'
            ]); ?>" class="btn btn-default btn-xs">
                {{ hoststatusCount['2'] }}
            </a>
        </li>
    </ol>
    <ol class="menustats">
        <li>
            <a href="/services/index<?php echo Router::queryString([
                'sort' => 'Servicestatus.last_state_change',
                'direction' => 'desc'
            ]); ?>" style="color:#bbb;">
                <i class="fa fa-cog fa-lg"></i>
            </a>
        </li>
        <li>
            <a href="/services/index<?php echo Router::queryString([
                'filter' => [
                    'Servicestatus.current_state' => [1 => 'warning']
                ],
                'sort' => 'Servicestatus.last_state_change',
                'direction' => 'desc'
            ]); ?>" class="btn btn-warning btn-xs">
                {{ servicestatusCount['1'] }}
            </a>
        </li>
        <li>
            <a href="/services/index<?php echo Router::queryString([
                'filter' => [
                    'Servicestatus.current_state' => [2 => 'critical']
                ],
                'sort' => 'Servicestatus.last_state_change',
                'direction' => 'desc'
            ]); ?>" class="btn btn-danger btn-xs">
                {{ servicestatusCount['2'] }}
            </a>
        </li>
        <li>
            <a href="/services/index<?php echo Router::queryString([
                'filter' => [
                    'Servicestatus.current_state' => [3 => 'unknown']
                ],
                'sort' => 'Servicestatus.last_state_change',
                'direction' => 'desc'
            ]); ?>" class="btn btn-default btn-xs">
                {{ servicestatusCount['3'] }}
            </a>
        </li>
    </ol>
</div>