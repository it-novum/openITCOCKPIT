<div class="col-xs-12 col-md-12 col-lg-12 padding-5">
    <div class="jarviswidget jarviswidget-sortable" role="widget">
        <header role="heading">
            <h2>
                <i class="fa fa-desktop"></i>
                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                    <a ui-sref="HostsBrowser({id:data.Host.id})">
                        {{data.Host.name}}
                    </a>
                <?php else: ?>
                    {{data.Host.name}}
                <?php endif; ?>
            </h2>
        </header>

        <div class="widget-body">
            <div class="col col-md-12 padding-2">
                <div class="col col-xs-1 col-md-1 col-lg-1 no-padding">
                    <div id="hostPieChart-{{data.Host.id}}"></div>
                </div>
            </div>
            <service-availability-overview data="service"
                                           dynamic-color="dynamicColor"
                                           ng-repeat="service in data.Services"
                                           ng-if="evaluationType == 1">
            </service-availability-overview>
        </div>
    </div>
</div>

