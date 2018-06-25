<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.
?>
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-info "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Container overview'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-sitemap fa-rotate-270"></i> </span>
                    <h2><?php echo __('Objects overview'); ?> "{{containerDetails.Container.name}}"</h2>
                    <div class="widget-toolbar" role="menu">
                        <?php echo $this->Utils->backButton() ?>
                    </div>
                </header>
                <div class="widget-body">
                    <table id="host_list" class="table table-striped table-hover table-bordered smart-form">
                        <tbody>
                            <tr>
                                <th class="no-sort">
                                    <i class="fa fa-location-arrow fa-lg"></i>
                                    <?php echo __('Locations'); ?> ({{containerDetails.ContainerLocation.length}})
                                </th>
                                <th class="width-60"></th>
                            </tr>
                            <tr ng-repeat="containerlocation in containerDetails.ContainerLocation">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'locations')): ?>
                                        <a href="/locations/edit/{{ containerlocation.Location[0].id }}" target="_blank">
                                            {{ containerlocation.name }}
                                        </a>
                                    <?php else: ?>
                                        {{ containerlocation.name }}
                                    <?php endif; ?>
                                    <i class="text-info">{{ containerlocation.Location[0].description }}</i>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($this->Acl->hasPermission('edit', 'locations')): ?>
                                            <a href="/locations/edit/{{containerlocation.Location[0].id}}"
                                               class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn btn-default">&nbsp;
                                                 <i class="fa fa-cog"></i> </a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-location-{{containerlocation.id}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'locations')): ?>
                                                <li>
                                                    <a href="/locations/edit/{{containerlocation.Location[0].id}}">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('showDetails', 'containers')): ?>
                                                <li>
                                                    <a href="/containers/showDetails/{{containerlocation.id}}">
                                                        <i class="fa fa-info-circle"></i> <?php echo __('Show details'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="no-sort" colspan="2">
                                    <i class="fa fa-link fa-lg"></i>
                                    <?php echo __('Nodes'); ?> ({{containerDetails.ContainerNode.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="node in containerDetails.ContainerNode">
                                <td>
                                    {{ node.name }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                    <?php if ($this->Acl->hasPermission('showDetails', 'containers')): ?>
                                        <a href="/containers/showDetails/{{node.id}}" class="btn btn-default">&nbsp;
                                            <i class="fa fa-info-circle"></i>&nbsp;
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="btn btn-default">&nbsp;
                                            <i class="fa fa-info-circle"></i> </a>
                                    <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-node-{{node.id}}">
                                            <?php if ($this->Acl->hasPermission('showDetails', 'containers')): ?>
                                            <li>
                                                <a href="/containers/showDetails/{{node.id}}">
                                                    <i class="fa fa-info-circle"></i> <?php echo __('Show details'); ?>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="no-sort" colspan="2">
                                    <i class="fa fa-sitemap fa-lg"></i>
                                    <?php echo __('Host groups'); ?> ({{containerDetails.ContainerHostgroup.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="hostgroupContainer in containerDetails.ContainerHostgroup">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>

                                        <a href="/hostgroups/edit/{{ hostgroupContainer.Hostgroup[0].id }}"
                                           ng-if="hostgroupContainer.Hostgroup[0].id"
                                           target="_blank">
                                            {{ hostgroupContainer.name }}
                                        </a>
                                        <span ng-hide="hostgroupContainer.Hostgroup[0].id">
                                            <span class="changelog_delete">{{ hostgroupContainer.name }}</span>
                                            <i><?php echo __(' ... invalid host group'); ?></i>
                                        </span>
                                    <?php else: ?>
                                        <span>{{ hostgroupContainer.name }}</span>
                                    <?php endif; ?>
                                    <i class="text-info">{{ hostgroupContainer.Hostgroup[0].description }}</i>
                                </td>
                                <td>
                                    <div class="btn-group" ng-if="hostgroupContainer.Hostgroup[0].id">
                                        <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>
                                            <a href="/hostgroups/edit/{{hostgroupContainer.Hostgroup[0].id}}"
                                               class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn btn-default">&nbsp;
                                                <i class="fa fa-cog"></i> </a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-hostgroup-{{hostgroupContainer.id}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>
                                                <li>
                                                    <a href="/hostgroups/edit/{{hostgroupContainer.Hostgroup[0].id}}">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="no-sort" colspan="2">
                                    <i class="fa fa-desktop fa-lg"></i>
                                    <?php echo __('Hosts'); ?> ({{containerDetails.Host.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="host in containerDetails.Host">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                        <a href="/hosts/edit/{{ host.id }}"
                                           target="_blank">
                                            {{ host.name }}
                                        </a>
                                    <?php else: ?>
                                        <span>{{ host.name }}</span>
                                    <?php endif; ?>
                                    <i class="text-info">{{ host.description }}</i>
                                </td>
                                <td>
                                    <div class="btn-group" ng-if="host.id">
                                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                            <a href="/hosts/edit/{{host.id}}"
                                               class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn btn-default">&nbsp;
                                                <i class="fa fa-cog"></i> </a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-host-{{host.id}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                <li>
                                                    <a href="/hosts/edit/{{host.id}}">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="no-sort" colspan="2">
                                    <i class="fa fa-pencil-square-o fa-lg"></i>
                                    <?php echo __('Service template groups'); ?> ({{containerDetails.ContainerServicetemplategroup.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="servicetemplategroupContainer in containerDetails.ContainerServicetemplategroup">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'servicetemplategroups')): ?>

                                        <a href="/servicetemplategroups/edit/{{ servicetemplategroupContainer.Servicetemplategroup[0].id }}"
                                           ng-if="servicetemplategroupContainer.Servicetemplategroup[0].id"
                                           target="_blank">
                                            {{ servicetemplategroupContainer.name }}
                                        </a>
                                        <span ng-hide="servicetemplategroupContainer.Servicetemplategroup[0].id">
                                            <span class="changelog_delete">{{ servicetemplategroupContainer.name }}</span>
                                            <i><?php echo __(' ... invalid service template group'); ?></i>
                                        </span>
                                    <?php else: ?>
                                        <span>{{ servicetemplategroupContainer.name }}</span>
                                    <?php endif; ?>
                                    <i class="text-info">{{ servicetemplategroupContainer.Servicetemplategroup[0].description }}</i>
                                </td>
                                <td>
                                    <div class="btn-group" ng-if="servicetemplategroupContainer.Servicetemplategroup[0].id">
                                        <?php if ($this->Acl->hasPermission('edit', 'servicetemplategroups')): ?>
                                            <a href="/servicetemplategroups/edit/{{servicetemplategroupContainer.Servicetemplategroup[0].id}}"
                                               class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn btn-default">&nbsp;
                                                <i class="fa fa-cog"></i> </a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-servicetemplategroup-{{servicetemplategroupContainer.id}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'servicetemplategroups')): ?>
                                                <li>
                                                    <a href="/hostgroups/edit/{{servicetemplategroupContainer.Servicetemplategroup[0].id}}">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="no-sort" colspan="2">
                                    <i class="fa fa-pencil-square-o fa-lg"></i>
                                    <?php echo __('Service templates'); ?> ({{containerDetails.Servicetemplate.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="servicetemplate in containerDetails.Servicetemplate">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'servicetemplates')): ?>
                                        <a href="/servicetemplates/edit/{{ servicetemplate.id }}"
                                           target="_blank">
                                            {{ servicetemplate.template_name }}
                                        </a>
                                    <?php else: ?>
                                        <span>{{ servicetemplate.template_name }}</span>
                                    <?php endif; ?>
                                    <i class="text-info">{{ servicetemplate.name }}</i>
                                </td>
                                <td>
                                    <div class="btn-group" ng-if="servicetemplate.id">
                                        <?php if ($this->Acl->hasPermission('edit', 'servicetemplates')): ?>
                                            <a href="/hosts/edit/{{servicetemplate.id}}"
                                               class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn btn-default">&nbsp;
                                                <i class="fa fa-cog"></i> </a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-host-{{servicetemplate.id}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'servicetemplates')): ?>
                                                <li>
                                                    <a href="/hosts/edit/{{servicetemplate.id}}">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </div>
</section>
