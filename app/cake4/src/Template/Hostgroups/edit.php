<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-sitemap fa-fw "></i>
            <?php echo __('Host groups'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>

<confirm-delete></confirm-delete>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
        <h2>
            <?php echo __('Edit host group:'); ?>
            {{ post.Hostgroup.container.name }}
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete', 'hostgroups')): ?>
                <button type="button" class="btn btn-danger btn-xs" ng-click="confirmDelete(hostgroup)">
                    <i class="fa fa-trash-o"></i>
                    <?php echo __('Delete'); ?>
                </button>
            <?php endif; ?>
            <a back-button fallback-state='HostgroupsIndex' class="btn btn-default btn-xs">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID:'); ?> {{ post.Hostgroup.uuid }}
        </div>
    </header>
    <div class="widget-body">
        <form ng-submit="submit();" class="form-horizontal"
              ng-init="successMessage=
            {objectName : '<?php echo __('Host group'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
            <div class="row">

                <div class="form-group required" ng-class="{'has-error': errors.container.parent_id}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Container'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select
                                id="HostgroupParentContainer"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Hostgroup.container.parent_id">
                        </select>
                        <div ng-repeat="error in errors.container.parent_id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group required" ng-class="{'has-error': errors.container.name}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Host group name'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input
                                class="form-control"
                                type="text"
                                ng-model="post.Hostgroup.container.name">
                        <div ng-repeat="error in errors.container.name">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Description'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input class="form-control" type="text" ng-model="post.Hostgroup.description">
                    </div>
                </div>

                <div class="form-group" ng-class="{'has-error': errors.hostgroup_url}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Host group URL'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input class="form-control" type="text" ng-model="post.Hostgroup.hostgroup_url">
                        <div ng-repeat="error in errors.hostgroup_url">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Hosts'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select
                                id="HostgroupHosts"
                                multiple
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hosts"
                                callback="loadHosts"
                                ng-options="host.key as host.value for host in hosts"
                                ng-model="post.Hostgroup.hosts._ids">
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Host templates'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select
                                id="HostgroupHosttemplates"
                                multiple
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hosttemplates"
                                callback="loadHosttemplates"
                                ng-options="hosttemplate.key as hosttemplate.value for hosttemplate in hosttemplates"
                                ng-model="post.Hostgroup.hosttemplates._ids">
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 margin-top-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" value="<?php echo __('Update host group'); ?>">
                            <a back-button fallback-state='HostgroupsIndex' class="btn btn-default">
                                <?php echo __('Cancel'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
