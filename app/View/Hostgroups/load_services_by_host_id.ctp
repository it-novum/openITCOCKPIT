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

use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\HumanTime;

$statuscounter = [
    0 => 0,
    1 => 0,
    2 => 0,
    3 => 0
];

foreach($services as $service):
    $statuscounter[$service['Servicestatus']['current_state']]++;
endforeach;

?>

<tr class="<?php echo $hostgroup['Hostgroup']['uuid'].$host['Host']['uuid'];?>">
    <td colspan="10" class="no-padding text-right">

        <div class="col-md-12 pull-right">
            <div class="col-md-4"></div>
            <div class="col-md-2 btn-success">
                <div class="padding-5">
                    <label for="<?php echo $host['Host']['uuid'].'[0]'; ?>" class="no-padding pointer">
                        <input type="checkbox" name="<?php echo $host['Host']['uuid'].'[0]'; ?>" id="<?php echo $host['Host']['uuid'].'[0]'; ?>"class="no-padding pointer state_filter" state="0" checked="checked" uuid="<?php echo $host['Host']['uuid'].'_service'; ?>"/>
                        <strong>
                            <?php echo __('%s ok', $statuscounter[0]); ?>
                        </strong>
                    </label>
                </div>
            </div>
            <div class="col-md-2 btn-warning">
                <div class="padding-5">
                    <label for="<?php echo $host['Host']['uuid'].'[1]'; ?>" class="no-padding pointer">
                        <input type="checkbox" name="<?php echo $host['Host']['uuid'].'[1]'; ?>" id="<?php echo $host['Host']['uuid'].'[1]'; ?>"class="no-padding pointer state_filter" state="1" checked="checked" uuid="<?php echo $host['Host']['uuid'].'_service'; ?>"/>
                        <strong>
                            <?php echo __('%s warning', $statuscounter[1]); ?>
                        </strong>
                    </label>
                </div>
            </div>
            <div class="col-md-2 btn-danger">
                <div class="padding-5">
                    <label for="<?php echo $host['Host']['uuid'].'[2]'; ?>" class="no-padding pointer">
                        <input type="checkbox" name="<?php echo $host['Host']['uuid'].'[2]'; ?>" id="<?php echo $host['Host']['uuid'].'[2]'; ?>"class="no-padding pointer state_filter" state="2" checked="checked" uuid="<?php echo $host['Host']['uuid'].'_service'; ?>"/>
                        <strong>
                            <?php echo __('%s critical', $statuscounter[2]); ?>
                        </strong>
                    </label>
                </div>
            </div>
            <div class="col-md-2 bg-color-blueLight txt-color-white">
                <div class="padding-5">
                    <label for="<?php echo $host['Host']['uuid'].'[3]'; ?>" class="no-padding pointer">
                        <input type="checkbox" name="<?php echo $host['Host']['uuid'].'[3]'; ?>" id="<?php echo $host['Host']['uuid'].'[3]'; ?>"class="no-padding pointer state_filter" state="3" checked="checked" uuid="<?php echo $host['Host']['uuid'].'_service'; ?>"/>
                        <strong>
                            <?php echo __('%s unknown', $statuscounter[3]); ?>
                        </strong>
                    </label>
                </div>
            </div>
        </div>
    </td>
</tr>

<tr class="<?php echo $hostgroup['Hostgroup']['uuid'].$host['Host']['uuid'];?>">
    <td colspan="6"></td>
    <td>
        <?php
        echo $this->Form->input('servicename',[
            'label' => false,
            'placeholder' => __('Service'),
            'class' => 'padding-5',
            'search_id' => $hostgroup['Hostgroup']['uuid'].$host['Host']['uuid'],
            'filter' => 'true',
            'needle' => 'servicename'
        ]);
        ?>
    </td>
    <td>
        <?php
        echo $this->Form->input('status_since',[
            'label' => false,
            'placeholder' => __('Status since'),
            'class' => 'padding-5',
            'search_id' => $hostgroup['Hostgroup']['uuid'].$host['Host']['uuid'],
            'filter' => 'true',
            'needle' => 'status_since'
        ]);
        ?>
    </td>
    <td>
        <?php
        echo $this->Form->input('last_check',[
            'label' => false,
            'placeholder' => __('Last check'),
            'class' => 'padding-5',
            'search_id' => $hostgroup['Hostgroup']['uuid'].$host['Host']['uuid'],
            'filter' => 'true',
            'needle' => 'last_check'
        ]);
        ?>
    </td>
    <td>
        <?php
        echo $this->Form->input('next_check',[
            'label' => false,
            'placeholder' => __('Next check'),
            'class' => 'padding-5',
            'search_id' => $hostgroup['Hostgroup']['uuid'].$host['Host']['uuid'],
            'filter' => 'true',
            'needle' => 'next_check'
        ]);
        ?>
    </td>
</tr>

<?php
foreach($services as $service):
    $servicestatus = new Servicestatus($service['Servicestatus']);

    $class = sprintf(
        '%s%s %s_service state_%s',
        $hostgroup['Hostgroup']['uuid'],
        $host['Host']['uuid'],

        $host['Host']['uuid'],
        $servicestatus->currentState()
    );
    ?>

    <tr class="<?php echo $class; ?>">
        <td></td>
        <td class="text-center">
            <?php
            $serviceHref = 'javascript:void(0);';
            if($this->Acl->hasPermission('browser', 'services')):
                $serviceHref = '/services/browser/'.$service['Service']['id'];
            endif;

            if($servicestatus->isFlapping()):
                echo $servicestatus->getServiceFlappingIconColored();
            else:
                echo $servicestatus->getHumanServicestatus()['html_icon'];
            endif;
            ?>

        </td>
        <td>
            <?php if($servicestatus->isActiveChecksEnabled() === false): ?>
                <strong title="<?php echo __("Passively transferred service"); ?>">P</strong>
            <?php endif; ?>
        </td>
        <td>
            <?php if($servicestatus->isAacknowledged() && $servicestatus->currentState() > 0): ?>
                <i class="fa fa-user txt-color-blue"></i>
            <?php endif; ?>
        </td>
        <td>
            <?php if($servicestatus->isInDowntime()): ?>
                <i class="fa fa-power-off"></i>
            <?php endif; ?>
        </td>
        <td>
            <?php
            if($servicestatus->processPerformanceData()):
                if($this->Monitoring->checkForServiceGraph($host['Host']['uuid'], $service['Service']['uuid'])):
                    if($this->Acl->hasPermission('browser', 'services')): ?>
                        <a class="txt-color-blueDark" href="/services/grapherSwitch/<?php echo $service['Service']['id']; ?>"><i class="fa fa-area-chart fa-lg popupGraph" host-uuid="<?php echo $host['Host']['uuid']; ?>" service-uuid="<?php echo $service['Service']['uuid']; ?>"></i></a>
                    <?php else: ?>
                        <i class="fa fa-area-chart fa-lg popupGraph" host-uuid="<?php echo $host['Host']['uuid']; ?>" service-uuid="<?php echo $service['Service']['uuid']; ?>"></i>
                    <?php endif;
                endif;
            endif;
            ?>
        </td>
        <td search="servicename">
            <?php
            if($this->Acl->hasPermission('browser', 'services')): ?>
                <a href="<?php echo Router::url(['controller' => 'services', 'action' => 'browser', $service['Service']['id']]); ?>"><?php echo h($service['Service']['name']);?></a>
            <?php else:
                echo h($service['Service']['name']); //already servicetemplate name if service name is null!
            endif;
            ?>
        </td>
        <td search="status_since">
            <?php
            if($servicestatus->getLastHardStateChange()):
                echo h(HumanTime::secondsInHumanShort(time() - strtotime($servicestatus->getLastHardStateChange())));
            else:
                echo __('N/A');
            endif;
            ?>
        </td>
        <td search="last_check">
            <?php
            if($servicestatus->getLastCheck()):
                echo h($this->Time->format($servicestatus->getLastCheck(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
            else:
                echo __('N/A');
            endif;
            ?>
        </td>
        <td search="next_check">
            <?php
            if($servicestatus->getNextCheck()):
                echo h($this->Time->format($servicestatus->getNextCheck(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
            else:
                echo __('N/A');
            endif;
            ?>
        </td>
    </tr>
    <?php
    endforeach;


