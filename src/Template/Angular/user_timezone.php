<div class="btn-header float-right hidden-mobile hidden-tablet padding-left-5" ng-hide="initTime">
    <span>
        <a href="javascript:void(0);" class="server-clock"
           data-original-title="<?php echo __('Server time'); ?>" data-placement="left" rel="tooltip"
           data-container="body">
            <i class="fas fa-clock"></i>
            {{ currentServerTime }}
        </a>
    </span>
</div>
<div class="btn-header float-right hidden-mobile hidden-tablet" ng-show="showClientTime">
    <span>
        <a href="javascript:void(0);" class="server-clock"
           data-original-title="<?php echo __('Your local time'); ?>" data-placement="left" rel="tooltip"
           data-container="body">
            <i class="far fa-clock"></i>
            {{ currentClientTime }}
        </a>
    </span>
</div>
