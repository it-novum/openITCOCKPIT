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
<head>
    <?php
    $css = [
        '/css/vendor/bootstrap/css/bootstrap.css',
        '/css/vendor/bootstrap/css/bootstrap-theme.css',
        '/smartadmin/css/font-awesome.css',
        '/smartadmin/css/smartadmin-production.css',
        '/smartadmin/css/your_style.css',
        '/css/app.css',
        '/css/pdf_list_style.css',
        '/css/bootstrap_pdf.css',
    ];
    ?>
    <?php
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT . $cssFile; ?>"/>
    <?php
    endforeach; ?>
</head>
<body class="">
<div class="jarviswidget no-bordered">
    <div class="well no-bordered">
        <div class="row margin-top-10 font-md padding-bottom-10">
            <div class="col-md-9 text-left padding-left-20">
                <i class="fa fa-calendar txt-color-blueDark"></i>
                <?php
                echo __('Current state report ');
                echo h('(' . __('Date: ') . $this->Time->format(time(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')) . ')'); ?>
            </div>
            <div class="col-md-3 text-left">
                <img src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
            </div>
            <div class="col-md-12 padding-20">
                <?php
                if (sizeof($all_services) > 0):
                    foreach ($all_services as $hostId => $currentStateObjectData):
                        if (!empty($currentStateObjectData['Services'])):?>
                            <div class="jarviswidget col-md-12">
                                <header role="heading">
                                    <h2>
                                        <strong class="<?php echo $currentStateObjectData['Hoststatus']['humanState']; ?> font-lg">
                                            <i class="fa fa-desktop <?php echo $currentStateObjectData['Hoststatus']['humanState']; ?>"></i> <?php echo h($currentStateObjectData['Host']['hostname']);
                                            ?>
                                        </strong>
                                    </h2>
                                </header>
                                <div class="widget-body font-md">
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
                                        <?php echo h($this->Status->humanSimpleServiceStatus($currentStateObjectData['Hoststatus']['currentState'])); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo __('Status since'); ?>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        echo h($currentStateObjectData['Hoststatus']['lastCheck']);
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo __('Host output'); ?>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo h($currentStateObjectData['Hoststatus']['output']); ?>
                                    </div>
                                    <div class="col-md-12 padding-top-20 padding-bottom-10">
                                        <i class="fa fa-gears txt-color-blueDark"></i>
                                        <?php echo __('Checks'); ?>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="widget-body font-sm">
                                            <?php
                                            foreach ($currentStateObjectData['Services'] as $serviceData):
                                                $perfDataArray = $this->Perfdata->parsePerfData($serviceData['Servicestatus']['perfdata']);
                                                ?>
                                                <div class="row no-padding padding-top-5">
                                                    <div class="col-md-3">
                                                        <i class="fa fa-square <?php echo $this->Status->ServiceStatusTextColor($serviceData['Servicestatus']['currentState']); ?>"> </i>
                                                        <?php
                                                        echo h($serviceData['Service']['servicename']);
                                                        ?>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <?php
                                                        echo h($serviceData['Servicestatus']['lastCheck']);
                                                        ?>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <?php echo h($serviceData['Servicestatus']['output']); ?>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <?php
                                                        if (!empty($perfDataArray) && isset($perfDataArray[0])):?>
                                                            <div class="col-md-12 text-center">
                                                                <?php echo h($perfDataArray[0]['label']); ?>
                                                            </div>
                                                            <div class="col-md-12 text-center bordered <?php echo $this->Status->ServiceStatusColorSimple($serviceData['Servicestatus']['currentState'])['class']; ?>">
                                                                <strong class=" txt-color-white">
                                                                    <?php
                                                                    echo $perfDataArray[0]['current_value'] . ' ' . $perfDataArray[0]['unit'];
                                                                    ?>
                                                                </strong>
                                                            </div>
                                                        <?php
                                                        endif;
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php
                                            endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endif;
                    endforeach;
                else:?>
                    <i class="fa fa-lg fa-info-circle txt-color-blueDark"></i>
                    <span class="txt-color-blueDark">
                     <?php echo __('No entries match the selection'); ?>
                    </span>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</div>
</body>