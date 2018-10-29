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

use itnovum\openITCOCKPIT\Core\Views\Logo;

$Logo = new Logo();
?>
<div class="jarviswidget">
    <header>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div class="well">
        <div class="row margin-top-10 font-md padding-bottom-10">
            <div class="col-md-9 text-left">
                <i class="fa fa-calendar txt-color-blueDark"></i>
                <?php
                echo __('Current state report ');
                echo h('(' . __('Date: ') . $this->Time->format(time(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')) . ')'); ?>
            </div>
            <div class="col-md-3 text-left">
                <?php
                echo $this->Html->image($Logo->getLogoForHtmlHelper(),
                    ['width' => '200']
                ); ?>
            </div>
            <div class="col-md-12 padding-20">
                <?php
                foreach ($currentStateData as $currentStateObjectData):
                    if (!empty($currentStateObjectData['Host']['Services'])):?>
                        <div class="jarviswidget jarviswidget-color-blueLight col-md-12">
                            <header role="heading">
                                <h2>
                                    <strong>
                                        <i class="fa fa-desktop"></i> <?php echo $this->Html->link(h($currentStateObjectData['Host']['name']), [
                                            'action'     => 'browser',
                                            'controller' => 'hosts',
                                            $currentStateObjectData['Host']['id'],
                                        ], [
                                                'class' => 'txt-color-blueDark',
                                            ]
                                        );
                                        ?>
                                    </strong>
                                </h2>
                            </header>
                            <div class="widget-body">
                                <div class="col-md-3 ">
                                    <?php echo __('Description'); ?>
                                </div>
                                <div class="col-md-9">
                                    <?php echo h(($currentStateObjectData['Host']['description']) ? $currentStateObjectData['Host']['description'] : ' - '); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo __('IP address'); ?>
                                </div>
                                <div class="col-md-9">
                                    <?php echo h($currentStateObjectData['Host']['address']); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo __('Status'); ?>
                                </div>
                                <div class="col-md-9">
                                    <?php echo h($this->Status->humanSimpleServiceStatus($currentStateObjectData['Host']['Hoststatus']['current_state'])); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo __('Status since'); ?>
                                </div>
                                <div class="col-md-9">
                                    <?php
                                    echo h($this->Utils->secondsInHumanShort(time() - strtotime($currentStateObjectData['Host']['Hoststatus']['last_state_change'])));
                                    ?>
                                </div>
                                <div class="col-md-3">
                                    <?php echo __('Host output'); ?>
                                </div>
                                <div class="col-md-9">
                                    <?php echo h($currentStateObjectData['Host']['Hoststatus']['output']); ?>
                                </div>
                                <?php
                                ?>
                                <div class="col-md-12">
                                    <header role="heading">
                                        <h2 class="font-sm">
                                            <i class="fa fa-gears"></i>
                                            <?php echo h(__('Checks')); ?>
                                        </h2>
                                    </header>
                                    <div class="widget-body font-xs">
                                        <?php
                                        foreach ($currentStateObjectData['Host']['Services'] as $serviceData):
                                            $perfDataArray = $this->Perfdata->parsePerfData($serviceData['Servicestatus']['perfdata']);
                                            ?>
                                            <div class="row padding-5">
                                                <div class="col-md-3">
                                                    <i class="fa fa-square <?php echo $this->Status->ServiceStatusTextColor($serviceData['Servicestatus']['current_state']); ?>"> </i>
                                                    <?php
                                                    echo $this->Html->link(h($serviceData['Service']['name']), [
                                                        'action'     => 'browser',
                                                        'controller' => 'services',
                                                        $serviceData['Service']['id'],
                                                    ], [
                                                            'class' => 'txt-color-blueDark',
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?php
                                                    echo h($this->Utils->secondsInHumanShort(time() - strtotime($serviceData['Servicestatus']['last_state_change'])));
                                                    ?>
                                                </div>
                                                <div class="col-md-5">
                                                    <?php echo h($serviceData['Servicestatus']['output']); ?>
                                                </div>
                                                <?php


                                                ?>
                                                <div class="col-md-2">
                                                    <?php
                                                    if (!empty($perfDataArray) && isset($perfDataArray[0])):?>
                                                        <div class="col-md-10 text-center bordered perfdataContainer"
                                                            <?php echo implode(' ',
                                                                array_map(function ($value, $key) {
                                                                    return sprintf('%s="%s"', $key, preg_replace('/,/', '.', $value));
                                                                },
                                                                    $perfDataArray[0],
                                                                    array_keys($perfDataArray[0]))
                                                            );
                                                            ?>
                                                        >
                                                            <?php
                                                            echo $perfDataArray[0]['current_value'] . ' ' . $perfDataArray[0]['unit'];
                                                            ?>
                                                        </div>
                                                        <div class="col-md-2 no-padding text-right">
                                                            <i class="fa fa-plus-square-o font-md pointer perfdataContainerShowDetails"
                                                               uuid="<?php echo $serviceData['Service']['uuid']; ?>"></i>
                                                        </div>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            if (!empty($perfDataArray)):
                                                foreach ($perfDataArray as $perfData):?>
                                                    <div class="row padding-5 hidden <?php echo $serviceData['Service']['uuid']; ?>">
                                                        <div class="col-md-2 col-md-offset-8 text-right">
                                                            <?php echo $perfData['label']; ?>
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            <div class="col-md-10 text-center bordered perfdataContainer"
                                                                <?php echo implode(' ',
                                                                    array_map(function ($value, $key) {
                                                                        return sprintf('%s="%s"', $key, preg_replace('/,/', '.', $value));
                                                                    },
                                                                        $perfData,
                                                                        array_keys($perfData))
                                                                );
                                                                ?>
                                                            >
                                                                <?php echo $perfData['current_value'] . ' ' . $perfData['unit']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        <?php
                                        endforeach; ?>
                                    </div>
                                </div>
                                <?php
                                if (!empty($currentStateObjectData['Host']['ServicesNotMonitored'])):?>
                                    <div class="col-md-12">
                                        <header role="heading">
                                            <h2 class="font-sm">
                                                <i class="fa fa-gears"></i>
                                                <?php echo h(__('Checks (Not monitored)')); ?>
                                            </h2>
                                        </header>
                                        <div class="widget-body font-xs">
                                            <?php
                                            foreach ($currentStateObjectData['Host']['ServicesNotMonitored'] as $serviceData):?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <i class="fa fa-square text-primary"> </i>
                                                        <?php
                                                        echo $this->Html->link(h($serviceData['Service']['name']), [
                                                            'action'     => 'browser',
                                                            'controller' => 'services',
                                                            $serviceData['Service']['id'],
                                                        ], [
                                                                'class' => 'txt-color-blueDark',
                                                            ]
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php
                                            endforeach; ?>
                                        </div>
                                    </div>
                                <?php
                                endif; ?>
                            </div>
                        </div>
                    <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
    </div>
</div>
