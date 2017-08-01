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

use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementService;
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;

if (!isset($hoststatus['Hoststatus'])):
    $hoststatus['Hoststatus'] = [];
endif;
if (!isset($servicestatus['Servicestatus'])):
    $servicestatus['Servicestatus'] = [];
endif;
$Hoststatus = new Hoststatus($hoststatus['Hoststatus']);
$Servicestatus = new Servicestatus($servicestatus['Servicestatus']);

?>
<div id="error_msg"></div>
<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<?php if (!$QueryHandler->exists()): ?>
    <div class="alert alert-danger alert-block">
        <a href="#" data-dismiss="alert" class="close">×</a>
        <h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
        </h4>
        <?php echo __('File %s does not exists', $QueryHandler->getPath()); ?>
    </div>
<?php endif; ?>

<input type="hidden" id="serviceHasGraphs"
       value="<?php echo (int)$this->Monitoring->checkForServiceGraph($service['Host']['uuid'], $service['Service']['uuid']); ?>">
<div class="alert auto-hide alert-danger" id="flashFailed"
     style="display:none"><?php echo __('Error while sending command'); ?></div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <h1 class="status_headline <?php echo $Servicestatus->ServiceStatusColor(); ?>">
            <?php echo $Servicestatus->getServiceFlappingIconColored(); ?>
            <i class="fa fa-cog fa-fw"></i>
            <?php
            if ($service['Service']['name'] !== null && $service['Service']['name'] !== ''):
                echo h($service['Service']['name']);
            else:
                echo h($service['Servicetemplate']['name']);
            endif;
            ?><span>
                &nbsp;<?php echo __('on'); ?>&nbsp;
                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                    <a href="/hosts/browser/<?php echo $service['Host']['id']; ?>"><?php echo h($service['Host']['name']); ?>
                        (<?php echo h($service['Host']['address']); ?>)</a>
                <?php else: ?>
                    <?php echo h($service['Host']['name']); ?> (<?php echo h($service['Host']['address']); ?>)
                <?php endif; ?>
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <h5>
            <div class="pull-right">
                <?php echo $this->element('service_browser_menu'); ?>
            </div>
        </h5>
    </div>
</div>

<div class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
        <div data-widget-custombutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false"
             data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false"
             id="wid-id-11" class="jarviswidget jarviswidget-sortable" role="widget">
            <header role="heading">
                <h2 class="hidden-mobile hidden-tablet"><strong><?php echo __('Service'); ?>:</strong></h2>
                <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab"> <i class="fa fa-lg fa-info"></i> <span
                                    class="hidden-mobile hidden-tablet"> <?php echo __('Status information'); ?></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#tab2" data-toggle="tab"> <i class="fa fa-lg fa-hdd-o"></i> <span
                                    class="hidden-mobile hidden-tablet"> <?php echo __('Check information'); ?> </span></a>
                    </li>
                    <li class="">
                        <a href="#tab3" data-toggle="tab"> <i class="fa fa-lg fa-envelope-o"></i> <span
                                    class="hidden-mobile hidden-tablet"> <?php echo __('Notification information'); ?> </span></a>
                    </li>
                    <?php if ($allowEdit): ?>
                        <li class="">
                            <a href="#tab4" data-toggle="tab"> <i class="fa fa-lg fa-desktop"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Service commands'); ?> </span></a>
                        </li>
                    <?php endif; ?>
                </ul>
                <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
            <div role="content">
                <div class="widget-body no-pwing">
                    <div class="tab-content padding-10">
                        <div id="tab1" class="tab-pane fade active in">
                            <?php echo $service['Service']['name']; ?> <strong><?php echo __('Last state change') ?>
                                : <?php echo $this->Time->format(
                                    $Servicestatus->getLastStateChange(),
                                    $this->Auth->user('dateformat'),
                                    false,
                                    $this->Auth->user('timezone')
                                ); ?></strong>
                            <br/><br/>
                            <p><?php echo __('The last system check occurred at'); ?>
                                <strong><?php echo $this->Time->format(
                                        $Servicestatus->getLastCheck(),
                                        $this->Auth->user('dateformat'),
                                        false,
                                        $this->Auth->user('timezone')
                                    ); ?></strong>
                                <?php
                                if ($Servicestatus->isHardState()):
                                    echo '<span class="label text-uppercase ' . $Servicestatus->ServiceStatusBackgroundColor() . '">' . __('hard state') . '</span>';
                                else:
                                    echo '<span class="label text-uppercase opacity-50 ' . $Servicestatus->ServiceStatusBackgroundColor() . '" >' . __('soft state') . '</span>';
                                endif; ?>
                            </p>

                            <?php
                            if ($Servicestatus->isAcknowledged() && !empty($acknowledged)):
                                $AcknowledgementService = new AcknowledgementService($acknowledged['AcknowledgedService']);
                                ?>
                                <p>
                                    <span class="fa-stack fa-lg">
                                        <?php if ($Servicestatus->getAcknowledgementType() == 1): ?>
                                            <i class="fa fa-user fa-stack-2x"></i>
                                        <?php else: ?>
                                            <i class="fa fa-user-o fa-stack-2x"></i>
                                        <?php endif; ?>
                                        <i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-8"></i>
                                    </span>
                                    <?php
                                    if ($Servicestatus->getAcknowledgementType() == 1):
                                        echo __('The current status was already acknowledged by');
                                    else:
                                        echo __('The current status was already acknowledged (STICKY) by');
                                    endif; ?>
                                    <strong>
                                        <?php echo h($AcknowledgementService->getAuthorName()); ?>
                                    </strong>
                                    (<i class="fa fa-clock-o"></i>
                                    <?php
                                    echo $this->Time->format($AcknowledgementService->getEntryTime(),
                                        $this->Auth->user('dateformat'),
                                        false,
                                        $this->Auth->user('timezone')
                                    );
                                    ?>)
                                    <?php echo __('with the comment '); ?>
                                    "<?php
                                    $ticketDetails = [];
                                    if (!empty($ticketSystem['Systemsetting']['value']) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $AcknowledgementService->getCommentData(), $ticketDetails)):
                                        echo (isset($ticketDetails[1], $ticketDetails[3], $ticketDetails[2])) ?
                                            $this->Html->link(
                                                $ticketDetails[1] . ' ' . $ticketDetails[2],
                                                $ticketSystem['Systemsetting']['value'] . $ticketDetails[3],
                                                ['target' => '_blank']) :
                                            $AcknowledgementService->getCommentData();
                                    else:
                                        echo h($AcknowledgementService->getCommentData());
                                    endif;
                                    ?>".

                                </p>
                            <?php endif; ?>

                            <?php if ($Servicestatus->isInDowntime()): ?>
                                <p>
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-power-off fa-stack-2x"></i>
                                        <i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-5"></i>
                                    </span>
                                    <?php echo __('The service is currently in a planned maintenance period.'); ?>
                                    <br/><br/>
                                </p>
                            <?php endif; ?>
                            <?php if ($Hoststatus->currentState() > 0): ?>
                                <p class="parentstatus padding-left-10">
                                    <?php echo __('Problem with associated host'); ?> <a
                                            href="/hosts/browser/<?php echo $service['Host']['id']; ?>"><?php echo h($service['Host']['name']); ?></a> <?php echo __('detected'); ?>
                                    <br/>
                                    <?php
                                    $HoststatusIcon = new HoststatusIcon($Hoststatus->currentState());
                                    echo $HoststatusIcon->getHtmlIcon();
                                    ?>
                                    <span class="padding-left-5"
                                          style="vertical-align: middle;"><?php echo $HoststatusIcon->getHumanState(); ?></span>
                                    <code class="no-background <?php echo $Hoststatus->HostStatusColor(); ?>">
                                        (<?php echo h($Hoststatus->getOutput()); ?>)
                                    </code>
                                </p>
                            <?php endif; ?>
                            <?php if ($Hoststatus->isInDowntime()): ?>
                                <p class="parentstatus">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-power-off fa-stack-2x"></i>
                                        <i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-5"></i>
                                    </span>
                                    <?php echo __('The Host is currently in a planned maintenance period.'); ?>
                                </p>
                            <?php endif; ?>

                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td><strong><?php echo __('Current state'); ?>:</strong></td>
                                    <td>
                                        <?php
                                        $Icon = new ServicestatusIcon($Servicestatus->currentState());
                                        echo $Icon->getHtmlIcon();
                                        ?>
                                        <span class="padding-left-5" style="vertical-align: middle;">
                                            <?php echo $Icon->getHumanState(); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo __('Flap detection'); ?>:</strong>
                                    </td>
                                    <td>
                                        <?php echo $Servicestatus->compareHostFlapDetectionWithMonitoring($service['Service']['flap_detection_enabled'])['html']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Priority'); ?>:</strong></td>
                                    <td>
                                        <?php if(isset($service['Service']['priority'])):?>
                                            <?php for ($i = 1; $i < 6; $i++): ?>
                                                <?php if($i <= $service['Service']['priority']):?>
                                                    <i class="fa fa-fire" style="color:#3276B1; font-size:17px;"></i>
                                                <?php else:?>
                                                    <i class="fa fa-fire" style="color:#CCC; font-size:17px;"></i>
                                                <?php endif;
                                            endfor; ?>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <?php if (!$Servicestatus->isNotificationsEnabled()): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo __('Notifications'); ?>:</strong>
                                        </td>
                                        <td>
                                            <a data-original-title="<?php echo __('Difference to configuration detected'); ?>"
                                               data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i
                                                        class="fa fa-exclamation-triangle txt-color-orange"></i></a>
                                            <span class="label bg-color-redLight"><?php echo __('Temporary off'); ?></span>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <tr>
                                    <td><strong><?php echo __('Check attempt'); ?>:</strong></td>
                                    <td><?php echo h($Servicestatus->getCurrentCheckAttempt()); ?>
                                        /<?php echo h($service['Service']['max_check_attempts']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Command name'); ?>:</strong></td>
                                    <td>
                                        <?php if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== ''): ?>
                                            <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                                <a href="/commands/edit/<?php echo $service['CheckCommand']['id']; ?>"><?php echo h($service['CheckCommand']['name']); ?></a>
                                            <?php else: ?>
                                                <?php echo h($service['CheckCommand']['name']); ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                                <a href="/commands/edit/<?php echo $service['Servicetemplate']['CheckCommand']['id']; ?>"><?php echo h($service['Servicetemplate']['CheckCommand']['name']); ?></a>
                                            <?php else: ?>
                                                <?php echo h($service['Servicetemplate']['CheckCommand']['name']); ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ($this->Acl->hasPermission('checkcommand')): ?>
                                    <tr>
                                        <td><strong><?php echo __('Command line'); ?>:</strong></td>
                                        <td>
                                            <code class="no-background <?php echo $Servicestatus->ServiceStatusColor(); ?>">
                                                <?php
                                                if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== ''):
                                                    // The service has its own check command (not form service template)
                                                    //Replace host macros
                                                    $ServiceMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\HostMacroReplacer($service);
                                                    $ServiceCommandLine = $ServiceMacroReplacerCommandLine->replaceBasicMacros($service['CheckCommand']['command_line']);

                                                    $ServiceCustomMacroReplacer = new \itnovum\openITCOCKPIT\Core\CustomMacroReplacer($service['Customvariable'], OBJECT_SERVICE);
                                                    $ServiceCommandLine = $ServiceCustomMacroReplacer->replaceAllMacros($ServiceCommandLine);

                                                    $ServiceMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\ServiceMacroReplacer($service);
                                                    $ServiceCommandLine = $ServiceMacroReplacerCommandLine->replaceBasicMacros($ServiceCommandLine);
                                                    echo $this->Monitoring->replaceCommandArguments($commandarguments, $ServiceCommandLine);
                                                else:
                                                    $ServiceMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\HostMacroReplacer($service);
                                                    $ServiceCommandLine = $ServiceMacroReplacerCommandLine->replaceBasicMacros($service['Servicetemplate']['CheckCommand']['command_line']);

                                                    $ServiceCustomMacroReplacer = new \itnovum\openITCOCKPIT\Core\CustomMacroReplacer($service['Customvariable'], OBJECT_SERVICE);
                                                    $ServiceCommandLine = $ServiceCustomMacroReplacer->replaceAllMacros($ServiceCommandLine);

                                                    $ServiceMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\ServiceMacroReplacer($service);
                                                    $ServiceCommandLine = $ServiceMacroReplacerCommandLine->replaceBasicMacros($ServiceCommandLine);
                                                    echo $this->Monitoring->replaceCommandArguments($commandarguments, $ServiceCommandLine);
                                                endif;
                                                ?>
                                            </code>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong><?php echo __('Next check in'); ?>:</strong></td>
                                    <td>
                                        <?php if ($Servicestatus->isActiveChecksEnabled() && $service['Host']['satellite_id'] == 0): ?>
                                            <?php echo $this->Time->timeAgoInWords(
                                                $Servicestatus->getNextCheck(),
                                                ['timezone' => $this->Auth->user('timezone')]
                                            ); ?>
                                            <?php if ($Servicestatus->getLatency() > 1): ?>
                                                <span class="text-muted" title="<?php echo __('Check latency'); ?>">
                                                    (+<?php echo $Servicestatus->getLatency(); ?>)
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php
                                            if ($Servicestatus->isInMonitoring() === false):
                                                echo __('Not found in monitoring');
                                            else:
                                                echo __('n/a due to passive check');
                                            endif;
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Output'); ?>:</strong></td>
                                    <td>
                                        <code class="no-background <?php echo $Servicestatus->ServiceStatusColor(); ?>">
                                            <?php echo h($Servicestatus->getOutput()); ?>
                                        </code>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Performance data'); ?>:</strong></td>
                                    <td>
                                        <code class="no-background <?php echo $Servicestatus->ServiceStatusColor(); ?>">
                                            <?php echo h($Servicestatus->getPerfdata()); ?>
                                        </code>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <dl>
                                <dt><?php echo __('Long output'); ?>:</dt>
                                <?php $long_output = $Servicestatus->getLongOutput(); ?>
                                <?php if (!empty($long_output)): ?>
                                    <!-- removing HTML tags, so that we can display a preview witout breaking the page -->
                                    <dd>
                                        <div id="nag_longout_preview"><?php echo $this->Bbcode->nagiosNl2br(substr(strip_tags($this->Bbcode->asHtml($long_output)), 0, 200)); ?>
                                            <a href="javascript:void(0);"
                                               id="nagShowLongOutput"><?php echo __('...read more'); ?></a></div>
                                        <div id="nag_longoutput_container" style="display:none;">
                                            <div id="nag_longoutput_loader">
                                                <span class="text-center">
                                                    <h1>
                                                        <i class="fa fa-cog fa-lg fa-spin"></i>
                                                    </h1>
                                                    <br/>
                                                </span>
                                            </div>
                                            <div id="nag_longoutput_content"><!-- content loaded by ajax --></div>
                                        </div>
                                    </dd>
                                <?php else: ?>
                                    <dd>
                                        <code class="no-background <?php echo $Servicestatus->ServiceStatusColor(); ?>">
                                            <?php echo __('No long output available'); ?>
                                        </code>
                                    </dd>
                                <?php endif; ?>
                            </dl>


                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <strong><?php echo __('Current check attempt') ?>
                                :</strong> <?php echo $Servicestatus->getCurrentCheckAttempt(); ?>
                            <br/>
                            <strong><?php echo __('Maximum attempts per check') ?>
                                :</strong> <?php echo h($service['Service']['max_check_attempts']); ?><br/>
                            <strong><?php echo __('Check period') ?>:</strong>
                            <?php
                            if ($service['CheckPeriod']['name'] !== '' && $service['CheckPeriod']['name'] !== null):
                                echo h($service['CheckPeriod']['name']);
                            else:
                                echo h($service['Servicetemplate']['CheckPeriod']['name']);
                            endif;
                            ?>
                            <br/>
                            <strong><?php echo __('Check interval') ?>:</strong>
                            <?php echo $this->Utils->secondsInHumanShort($service['Service']['check_interval']); ?>
                            <br/>
                            <strong><?php echo __('Check interval in case of error'); ?>
                                :</strong> <?php echo $this->Utils->secondsInHumanShort($service['Service']['retry_interval']); ?>
                            <br/>
                            <strong><?php echo __('Active checks enabled') ?>:</strong>
                            <?php if ($service['Service']['active_checks_enabled'] == 1): ?>
                                <i class="fa fa-check text-success"></i>
                            <?php else: ?>
                                <i class="fa fa-times text-danger"></i>
                            <?php endif; ?>
                            <br/>

                            <br/>
                            <br/>
                            <strong><?php echo __('UUID') ?>:</strong>
                            <code><?php echo $service['Service']['uuid']; ?></code>&nbsp;
                            <span class="btn btn-default btn-xs" iconcolor="white"
                                  onclick="$('#service-uuid-copy').show().select();document.execCommand('copy');$('#service-uuid-copy').hide();"><?php echo __('Copy to clipboard'); ?></span>
                            <input style="display:none;" type="text" id="service-uuid-copy"
                                   value="<?php echo $service['Service']['uuid']; ?>"><br/>
                            <strong><?php echo __('Description'); ?>:</strong><br/>
                            <i class="txt-color-blue"><?php echo h($service['Service']['description']); ?></i><br>
                            <strong><?php echo __('Notes'); ?>:</strong><br/>
                            <span class="txt-color-blue"><?php echo h($service['Service']['notes']); ?></span>
                        </div>
                        <div id="tab3" class="tab-pane fade">
                            <strong><?php echo __('Notification period:'); ?></strong>
                            <?php
                            if ($service['NotifyPeriod']['name'] !== null && $service['NotifyPeriod']['name'] !== ''):
                                echo h($service['NotifyPeriod']['name']);
                            else:
                                echo h($service['Servicetemplate']['NotifyPeriod']['name']);
                            endif;
                            ?>
                            <br/>
                            <strong><?php echo __('Notification interval:'); ?></strong>
                            <?php echo $this->Utils->secondsInHumanShort($service['Service']['notification_interval']); ?>
                            <br/>
                            <br/>
                            <dl>
                                <dt><?php echo __('Notification occurs in the following cases'); ?>:</dt>
                                <?php echo $this->Monitoring->formatNotifyOnService([
                                    'notify_on_critical' => $service['Service']['notify_on_critical'],
                                    'notify_on_unknown' => $service['Service']['notify_on_unknown'],
                                    'notify_on_warning' => $service['Service']['notify_on_warning'],
                                    'notify_on_recovery' => $service['Service']['notify_on_recovery'],
                                    'notify_on_flapping' => $service['Service']['notify_on_flapping'],
                                    'notify_on_downtime' => $service['Service']['notify_on_downtime'],
                                ]); ?>
                            </dl>
                            <?php
                            if ($ContactsInherited['inherit'] === true):
                                switch ($ContactsInherited['source']):
                                    case 'Host':
                                        if ($this->Acl->hasPermission('edit', 'hosts')):
                                            $source = __('Service') . ' <i class="fa fa-arrow-right"></i> ' . __('Servicetemplate') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/hosts/edit/' . $service['Host']['id'] . '">' . __('Host') . '</a></strong>';
                                        else:
                                            $source = __('Service') . ' <i class="fa fa-arrow-right"></i> ' . __('Servicetemplate') . ' <i class="fa fa-arrow-right"></i> <strong>' . __('Host') . '</strong>';
                                        endif;
                                        break;

                                    case 'Hosttemplate':
                                        if ($this->Acl->hasPermission('edit', 'hosttemplates')):
                                            $source = __('Service') . ' <i class="fa fa-arrow-right"></i> ' . __('Servicetemplate') . ' <i class="fa fa-arrow-right"></i> ' . __('Host') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/hosttemplates/edit/' . $service['Host']['hosttemplate_id'] . '">' . __('Hosttemplate') . '</a></strong>';
                                        else:
                                            $source = __('Service') . ' <i class="fa fa-arrow-right"></i> ' . __('Servicetemplate') . ' <i class="fa fa-arrow-right"></i> ' . __('Host') . ' <i class="fa fa-arrow-right"></i> <strong>' . __('Hosttemplate') . '</strong>';
                                        endif;
                                        break;

                                    case 'Servicetemplate':
                                        if ($this->Acl->hasPermission('edit', 'hosttemplates')):
                                            $source = __('Service') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/servicetemplates/edit/' . $service['Service']['servicetemplate_id'] . '">' . __('Servicetemplate') . '</a></strong>';
                                        else:
                                            $source = __('Service') . ' <i class="fa fa-arrow-right"></i> <strong>' . __('Servicetemplate') . '</strong>';
                                        endif;
                                        break;
                                    default:
                                        $source = '???';
                                endswitch; ?>
                                <span class="text-info"><i
                                            class="fa fa-info-circle"></i> <?php echo __('Contacts and Contactgroups are inherited in the following order:'); ?> <?php echo $source; ?></span>
                                <?php
                            endif;
                            ?>
                            <?php if (!empty($ContactsInherited['Contact'])): ?>
                                <dl>
                                    <dt><?php echo __('The following contacts are notified'); ?>:</dt>
                                    <dd>
                                        <?php foreach ($ContactsInherited['Contact'] as $contact_id => $contact):
                                            if ($this->Acl->hasPermission('edit', 'contacts')):
                                                $_contacts[] = '<a href="/contacts/edit/' . $contact_id . '">' . h($contact) . '</a>';
                                            else:
                                                $_contacts[] = h($contact);
                                            endif;
                                        endforeach;
                                        echo implode(', ', $_contacts);
                                        unset($_contacts); ?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <?php if (!empty($ContactsInherited['Contactgroup'])): ?>
                                <dl>
                                    <dt><?php echo __('The following contact groups are notified'); ?>:</dt>
                                    <dd>
                                        <?php foreach ($ContactsInherited['Contactgroup'] as $contactgroup_id => $contactgroup):
                                            if ($this->Acl->hasPermission('edit', 'contacts')):
                                                $_contactgroups[] = '<a href="/contactgroups/edit/' . $contactgroup_id . '">' . h($contactgroup) . '</a>';
                                            else:
                                                $_contactgroups[] = h($contactgroup);
                                            endif;
                                        endforeach;
                                        echo implode(', ', $_contactgroups);
                                        unset($_contactgroups); ?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            </dl>
                        </div>
                        <div id="tab4" class="tab-pane fade">
                            <?php if (!$Servicestatus->isInMonitoring()): ?>
                                <div class="alert alert-info alert-block">
                                    <a class="close" data-dismiss="alert" href="#">×</a>
                                    <h4 class="alert-heading"><i
                                                class="fa fa-info-circle"></i> <?php echo __('Service not found in monitoring'); ?>
                                    </h4>
                                    <?php echo __('Due to the service is not available for the monitoring engine, you can not send commands.'); ?>
                                </div>
                            <?php else: ?>
                                <h5><?php
                                    echo $this->Form->input('satellite_id', ['type' => 'hidden', 'value' => $service['Host']['satellite_id'], 'id' => 'nag_commandSatelliteId']); ?>
                                    <span class="nag_command submitRescheduleService">
                                        <i class="fa fa-refresh"></i>
                                        <?php echo __('Reset check time'); ?>
                                    </span>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_passive_result"><i
                                                class="fa fa-download"></i> <?php echo __('Passive transfer of check results') ?> </span><br/>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_schedule_downtime"><i
                                                class="fa fa-power-off"></i> <?php echo __('Set planned maintenance times'); ?> </span><br/>
                                </h5>
                                <?php if ($Servicestatus->currentState() > 0): ?>
                                    <h5><span class="nag_command" data-toggle="modal"
                                              data-target="#nag_command_ack_state"><i
                                                    class="fa fa-user"></i> <?php echo __('Acknowledge service status'); ?> </span><br/>
                                    </h5>
                                <?php endif; ?>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_flap_detection"><i
                                                class="fa fa-adjust"></i> <?php echo __('Enables/disables flap detection for a particular service'); ?> </span><br/>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_notifications"><i
                                                class="fa fa-envelope-o"></i> <?php echo __('Enables/disables notifications'); ?> </span><br/>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_custom_notification"><i
                                                class="fa fa-envelope"></i> <?php echo __('Send custom service notification'); ?> </span><br/>
                                </h5>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="widget-footer text-right"></div>
                </div>
            </div>
        </div>
    </article>
</div>

<?php if ($this->Monitoring->checkForServiceGraph($service['Host']['uuid'], $service['Service']['uuid'])): ?>
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
            <div data-widget-custombutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false"
                 data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false"
                 id="wid-id-11" class="jarviswidget jarviswidget-sortable" role="widget">
                <header role="heading">
                    <h2 class="hidden-mobile hidden-tablet"><strong><?php echo __('Service graphs'); ?>:</strong></h2>
                    <?php
                    reset($serviceValues);
                    echo $this->Form->input('graph-filter-value', [
                        'options' => $serviceValues,
                        'SelectionMode' => 'single',
                        'class' => 'chosen',
                        'label' => [
                            'class' => 'hidden'
                        ],
                        'selected' => key($serviceValues),
                        'div' => [
                            'class' => 'widget-toolbar pull-left hidden-mobile padding-left-10',
                            'role' => 'menu',
                            'style' => 'border-left:none'
                        ]
                    ]);
                    ?>
                    <div class="widget-toolbar pull-left hidden-mobile" role="menu">
                        <span style="line-height: 32px;" class="pull-left"><?php echo __('From:'); ?></span>
                        <input id="graph-filter-from" class="form-control text-center pull-left margin-left-10"
                               style="width: 78%;"
                               type="text" maxlength="255"
                               value="<?php echo date('d-m-Y H:i', strtotime(date('d-m-Y H:i', time()) . ' -1 day')); ?>"
                               name="data[Listsettings][from]">
                    </div>

                    <div class="widget-toolbar pull-left hidden-mobile" role="menu">
                        <span style="line-height: 32px;" class="pull-left"><?php echo __('To:'); ?></span>
                        <input id="graph-filter-to" class="form-control text-center pull-left margin-left-10"
                               style="width: 85%;"
                               type="text" maxlength="255" value="<?= date('d-m-Y H:i') ?>"
                               name="data[Listsettings][to]">
                    </div>
                    <button id="apply-graph-filter" class="btn btn-xs btn-success toggle hidden-mobile margin-top-5"><i
                                class="fa fa-check"></i> <?php echo __('Apply'); ?></button>
                    <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
                <!-- widget div-->
                <div role="content">
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <!-- widget body text-->
                        <div class="padding-35">
                            <div id="graph_loader">
                                <center><i class="fa fa-cog fa-4x fa-spin"></i></center>
                            </div>
                            <div id="graph_data_tooltip"></div>
                            <div id="graph">
                                <!-- content added by JavaScript GraphComponent -->
                            </div>
                        </div>
                        <!-- end widget body text-->
                        <!-- widget footer -->
                        <div class="widget-footer text-right"></div>
                        <!-- end widget footer -->
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
        </article>
    </div>
<?php endif; ?>

<div class="modal fade" id="nag_command_passive_result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Passive transfer of check results'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitPassiveResult', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('test alert'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->input('status', ['options' => [0 => __('Ok'), 1 => __('Warning'), 2 => __('Critical'), 3 => __('Unknown')], 'label' => __('Status') . ':']); ?>
                    <?php echo $this->Form->fancyCheckbox('forceHardstate', ['caption' => __('Force to hard state?'), 'on' => __('true'), 'off' => __('false')]); ?>
                    <?php echo $this->Form->input('repetitions', ['type' => 'hidden', 'value' => $service['Service']['max_check_attempts']]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitCommitPassiveResult">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>


<div class="modal fade" id="nag_command_schedule_downtime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Set planned maintenance times'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="txt-color-red padding-bottom-20" id="validationErrorServiceDowntime"
                         style="display:none;"><i
                                class="fa fa-exclamation-circle"></i> <?php echo __('Please enter a valide date'); ?>
                    </div>
                    <?php
                    echo $this->Form->create('CommitServiceDowntime', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>
                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitServiceDowntimeFromDate"><?php echo __('From'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitServiceDowntimeFromDate" value="<?php echo date('d.m.Y'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][from_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitServiceDowntimeFromTime" value="<?php echo date('H:i'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][from_time]">
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitServiceDowntimeToDate"><?php echo __('To'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitServiceDowntimeToDate"
                                   value="<?php echo date('d.m.Y'); ?>" class="form-control"
                                   name="data[CommitServiceDowntime][to_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitServiceDowntimeToTime"
                                   value="<?php echo date('H:i', time() + 60 * 15); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][to_time]">
                        </div>
                    </div>

                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addServicedowntime', 'service_id' => $service['Service']['id']]); ?>"
                   class="btn btn-primary pull-left"><i class="fa fa-cogs"></i> <?php echo __('More options'); ?></a>
                <button type="button" class="btn btn-success" id="submitCommitServiceDowntime">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>


<div class="modal fade" id="nag_command_flap_detection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Enables/disables flap detection'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('enableOrDisableHostFlapdetection', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <span class="hintmark">
                            <?php if ($Servicestatus->compareHostFlapDetectionWithMonitoring($service['Service']['flap_detection_enabled'])['value'] == 0): ?>
                                <?php echo __('Yes, i want temporarily <strong>enable</strong> flap detection.'); ?>
                            <?php else: ?>
                                <?php echo __('Yes, i want temporarily <strong>disable</strong> flap detection.'); ?>
                            <?php endif; ?>
                            <?php echo $this->Form->input('condition', ['type' => 'hidden', 'value' => ($Servicestatus->compareHostFlapDetectionWithMonitoring($service['Service']['flap_detection_enabled'])['value'] == 1) ? 0 : 1]); ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"
                        id="submitEnableOrDisableHostFlapdetection">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>


<div class="modal fade" id="nag_command_custom_notification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Send custom service notification'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitCustomServiceNotification', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('test notification'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->fancyCheckbox('forced', ['caption' => __('Forced'), 'on' => __('true'), 'off' => __('false'), 'checked' => 'checked']); ?>
                    <?php echo $this->Form->fancyCheckbox('broadcast', ['caption' => __('Broadcast'), 'on' => __('true'), 'off' => __('false'), 'checked' => 'checked']); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitCustomServiceNotification">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_ack_state" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Acknowledge service status'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitServicestateAck', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky'), 'wrapInput' => 'col-md-offset-2 col-md-10']); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitServicestateAck">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_notifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Disable notifications'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('enableNotifications', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('isEnabled', ['type' => 'hidden', 'value' => (int)$Servicestatus->isNotificationsEnabled()]); ?>
                    <center>
                        <span class="hintmark">
                            <?php
                            if ($Servicestatus->isNotificationsEnabled() == false):
                                echo __('Yes, i want temporarily <strong>enable</strong> notifications.');
                            else:
                                echo __('Yes, i want temporarily <strong>disable</strong> notifications.');
                            endif;
                            ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitEnableNotifications">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php echo $this->element('qrmodal'); ?>
