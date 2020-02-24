<div id="angularMassAactivate" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary txt-color-white">
                <h5 class="modal-title">
                    <?php echo __('Attention!'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Do you really want activate the selected objects?'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 margin-top-10">
                        <ul>
                            <li ng-repeat="(id, object) in objects">
                                {{ object }}
                                <div class="text-danger" ng-repeat="issue in issueObjects[id]">
                                    <i class="fa fa-times"></i>
                                    <span class="text-danger">{{issue.message}}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-12 margin-top-10" ng-show="isActivating">
                        <h4><?php echo __('Activating...'); ?></h4>
                    </div>
                    <div class="col-lg-12 margin-top-10" ng-show="isActivating">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary txt-color-white" ng-click="activate()">
                    <?php echo __('Enable'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
