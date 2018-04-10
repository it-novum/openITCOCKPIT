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
            <i class="fa fa-map-marker fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('Copy'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">

    <div class="row">

        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
                    <h2>Copy Map</h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <form ng-submit="submit();" class="form-horizontal">
                            <div class="row">
                                <div class="col-xs-12 col-md-9 col-lg-7">
                                    <fieldset>
                                        <legend> <?php echo __('Source Map: '); ?> {{ sourceMap.Map.name }}</legend>
                                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                                            <label class="col col-md-2 control-label">
                                                <?php echo __('New Map Name'); ?>
                                            </label>
                                            <div class="col col-xs-10">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Map.name">
                                                <div ng-repeat="error in errors.name">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group required" ng-class="{'has-error': errors.title}">
                                            <label class="col col-md-2 control-label">
                                                <?php echo __('New Map Title'); ?>
                                            </label>
                                            <div class="col col-xs-10">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Map.title">
                                                <div ng-repeat="error in errors.title">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col col-md-2 control-label">
                                                <?php echo __('New Refresh interval'); ?>
                                            </label>
                                            <div class="col col-xs-10">
                                                <input class="form-control" type="number"
                                                       ng-model="post.Map.refresh_interval" min="10"
                                                       max="180" step="5">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <br/>
                                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                                    <div class="well formactions ">
                                        <div class="pull-right">
                                            <input class="btn btn-primary" type="submit" value="Save">&nbsp;
                                            <a href="/map_module/maps/index" class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>