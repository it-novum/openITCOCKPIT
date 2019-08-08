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

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-fighter-jet fa-fw "></i>
            <?php echo __('Monitoring Engine'); ?>
            <span>>
                <?php echo __('Performance'); ?>
            </span>
            <div class="third_level">> <?php echo __('Overview'); ?></div>
        </h1>
    </div>
</div>


<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12">
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>
                    </div>
                    <span class="widget-icon"> <i class="fa fa-fighter-jet"></i></span>
                    <h2><?php echo __('Performance information'); ?> </h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div>
                            <div class="row">
                                <div class="padding-10">
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Executed Active Service Checks'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Timeframe'); ?></th>
                                                            <th><?php echo __('Checks performed'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><= 1 <?php echo __('minute'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMSVCACTCHK1M == 0}">
                                                                {{stats.NUMSVCACTCHK1M}}
                                                                <span id="NUMSVCACTCHK1M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMSVCACTCHK5M == 0}">
                                                                {{stats.NUMSVCACTCHK5M}}
                                                                <span id="NUMSVCACTCHK5M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMSVCACTCHK15M == 0}">
                                                                {{stats.NUMSVCACTCHK15M}}
                                                                <span id="NUMSVCACTCHK15M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMSVCACTCHK60M == 0}">
                                                                {{stats.NUMSVCACTCHK60M}}
                                                                <span id="NUMSVCACTCHK60M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Since start'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMSVCCHECKED == 0}">
                                                                {{stats.NUMSVCCHECKED}}
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Active Service Checks Timings'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Key'); ?></th>
                                                            <th><?php echo __('Min.'); ?></th>
                                                            <th><?php echo __('Max.'); ?></th>
                                                            <th>&#216;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo __('Execution time'); ?></td>
                                                            <td>{{stats.MINACTSVCEXT / 1000}} sec.</td>
                                                            <td>{{stats.MAXACTSVCEXT / 1000}} sec.</td>
                                                            <td ng-class="{'warning': stats.AVGACTSVCEXT > 20000, 'critical': stats.AVGACTSVCEXT > 30000}">
                                                                {{stats.AVGACTSVCEXT / 1000}} sec.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Latency'); ?></td>
                                                            <td ng-class="{'warning': stats.MINACTSVCLAT > 20000, 'critical': stats.MINACTSVCLAT > 30000}">
                                                                {{stats.MINACTSVCLAT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.MAXACTSVCLAT > 20000, 'critical': stats.MAXACTSVCLAT > 30000}">
                                                                {{stats.MAXACTSVCLAT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.AVGACTSVCLAT > 20000, 'critical': stats.AVGACTSVCLAT > 30000}">
                                                                {{stats.AVGACTSVCLAT / 1000}} sec.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('State changes'); ?></td>
                                                            <td>{{stats.MINACTSVCPSC}} %</td>
                                                            <td>{{stats.MAXACTSVCPSC}} %</td>
                                                            <td>{{stats.AVGACTSVCPSC}} %</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Active Service Checks'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Key'); ?></th>
                                                            <th><?php echo __('Min.'); ?></th>
                                                            <th><?php echo __('Max.'); ?></th>
                                                            <th>&#216;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo __('Execution time'); ?></td>
                                                            <td ng-class="{'warning': stats.MINACTSVCEXT > 20000, 'critical': stats.MINACTSVCEXT > 30000}">
                                                                {{stats.MINACTSVCEXT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.MAXACTSVCEXT > 20000, 'critical': stats.MAXACTSVCEXT > 30000}">
                                                                {{stats.MAXACTSVCEXT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.AVGACTSVCEXT > 20000, 'critical': stats.AVGACTSVCEXT > 30000}">
                                                                {{stats.AVGACTSVCEXT / 1000}} sec.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Latency'); ?></td>
                                                            <td ng-class="{'warning': stats.MINACTSVCLAT > 20000, 'critical': stats.MINACTSVCLAT > 30000}">
                                                                {{stats.MINACTSVCLAT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.MAXACTSVCLAT > 20000, 'critical': stats.MAXACTSVCLAT > 30000}">
                                                                {{stats.MAXACTSVCLAT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.AVGACTSVCLAT > 20000, 'critical': stats.AVGACTSVCLAT > 30000}">
                                                                {{stats.AVGACTSVCLAT / 1000}} sec.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('State changes'); ?></td>
                                                            <td>{{stats.MINACTSVCPSC}} %</td>
                                                            <td>{{stats.MAXACTSVCPSC}} %</td>
                                                            <td>{{stats.AVGACTSVCPSC}} %</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row">
                                <div class="padding-10">
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Passive Service Checks'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Timeframe'); ?></th>
                                                            <th><?php echo __('Checks performed'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><= 1 <?php echo __('minute'); ?></td>
                                                            <td>
                                                                {{stats.NUMSVCPSVCHK1M}}
                                                                <span id="NUMSVCPSVCHK1M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td>
                                                                {{stats.NUMSVCPSVCHK5M}}
                                                                <span id="NUMSVCPSVCHK5M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td>
                                                                {{stats.NUMSVCPSVCHK15M}}
                                                                <span id="NUMSVCPSVCHK15M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td>
                                                                {{stats.NUMSVCPSVCHK60M}}
                                                                <span id="NUMSVCPSVCHK60M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Processed Passive Service Checks'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding" style="min-height: 10px;">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Key'); ?></th>
                                                            <th><?php echo __('Min.'); ?></th>
                                                            <th><?php echo __('Max.'); ?></th>
                                                            <th>&#216;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo __('State changes'); ?></td>
                                                            <td>{{stats.MINPSVSVCPSC}} %</td>
                                                            <td>{{stats.MAXPSVSVCPSC}} %</td>
                                                            <td>{{stats.AVGPSVSVCPSC}} %</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row">
                                <div class="padding-10">
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Executed Active Host Checks'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Timeframe'); ?></th>
                                                            <th><?php echo __('Checks performed'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><= 1 <?php echo __('minute'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMHSTACTCHK1M == 0}">
                                                                {{stats.NUMHSTACTCHK1M}}
                                                                <span id="NUMHSTACTCHK1M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMHSTACTCHK5M == 0}">
                                                                {{stats.NUMHSTACTCHK5M}}
                                                                <span id="NUMHSTACTCHK5M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMHSTACTCHK15M == 0}">
                                                                {{stats.NUMHSTACTCHK15M}}
                                                                <span id="NUMHSTACTCHK15M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMHSTACTCHK60M == 0}">
                                                                {{stats.NUMHSTACTCHK60M}}
                                                                <span id="NUMHSTACTCHK60M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Since start'); ?></td>
                                                            <td ng-class="{'critical': stats.NUMHSTCHECKED == 0}">
                                                                {{stats.NUMHSTCHECKED}}
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Active Host Checks Timings'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Key'); ?></th>
                                                            <th><?php echo __('Min.'); ?></th>
                                                            <th><?php echo __('Max.'); ?></th>
                                                            <th>&#216;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo __('Execution time'); ?></td>
                                                            <td ng-class="{'warning': stats.MINACTHSTEXT > 20000, 'critical': stats.MINACTHSTEXT > 30000}">
                                                                {{stats.MINACTHSTEXT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.MAXACTHSTEXT > 20000, 'critical': stats.MAXACTHSTEXT > 30000}">
                                                                {{stats.MAXACTHSTEXT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.AVGACTHSTEXT > 20000, 'critical': stats.AVGACTHSTEXT > 30000}">
                                                                {{stats.AVGACTHSTEXT / 1000}} sec.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Latency'); ?></td>
                                                            <td ng-class="{'warning': stats.MINACTHSTLAT > 20000, 'critical': stats.MINACTHSTLAT > 30000}">
                                                                {{stats.MINACTHSTLAT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.MAXACTHSTLAT > 20000, 'critical': stats.MAXACTHSTLAT > 30000}">
                                                                {{stats.MAXACTHSTLAT / 1000}} sec.
                                                            </td>
                                                            <td ng-class="{'warning': stats.AVGACTHSTLAT > 20000, 'critical': stats.AVGACTHSTLAT > 30000}">
                                                                {{stats.AVGACTHSTLAT / 1000}} sec.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('State changes'); ?></td>
                                                            <td>{{stats.MINACTHSTPSC}} %</td>
                                                            <td>{{stats.MAXACTHSTPSC}} %</td>
                                                            <td>{{stats.AVGACTHSTPSC}} %</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row">
                                <div class="padding-10">
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Processed Passive Host Checks'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Timeframe'); ?></th>
                                                            <th><?php echo __('Checks performed'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><= 1 <?php echo __('minute'); ?></td>
                                                            <td>
                                                                {{stats.NUMHSTPSVCHK1M}}
                                                                <span id="NUMHSTPSVCHK1M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td>
                                                                {{stats.NUMHSTPSVCHK5M}}
                                                                <span id="NUMHSTPSVCHK5M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td>
                                                                {{stats.NUMHSTPSVCHK15M}}
                                                                <span id="NUMHSTPSVCHK15M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td>
                                                                {{stats.NUMHSTPSVCHK60M}}
                                                                <span id="NUMHSTPSVCHK60M_sparkline"></span>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                        <div class="jarviswidget">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Passive Host Checks'); ?> </h2>
                                            </header>
                                            <div>
                                                <div class="widget-body no-padding" style="min-height: 10px;">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Key'); ?></th>
                                                            <th><?php echo __('Min.'); ?></th>
                                                            <th><?php echo __('Max.'); ?></th>
                                                            <th>&#216;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo __('State changes'); ?></td>
                                                            <td>{{stats.MINPSVHSTPSC}} %</td>
                                                            <td>{{stats.MAXPSVHSTPSC}} %</td>
                                                            <td>{{stats.AVGPSVHSTPSC}} %</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                            <br/>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
