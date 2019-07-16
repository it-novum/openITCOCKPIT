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
<div id="error_msg"></div>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-fighter-jet fa-fw "></i>
            <?php echo __('Performance'); ?>
            <span>>
                <?php echo __('Overview'); ?>
			</span>
        </h1>
    </div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <?php
                    /*
                    <div class="widget-toolbar" role="menu">
                         <a class="btn btn-xs btn-primary toggle" href="<?php echo $this->here; ?>"><i class="fa fa-refresh"></i> <?php echo __('Refresh'); ?></a>
                    </div>
                     */ ?>
                    <div class="widget-toolbar" role="menu">
                        <?php echo __('auto refresh'); ?>:
                        <div class="pull-right padding-top-8 padding-left-10 padding-right-20" id="autoLoadChart"></div>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu"></div>
                    <span class="widget-icon"> <i class="fa fa-fighter-jet"></i> </span>
                    <h2><?php echo __('Performance'); ?> </h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <div> <!-- active services -->
                            <div class="row">
                                <div class="padding-10">
                                    <!-- left row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Active Service Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
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
                                                            <td nagiostats="NUMSVCACTCHK1M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMSVCACTCHK5M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMSVCACTCHK15M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMSVCACTCHK60M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Since start'); ?></td>
                                                            <td nagiostats="NUMSVCCHECKED" unit="" critical="0"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                    <!-- center row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Active Service Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
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
                                                            <td nagiostats="MINACTSVCEXT" unit="s"></td>
                                                            <td nagiostats="MAXACTSVCEXT" unit="s"></td>
                                                            <td nagiostats="AVGACTSVCEXT" unit="s" warning="40"
                                                                critical="60"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Latency'); ?></td>
                                                            <td nagiostats="MINACTSVCLAT" unit="s" warning="20"
                                                                critical="50"></td>
                                                            <td nagiostats="MAXACTSVCLAT" unit="s" warning="20"
                                                                critical="50"></td>
                                                            <td nagiostats="AVGACTSVCLAT" unit="s" warning="20"
                                                                critical="50"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('State changes'); ?></td>
                                                            <td nagiostats="MINACTSVCPSC" unit="%"
                                                            </td>
                                                            <td nagiostats="MAXACTSVCPSC" unit="%"
                                                            </td>
                                                            <td nagiostats="AVGACTSVCPSC" unit="%"
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                    <!-- right row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Additional information'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo __('Key'); ?></th>
                                                            <th><?php echo __('Value'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo __('Version'); ?></td>
                                                            <td class="text-primary" nagiostats="NAGIOSVERSION"
                                                                unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Core PID'); ?></td>
                                                            <td class="text-primary" nagiostats="NAGIOSPID"
                                                                unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Total hosts'); ?></td>
                                                            <td class="text-primary" nagiostats="NUMHOSTS" unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Total services'); ?></td>
                                                            <td class="text-primary" nagiostats="NUMSERVICES"
                                                                unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Program uptime'); ?></td>
                                                            <td class="text-primary" nagiostats="PROGRUNTIME"
                                                                unit=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                </div><!-- end pedding -->
                            </div> <!-- end row -->
                        </div> <!-- end active services -->
                        <div> <!-- passive services -->
                            <div class="row">
                                <div class="padding-10">
                                    <!-- left row -->

                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Passive Service Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
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
                                                            <td nagiostats="NUMSVCPSVCHK1M" unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMSVCPSVCHK5M" unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMSVCPSVCHK15M" unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMSVCPSVCHK60M" unit=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                    <!-- center row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Passive Service Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
                                                <div class="widget-body no-padding" style="min-height: 10px;">
                                                    <!-- removing min-height :( -->
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
                                                            <td nagiostats="MINPSVSVCPSC" unit="%"></td>
                                                            <td nagiostats="MAXPSVSVCPSC" unit="%"></td>
                                                            <td nagiostats="AVGPSVSVCPSC" unit="%"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                </div><!-- end pedding -->
                            </div> <!-- end row -->
                        </div> <!-- end passive services -->
                        <div> <!-- active hosts -->
                            <div class="row">
                                <div class="padding-10">
                                    <!-- left row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Active Host Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
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
                                                            <td nagiostats="NUMHSTACTCHK1M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMHSTACTCHK5M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMHSTACTCHK15M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMHSTACTCHK60M" unit="" critical="0"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Since start'); ?></td>
                                                            <td nagiostats="NUMHSTCHECKED" unit="" critical="0"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                    <!-- center row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Active Host Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
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
                                                            <td nagiostats="MINACTHSTEXT" unit="s" warning="40"
                                                                critical="60"></td>
                                                            <td nagiostats="MAXACTHSTEXT" unit="s" warning="40"
                                                                critical="60"></td>
                                                            <td nagiostats="AVGACTHSTEXT" unit="s" warning="40"
                                                                critical="60"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('Latency'); ?></td>
                                                            <td nagiostats="MINACTHSTLAT" unit="s" warning="20"
                                                                critical="50"></td>
                                                            <td nagiostats="MAXACTHSTLAT" unit="s" warning="20"
                                                                critical="50"></td>
                                                            <td nagiostats="AVGACTHSTLAT" unit="s" warning="20"
                                                                critical="50"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo __('State changes'); ?></td>
                                                            <td nagiostats="MINACTHSTPSC" unit="%"></td>
                                                            <td nagiostats="MAXACTHSTPSC" unit="%"></td>
                                                            <td nagiostats="AVGACTHSTPSC" unit="%"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                </div><!-- end pedding -->
                            </div> <!-- end row -->
                        </div> <!-- end active hosts -->
                        <div> <!-- passive hosts -->
                            <div class="row">
                                <div class="padding-10">
                                    <!-- left row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Passive Host Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
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
                                                            <td nagiostats="NUMHSTPSVCHK1M" unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 5 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMHSTPSVCHK5M" unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 15 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMHSTPSVCHK15M" unit=""></td>
                                                        </tr>
                                                        <tr>
                                                            <td><= 60 <?php echo __('minutes'); ?></td>
                                                            <td nagiostats="NUMHSTPSVCHK60M" unit=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                    <!-- center row -->
                                    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4 sortable-grid ui-sortable">
                                        <div class="jarviswidget jarviswidget-color-white" id="wid-id-1"
                                             data-widget-editbutton="false">
                                            <header>
                                                <div class="jarviswidget-ctrls" role="menu"></div>
                                                <h2><?php echo __('Passive Host Checks'); ?> </h2>
                                            </header>
                                            <!-- widget div-->
                                            <div>
                                                <!-- widget content -->
                                                <div class="widget-body no-padding" style="min-height: 10px;">
                                                    <!-- removing min-height :( -->
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
                                                            <td nagiostats="MINPSVHSTPSC" unit="%"></td>
                                                            <td nagiostats="MAXPSVHSTPSC" unit="%"></td>
                                                            <td nagiostats="AVGPSVHSTPSC" unit="%"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                    </article> <!-- end article -->
                                </div><!-- end pedding -->
                            </div> <!-- end row -->
                            <br/>
                        </div> <!-- end passive hosts -->
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->