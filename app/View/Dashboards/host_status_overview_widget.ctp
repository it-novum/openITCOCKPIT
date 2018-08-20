<div class="padding-10" style="border: 1px solid #c3c3c3;">
    <div class="row">
        <div class="col-xs-8">
            <div class="row">
                <div class="form-group smart-form">
                    <label>
                        <input type="radio" class="radiobox"
                            ng-model="filter.Hoststatus.current_state"
                            ng-model-options="{debounce: 500}"
                            ng-value="0">
                        <span>
                            <?php echo __('Up'); ?>
                        </span>
                    </label>
                    <label>
                        <input type="radio" class="radiobox"
                           ng-model="filter.Hoststatus.current_state"
                           ng-model-options="{debounce: 500}"
                           ng-value="1">
                        <span>
                            <?php echo __('Down'); ?>
                        </span>
                    </label>
                    <label>
                        <input type="radio" class="radiobox"
                           ng-model="filter.Hoststatus.current_state"
                           ng-model-options="{debounce: 500}"
                           ng-value="2">
                        <span>
                            <?php echo __('Unreachable'); ?>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row padding-top-20">
        <div class="col-xs-12 col-md-12">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by host name'); ?>"
                           ng-model="filter.Host.name"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </div>
    </div>
</div>

