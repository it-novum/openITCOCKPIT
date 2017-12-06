<div class="pull-right" style="padding-right: 25px;">
    <ol class="menustats">
        <li>
            <a href="<?php echo Router::url([
                'controller'                         => 'hosts',
                'action'                             => 'index',
                'plugin'                             => '',
                'Filter.Hoststatus.current_state[1]' => 1,
                'Filter.Hoststatus.current_state[2]' => 1,
                'sort'                               => 'Hoststatus.last_state_change',
                'direction'                          => 'desc'
            ]); ?>" style="color:#bbb;">
                <i class="fa fa-hdd-o fa-lg"></i>
            </a>
        </li>
        <li>
            <a href="<?php echo Router::url([
                'controller'                         => 'hosts',
                'action'                             => 'index',
                'plugin'                             => '',
                'Filter.Hoststatus.current_state[1]' => 1,
                'sort'                               => 'Hoststatus.last_state_change',
                'direction'                          => 'desc'
            ]); ?>" class="btn btn-danger btn-xs">
                {{ hoststatusCount['1'] }}
            </a>
        </li>
        <li>
            <a href="<?php echo Router::url([
                'controller'                         => 'hosts',
                'action'                             => 'index',
                'plugin'                             => '',
                'Filter.Hoststatus.current_state[2]' => 1,
                'sort'                               => 'Hoststatus.last_state_change',
                'direction'                          => 'desc'
            ]); ?>" class="btn btn-default btn-xs">
                {{ hoststatusCount['2'] }}
            </a>
        </li>
    </ol>
    <ol class="menustats">
        <li>
            <a href="<?php echo Router::url([
                'controller'                            => 'services',
                'action'                                => 'index',
                'plugin'                                => '',
                'Filter.Servicestatus.current_state[1]' => 1,
                'Filter.Servicestatus.current_state[2]' => 1,
                'Filter.Servicestatus.current_state[3]' => 1,
                'sort'                                  => 'Servicestatus.last_state_change',
                'direction'                             => 'desc'
            ]); ?>" style="color:#bbb;">
                <i class="fa fa-cog fa-lg"></i>
            </a>
        </li>
        <li>
            <a href="<?php echo Router::url([
                'controller'                            => 'services',
                'action'                                => 'index',
                'plugin'                                => '',
                'Filter.Servicestatus.current_state[1]' => 1,
                'sort'                                  => 'Servicestatus.last_state_change',
                'direction'                             => 'desc'
            ]); ?>" class="btn btn-warning btn-xs">
                {{ servicestatusCount['1'] }}
            </a>
        </li>
        <li>
            <a href="<?php echo Router::url([
                'controller'                            => 'services',
                'action'                                => 'index',
                'plugin'                                => '',
                'Filter.Servicestatus.current_state[2]' => 1,
                'sort'                                  => 'Servicestatus.last_state_change',
                'direction'                             => 'desc'
            ]); ?>" class="btn btn-danger btn-xs">
                {{ servicestatusCount['2'] }}
            </a>
        </li>
        <li>
            <a href="<?php echo Router::url([
                'controller'                            => 'services',
                'action'                                => 'index',
                'plugin'                                => '',
                'Filter.Servicestatus.current_state[3]' => 1,
                'sort'                                  => 'Servicestatus.last_state_change',
                'direction'                             => 'desc'
            ]); ?>" class="btn btn-default btn-xs">
                {{ servicestatusCount['3'] }}
            </a>
        </li>
    </ol>
</div>