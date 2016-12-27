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
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <h1 class="page-title <?php echo $this->Status->ServiceStatusColor($service['Service']['uuid']); ?>">
            <?php echo $this->Monitoring->serviceFlappingIcon($this->Status->sget($service['Service']['uuid'], 'is_flapping'), 'padding-left-5'); ?>
            <i class="fa fa-cog fa-fw"></i>
            <?php
            if ($service['Service']['name'] !== null && $service['Service']['name'] !== '') {
                echo $service['Service']['name'];
            } else {
                echo $service['Servicetemplate']['name'];
            }
            ?><span>
				&nbsp;<?php echo __('on'); ?>&nbsp;
				<a href="/hosts/browser/<?php echo $service['Host']['id']; ?>"><?php echo $service['Host']['name']; ?>
                    (<?php echo $service['Host']['address']; ?>)</a>
			</span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <h5>
            <div class="pull-right">
                <a href="/services/browser/<?php echo $service['Service']['id']; ?>" class="btn btn-primary btn-sm"><i
                            class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Service')); ?>
                </a>
                <?php echo $this->element('service_browser_menu'); ?>
            </div>
        </h5>
    </div>
</div>

<?php

$graphs = [
    [
        'label' => __('4 Hours'),
        'start' => strtotime('4 hours ago'),
        'end'   => time(),
    ],
    [
        'label' => __('25 Hours'),
        'start' => strtotime('25 hours ago'),
        'end'   => time(),
    ],
    [
        'label' => __('One week'),
        'start' => strtotime('1 week ago'),
        'end'   => time(),
    ],
    [
        'label' => __('One Month'),
        'start' => strtotime('1 month ago'),
        'end'   => time(),
    ],
    [
        'label' => __('One Year'),
        'start' => strtotime('1 year ago'),
        'end'   => time(),
    ],
];


$Rrd = ClassRegistry::init('Rrd');

$rrd_path = Configure::read('rrd.path');

require_once APP.'GrapherTemplates'.DS.$commandUuid.'.php';

foreach ($graphs as $graph):
    foreach ($templateSettings as $key => $templateSetting):
        $rrdOptions = [
            '--slope-mode',
            '--start', $graph['start'],
            '--end', $graph['end'],
            '--width', 850,
            '--color', 'BACK#FFFFFF',
            '--border', 1,
            '--imgformat', 'PNG',
        ];

        //Merging template settings to our default settings
        $rrdOptions = Hash::merge($rrdOptions, $templateSetting);

        $imageUrl = $Rrd->createRrdGraphFromTemplate($rrdOptions);

        $error = false;

        if (!isset($imageUrl['webPath'])):
            $errorImage = $this->Grapher->createGrapherErrorPng($imageUrl);
            $error = true;
        endif;
        ?>
        <div class="jarviswidget" id="wid-id-0">
            <header>
                <span class="widget-icon"> <i class="fa fa-area-chart"></i> </span>
                <h2><?php echo $graph['label']; ?> <span
                            class="text-muted padding-left-10 graphTime"><?php echo date('d.m.Y H:i', $graph['start']); ?>
                        - <?php echo date('d.m.Y H:i', $graph['end']); ?></span></h2>
                <div class="widget-toolbar" role="menu" style="display:none">
                    <a href="javascript:void(0);" class="btn btn-danger btn-xs resetZoom"
                       start="<?php echo h($graph['start']); ?>" end="<?php echo h($graph['end']); ?>"
                       ds="<?php echo h($key); ?>" service_id="<?php echo h($service['Service']['id']); ?>"
                       commandUuid="<?php echo h($commandUuid); ?>"><i
                                class="fa fa-search-minus"></i> <?php echo __('Reset zoom'); ?></a>
                </div>
            </header>
            <div>
                <div class="widget-body">
                    <div class="pull-left">
                        <div class="graphContainer">
                            <?php
                            if ($error === true):
                                echo $this->html->image($errorImage['webPath'], ['class' => 'img-responsive']);
                            else:
                                echo $this->html->image($imageUrl['webPath'], ['class' => 'zoomSelection img-responsive', 'start' => h($graph['start']), 'end' => h($graph['end']), 'service_id' => h($service['Service']['id']), 'ds' => h($key), 'commandUuid' => h($commandUuid)]);
                            endif;
                            ?>
                        </div>
                        <div class="grapherLoader text-center" style="display:none;"><i
                                    class="fa fa-spin fa-cog fa-5x"></i></div>
                    </div>
                    <div class="pull-left margin-left-10">

                    </div>
                    <div class="clearfix"></div>
                    <div stlye="padding-bottom: 13px;"><!-- padding spacer --></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
