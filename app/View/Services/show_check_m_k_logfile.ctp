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
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input type="hidden" id="hostUuid" value="<?php echo $service['Host']['uuid'];?>">
            <input type="hidden" id="serviceName" value="<?php echo $service['Service']['name'];?>">
            <div class="jarviswidget jarviswidget-color-blueDark" style="margin-bottom:0px;" id="wid-id-1" data-widget-editbutton="false" >
                <header>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-cog"></i> </span>
                    <h2 class="hidden-mobile">
                        <a href="/hosts/browser/<?php echo $service['Host']['id']; ?>" style="color:#FFF;"><?php echo $service['Host']['name'];?></a>
                        <a href="/services/browser/<?php echo $service['Service']['id']; ?>" style="color:#FFF;"><?php echo $service['Service']['name']; ?></a>
                    </h2>
                </header>
            </div>
            <div class="mobile_table">
                <table id="service_list" class="table table-striped table-bordered smart-form" style="">
                    <thead>
                    <tr>
                        <?php $order = $this->Paginator->param('order'); ?>
                        <th class="select_datatable no-sort">&nbsp;</th>
                        <th class="no-sort"><?php echo __('State'); ?></th>
                        <th class="no-sort"><?php echo __('Time'); ?></th>
                        <th class="no-sort"><?php echo __('Output'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $counter = 0;
                    foreach($logfileContent as $time => $values): ?>
                        <tr>
                            <td class="text-center width-5">
                                <input type="checkbox" array-index="<?php echo $counter;?>" class="massChange" servicename="">
                            </td>
                            <td class="text-center width-10">
                                <?php
                                $timeComponents = explode(" ",$time);
                                $state = array_slice($timeComponents,-1,1);
                                if($state[0] === 'CRIT'){
                                    echo '<div class="btn btn-danger btn-xs status-circle" style=""></div>';
                                }elseif($state[0] === 'WARN'){
                                    echo '<div class="btn btn-warning btn-xs status-circle" style=""></div>';
                                }else{
                                    echo '<div class="btn btn-default btn-xs status-circle" style=""></div>';
                                }
                                ?>
                            </td>
                            <td style="width:100px;">
                                <?php
                                    echo $time;
                                ?>
                            </td>
                            <td>
                                <?php foreach($values as $value):
                                    echo $value."</br>";
                                endforeach; ?>
                            </td>
                        </tr>
                    <?php
                        $counter++;
                    endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </div>
    <div class="padding-top-10"></div>
    <div class="row">
        <div class="col-xs-12 col-md-2 text-muted"><center><span id="selectionCount"></span></center></div>
        <div class="col-xs-12 col-md-2 "><span id="selectAll" class="pointer"><i class="fa fa-lg fa-check-square-o"></i> <?php echo __('Select all'); ?></span></div>
        <div class="col-xs-12 col-md-2"><span id="untickAll" class="pointer"><i class="fa fa-lg fa-square-o"></i> <?php echo __('Undo selection'); ?></span></div>
        <div class="col-xs-12 col-md-2"><a href="javascript:void(0);" id="deleteAllSelectedEntries" class="txt-color-red" style="text-decoration: none;"> <i class="fa fa-lg fa-trash-o"></i> Delete</a></div>
    </div>
</section>
