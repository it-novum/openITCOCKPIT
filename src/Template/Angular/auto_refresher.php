<div class="btn-group mr-1">
    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        <i class="fa fa-clock-o"></i>
        <span class="text-primary">
            {{humanAutoRefresh}}
        </span>
        <span class="caret"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-right">

        <button
                ng-click="changeAutoRefresh(0, '<?= __('Disabled') ?>')"
                class="dropdown-item">
            <?= __('Disabled') ?>
        </button>

        <div class="dropdown-divider"></div>

        <button ng-repeat="(seconds, name) in timeranges.refresh_interval"
                ng-if="seconds > 0"
                ng-click="changeAutoRefresh(seconds, name)"
                class="dropdown-item">
            {{ name }}
        </button>

    </div>
</div>
