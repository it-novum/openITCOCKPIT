<div class="btn-header pull-right hidden-mobile hidden-tablet" ng-hide="initTime">
    <span>
        <a href="javascript:void(0);" class="server-clock"
           data-original-title="<?php echo __('Server time'); ?>" data-placement="left" rel="tooltip"
           data-container="body">
            <i class="fa fa-clock-o"></i>
            {{ currentServerTime }}
        </a>
    </span>
</div>
<div class="btn-header pull-right hidden-mobile hidden-tablet" ng-show="showClientTime">
    <span>
        <a href="javascript:void(0);" class="server-clock"
           data-original-title="<?php echo __('Your local time'); ?>" data-placement="left" rel="tooltip"
           data-container="body">
            <i class="fa fa-clock-o"></i>
            {{ currentClientTime }}
        </a>
    </span>
</div>
