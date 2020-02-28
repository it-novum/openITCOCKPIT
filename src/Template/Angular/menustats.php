<div class="btn-toolbar header-icon" role="toolbar" style="padding-right: 25px;">
    <div class="btn-group btn-group-xs mr-2" role="group">
        <?php if ($this->Acl->hasPermission('index', 'Hosts', '')): ?>
            <button class="btn btn-default"
                    ui-sref="HostsIndex({sort: 'Hoststatus.last_state_change', direction: 'desc'})">
                <i class="fa fa-hdd-o fa-lg"></i>
            </button>
            <button class="btn btn-danger"
                    ui-sref="HostsIndex({hoststate: [1], sort: 'Hoststatus.last_state_change', direction: 'desc'})">
                {{ hoststatusCount['1'] }}
            </button>
            <button class="btn btn-secondary"
                    ui-sref="HostsIndex({hoststate: [2], sort: 'Hoststatus.last_state_change', direction: 'desc'})">
                {{ hoststatusCount['2'] }}
            </button>
        <?php else: ?>
            <button class="btn btn-default">
                <i class="fa fa-hdd-o fa-lg"></i>
            </button>
            <button class="btn btn-danger">
                {{ hoststatusCount['1'] }}
            </button>
            <button class="btn btn-default">
                {{ hoststatusCount['2'] }}
            </button>
        <?php endif; ?>
    </div>
    <div class="btn-group btn-group-xs mr-2" role="group">
        <?php if ($this->Acl->hasPermission('index', 'services', '')): ?>
            <button class="btn btn-default"
                    ui-sref="ServicesIndex({sort: 'Servicestatus.last_state_change', direction: 'desc'})">
                <i class="fa fa-cog fa-lg"></i>
            </button>
            <button class="btn btn-warning"
                    ui-sref="ServicesIndex({servicestate: [1], sort: 'Servicestatus.last_state_change', direction: 'desc'})">
                {{ servicestatusCount['1'] }}
            </button>
            <button class="btn btn-danger"
                    ui-sref="ServicesIndex({servicestate: [2], sort: 'Servicestatus.last_state_change', direction: 'desc'})">
                {{ servicestatusCount['2'] }}
            </button>
            <button class="btn btn-secondary"
                    ui-sref="ServicesIndex({servicestate: [3], sort: 'Servicestatus.last_state_change', direction: 'desc'})">
                {{ servicestatusCount['3'] }}
            </button>
        <?php else: ?>
            <button class="btn btn-default">
                <i class="fa fa-cog fa-lg"></i>
            </button>
            <button class="btn btn-warning">
                {{ servicestatusCount['1'] }}
            </button>
            <button class="btn btn-danger">
                {{ servicestatusCount['2'] }}
            </button>
            <button class="btn btn-secondary">
                {{ servicestatusCount['3'] }}
            </button>
        <?php endif; ?>
    </div>
</div>
