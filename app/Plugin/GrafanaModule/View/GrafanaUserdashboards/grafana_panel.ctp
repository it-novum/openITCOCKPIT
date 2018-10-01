<div class="panel panel-default">
    <div class="panel-body padding-bottom-10 ">
        <div class="row margin-bottom-10">
            <div class="btn-group pull-right">
                <button class="btn btn-xs btn-success" ng-click="addMetric()">
                    <i class="fa fa-plus"> </i>
                    <?php echo __('Add Metric'); ?>
                </button>
                <button class="btn btn-xs btn-danger" ng-click="removePanel()">
                    <i class="fa fa-trash"> </i>
                    <?php echo __('Remove Panel'); ?>
                </button>
            </div>
        </div>

        <ul class="no-padding" style="list-style: none">
            <li ng-repeat="metric in panel.metrics">
                <div class=" panel panel-default margin-bottom-5" style="background-color:#f2f2f2">
                    <div class="panel-body padding-top-5 padding-bottom-5">
                        <?php if ($this->Acl->hasPermission('browser', 'hosts', '')): ?>
                            <a href="/hosts/browser/{{metric.Host.id}}">{{metric.Host.hostname}}</a>
                        <?php else: ?>
                            {{metric.Host.hostname}}
                        <?php endif; ?>
                        <i class="fa fa-chevron-right"></i>
                        <?php if ($this->Acl->hasPermission('browser', 'services', '')): ?>
                            <a href="/services/browser/{{metric.Service.id}}">{{metric.Service.servicename}}</a>
                        <?php else: ?>
                            {{metric.Service.servicename}}
                        <?php endif; ?>
                        <i class="fa fa-chevron-right"></i>
                        {{metric.metric}}
                        <i class="fa fa-trash-o text-danger pull-right pointer" ng-click="removeMetric(metric)"></i>
                    </div>
                </div>
            </li>
        </ul>

        <div class="col-xs-12 text-info text-center" ng-show="panel.metrics.length == 0">
            <i class="fa fa-info-circle"></i>
            <?php echo __('This panel is empty. Start by adding metrics.'); ?>
        </div>

        <!-- Add new metric to panel modal -->
        <div id="addMetricToPanelModal_{{rowId}}_{{panelId}}" class="modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            <i class="fa fa-pencil"></i>
                            <?php echo __('Add new metric'); ?>
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-xs-12">
                                <div class="form-group smart-form hintmark_red">
                                    <?php echo __('Select service'); ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group" ng-class="{'has-error': errors.service_id}">
                                    <select
                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                            class="form-control"
                                            chosen="services"
                                            callback="loadMoreServices"
                                            ng-options="itemObject.key as itemObject.value for itemObject in services"
                                            ng-model="currentServiceId">
                                    </select>
                                    <div ng-repeat="error in errors.service_id">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                            <br/>

                            <div class="col-xs-12">
                                <div class="form-group smart-form hintmark_red">
                                    <?php echo __('Select metric'); ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group" ng-class="{'has-error': errors.metric}">
                                    <select
                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                            class="form-control"
                                            chosen="metrics"
                                            callback="loadMoreServices"
                                            ng-options="key as value for (key , value) in metrics"
                                            ng-model="currentServiceMetric">
                                    </select>
                                    <div ng-repeat="error in errors.metric">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <?php echo __('Close'); ?>
                        </button>

                        <button type="button" class="btn btn-primary" ng-click="saveMetric()">
                            <?php echo __('Add metric'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
