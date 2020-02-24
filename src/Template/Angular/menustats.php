<div class="float-right" style="padding-right: 25px;">
    <ol class="menustats">
        <?php if ($this->Acl->hasPermission('index', 'Hosts', '')): ?>
            <li>
                <a ui-sref="HostsIndex({sort: 'Hoststatus.last_state_change', direction: 'desc'})" style="color:#bbb;">
                    <i class="fa fa-hdd-o fa-lg"></i>
                </a>
            </li>
            <li>
                <a ui-sref="HostsIndex({hoststate: [1], sort: 'Hoststatus.last_state_change', direction: 'desc'})"
                   class="btn btn-danger btn-xs btn-icon">
                    {{ hoststatusCount['1'] }}
                </a>
            </li>
            <li>
                <a ui-sref="HostsIndex({hoststate: [2], sort: 'Hoststatus.last_state_change', direction: 'desc'})"
                   class="btn btn-default btn-xs btn-icon">
                    {{ hoststatusCount['2'] }}
                </a>
            </li>
        <?php else: ?>
            <li>
                <i class="fa fa-hdd-o fa-lg"></i>
            </li>
            <li>
                <button class="btn btn-danger btn-xs btn-icon">
                    {{ hoststatusCount['1'] }}
                </button>
            </li>
            <li>
                <button class="btn btn-default btn-xs btn-icon">
                    {{ hoststatusCount['2'] }}
                </button>
            </li>
        <?php endif; ?>
    </ol>
    <ol class="menustats">
        <?php if ($this->Acl->hasPermission('index', 'services', '')): ?>
            <li>
                <a ui-sref="HostsIndex({sort: 'Hoststatus.last_state_change', direction: 'desc'})" style="color:#bbb;">
                    <i class="fa fa-cog fa-lg"></i>
                </a>
            </li>
            <li>
                <a ui-sref="ServicesIndex({servicestate: [1], sort: 'Servicestatus.last_state_change', direction: 'desc'})"
                   class="btn btn-warning btn-xs btn-icon">
                    {{ servicestatusCount['1'] }}
                </a>
            </li>
            <li>
                <a ui-sref="ServicesIndex({servicestate: [2], sort: 'Servicestatus.last_state_change', direction: 'desc'})"
                   class="btn btn-danger btn-xs btn-icon">
                    {{ servicestatusCount['2'] }}
                </a>
            </li>
            <li>
                <a ui-sref="ServicesIndex({servicestate: [3], sort: 'Servicestatus.last_state_change', direction: 'desc'})"
                   class="btn btn-default btn-xs btn-icon">
                    {{ servicestatusCount['3'] }}
                </a>
            </li>
        <?php else: ?>
            <li>
                <i class="fa fa-cog fa-lg"></i>
            </li>
            <li>
                <button class="btn btn-warning btn-xs btn-icon">
                    {{ servicestatusCount['1'] }}
                </button>
            </li>
            <li>
                <button class="btn btn-danger btn-xs btn-icon">
                    {{ servicestatusCount['2'] }}
                </button>
            </li>
            <li>
                <button class="btn btn-default btn-xs btn-icon">
                    {{ servicestatusCount['3'] }}
                </button>
            </li>
        <?php endif; ?>
    </ol>
</div>
