<div id="angularMassDelete" class="modal" role="dialog">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-color-danger txt-color-white">
                <h5 class="modal-title">
                    <?php echo __('Attention!'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" ng-if="!massDeleteMessage">
                        <?php echo __('Do you really want delete the selected object?'); ?>
                    </div>

                    <div class="col-12" ng-if="massDeleteMessage">
                        {{massDeleteMessage}}
                    </div>

                    <div class="col-12 help-block" ng-if="massDeleteHelp">
                        {{massDeleteHelp}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 margin-top-10">
                        <ul>
                            <li ng-repeat="(id, object) in objects">
                                {{ object }}
                                <div class="text-danger" ng-repeat="issue in issueObjects[id]">
                                    <i class="fa fa-times"></i>
                                    <a class="text-danger"
                                       ng-if="!issue.isAngular"
                                       href="{{issue.url}}">{{issue.message}}</a>

                                    <a class="text-danger pointer"
                                       ng-if="issue.isAngular"
                                       ng-click="goToStateMassDelete(issue)">{{issue.message}}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12 margin-top-10" ng-show="isDeleting">
                        <h4><?php echo __('Deleting...'); ?></h4>
                    </div>
                    <div class="col-12 margin-top-10" ng-show="isDeleting">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-danger" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" ng-click="delete()">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
