
<div id="angularAddHostsToHostgroup" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary txt-color-white">
                <h5 class="modal-title">
                    <?php echo __('Append host/s to host group'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Selected hosts'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 margin-top-10">
                        <ul>
                            <li ng-repeat="(id, hostName) in objects">
                                {{ hostName }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xs-12 margin-top-10" ng-show="isDeleting">
                        <h4><?php echo __('Deleting...'); ?></h4>
                    </div>
                    <div class="col-xs-12 margin-top-10" ng-show="isDeleting">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" ui-sref="HostgroupsAdd({ids:hostIds})"
                        data-dismiss="modal">
                    <?php echo __('Create new host group'); ?>
                </button>
                <button type="button" class="btn btn-primary" ui-sref="HostgroupsAppend({ids:hostIds})"
                        data-dismiss="modal">
                    <?php echo __('Append existing host group'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
