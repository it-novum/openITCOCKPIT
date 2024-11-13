<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
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

/**
 * @var \App\View\AppView $this
 * @var string $systemname
 * @var bool $noAttachments
 * @var bool $noEmoji
 * @var \itnovum\openITCOCKPIT\Core\Views\Host $Host
 * @var \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon $HoststatusIcon
 * @var \itnovum\openITCOCKPIT\Core\Views\Service $Service
 * @var \itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon $ServicestatusIcon
 * @var \Cake\Console\Arguments $args
 * @var string $systemAddress
 * @var string $ticketsystemUrl
 * @var array $charts
 *
 * @var null|array $evcTree
 */

echo $this->element('emails/style');

?>
<!-- ########## EMAIL CONTENT ############### -->

<body bgcolor="#FFFFFF">

<!-- HEADER -->
<table class="head-wrap">
    <tr>
        <td></td>
        <td class="header container">

            <div class="content">
                <table>
                    <tr>
                        <td>
                            <?php if ($noAttachments === false): ?>
                                <img src="cid:100" width="60"/>
                            <?php endif; ?>
                        </td>
                        <td align="right">
                            <h6><?= __('{0} notification', $systemname) ?></h6>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td align="right" class="notification_type">
                            <?= h($args->getOption('notificationtype')) ?>
                        </td>
                    </tr>
                </table>
            </div>

        </td>
        <td></td>
    </tr>
</table>
<!-- /HEADER -->

<!-- BODY -->
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">

            <div class="content">
                <table>
                    <tr>
                        <td>
                            <h5>
                                <?php if ($noEmoji === false): ?>
                                    <?= $ServicestatusIcon->getEmoji() ?>
                                    &nbsp;
                                <?php endif; ?>
                                <span>
                                    <a href="<?php printf('https://%s/#!/services/browser/%s', $systemAddress, $Service->getUuid()); ?>"
                                       style="text-decoration:none"
                                       class="<?= strtoupper($ServicestatusIcon->getTextColor()) ?>">
                                            <?php echo h($Service->getServicename()); ?>
                                    </a>
                                    <?= __('on') ?>
                                    <a href="<?php printf('https://%s/#!/hosts/browser/%s', $systemAddress, $Host->getUuid()); ?>"
                                       style="text-decoration:none"
                                       class="<?= strtoupper($HoststatusIcon->getTextColor()) ?>">
                                        <?php echo h($Host->getHostname()); ?>
                                    </a>
                                    <?= __(' is {0}', $ServicestatusIcon->getHumanState()); ?>
                                </span>
                            </h5>
                            <hr noshade width="560" size="3" align="left">
                            <br>
                            <table width="100%">
                                <?php if ($args->getOption('notificationtype') === 'ACKNOWLEDGEMENT'): ?>
                                    <tr>
                                        <td colspan="2">
                                            <i class="fa fa-user fa-stack-2x"></i>
                                            <strong>
                                                <?= __('The current status was acknowledged by {0} with the comment: ', h($args->getOption('serviceackauthor'))); ?>
                                                <?php if (!empty($ticketsystemUrl) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $args->getOption('serviceackcomment'), $ticketDetails)): ?>
                                                    <a
                                                        href="<?= $ticketsystemUrl . $ticketDetails[3] ?>"
                                                        target="_blank">
                                                        <?= h($ticketDetails[1] . ' ' . $ticketDetails[2]) ?>
                                                    </a>
                                                <?php else: ?>
                                                    "<?= h($args->getOption('serviceackcomment')); ?>"
                                                <?php endif; ?>
                                            </strong>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong><?php echo __('Time'); ?>:</strong></td>
                                    <td><?php echo date('H:i:s T'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Service name'); ?>:</strong></td>
                                    <td><?= h($Service->getServicename()) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Service description'); ?>:</strong></td>
                                    <td><?= h($Service->getDescription()) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Host name'); ?>:</strong></td>
                                    <td><?= h($Host->getHostname()) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Host description'); ?>:</strong></td>
                                    <td><?= h($Host->getDescription()) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Host address'); ?>:</strong></td>
                                    <td><?= h($Host->getAddress()) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('State'); ?>:</strong></td>
                                    <td class=<?= strtoupper($ServicestatusIcon->getTextColor()) ?>>
                                        <?= h($ServicestatusIcon->getHumanState()); ?>
                                    </td>
                                </tr>
                                <?php if($ServicestatusIcon->getState() !== 0): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php printf('https://%s/#!/services/browser/%s%s', $systemAddress, $Service->getUuid(), '#acknowledge'); ?>"
                                               style="text-decoration:none"
                                               class="<?= strtoupper($ServicestatusIcon->getTextColor()) ?>">
                                                <?php echo __('Acknowledge'); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                            <br/>
                            <strong><?php echo __('Output'); ?>:</strong>
                            <p class="lead"> <?= h($args->getOption('serviceoutput')); ?> </p>
                            <br/>
                            <?php if ($args->getOption('servicelongoutput') !== ''): ?>
                                <strong><?php echo __('Service long output'); ?>:</strong>

                                <?php
                                $BBCodeParser = new \itnovum\openITCOCKPIT\Core\Views\BBCodeParser();
                                $longoutputHtml = $BBCodeParser->nagiosNl2br($BBCodeParser->asHtml(h($args->getOption('servicelongoutput')), true));
                                ?>

                                <p class="lead"> <?php echo $longoutputHtml; ?> </p>
                                <br/>
                            <?php endif; ?>

                            <?php if ($noAttachments === false): ?>

                                <?php
                                if (\Cake\Core\Plugin::isLoaded('EventcorrelationModule') && $Service->getServiceType() === EVK_SERVICE):
                                    $EvcTableRenderer = new \EventcorrelationModule\EvcTableRenderer(
                                        $evcTree,
                                        $Service->getUuid(),
                                        !$noEmoji
                                    );
                                    echo $EvcTableRenderer->getEvcDataAsTable();
                                    echo '<br>';
                                endif; ?>


                                <table class="social" style="width:100%;">
                                    <?php foreach ($charts as $filename => $chart): ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <img src="cid:<?= h($chart['contentId']) ?>" alt='<?= h($filename); ?>'
                                                     style="background:#fff;background-color:#fff;padding-top:15px;"/>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>

                            <br/>
                            <?php if ($ServicestatusIcon->getState() !== 0): ?>
                                <!--
                                --- BEGIN ACK INFORMATION ---
                                ACK_HOSTNAME: <?= h($Host->getHostname()); ?>
                                <?= PHP_EOL; ?>
                                ACK_HOSTUUID: <?= $Host->getUuid(); ?>
                                <?= PHP_EOL; ?>
                                ACK_SERVICEDESC: <?= h($Service->getServicename()); ?>
                                <?= PHP_EOL; ?>
                                ACK_SERVICEUUID: <?= $Service->getUuid(); ?>
                                <?= PHP_EOL; ?>
                                ACK_STATE: <?= h($args->getOption('servicestate')); ?>
                                <?= PHP_EOL; ?>
                                ACK_NOTIFICATIONTYPE: SERVICE
                                --- END ACK INFORMATION ---
                                -->
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table>
<table class="footer-wrap">
    <tr>
        <td></td>
        <td class="container">
            <div class="content">
                <table>
                    <tr>
                        <hr noshade width="560" size="3" align="center">
                        <br>
                        <td style="text-align: center;">
                            <p>
                                <a href="https://openitcockpit.io/"><?php echo __('openITCOCKPIT'); ?></a> |
                                <a href="https://it-novum.com/"><?php echo __('it-novum'); ?></a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <p>
                                <?php echo date('l jS \of F Y'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table>

<?php

// Disable PhpStorm code reformatting
// You need to enable "Enable formatter markers in comments" in your PhpStorm settings!!!
// See: https://stackoverflow.com/a/24438712
//
// @formatter:off
?>

<!--
--- BEGIN TICKET SYSTEM INFORMATION ---
TICKET_HOSTNAME: <?= h($Host->getHostname()); ?>
<?= PHP_EOL; ?>
TICKET_HOSTUUID: <?= $Host->getUuid(); ?>
<?= PHP_EOL; ?>
TICKET_SERVICEDESC: <?= h($Service->getServicename()); ?>
<?= PHP_EOL; ?>
TICKET_SERVICEUUID: <?= $Service->getUuid(); ?>
<?= PHP_EOL; ?>
TICKET_STATE: <?= h($args->getOption('servicestate')); ?>
<?= PHP_EOL; ?>
TICKET_NOTIFICATIONTYPE: SERVICE
TICKET_COMMAND_NUMBER: 34
--- END TICKET SYSTEM INFORMATION ---

<?php if ($ServicestatusIcon->getState() !== 0): ?>
--- BEGIN ACK2 INFORMATION ---
ACK_HOSTNAME: <?= h($Host->getHostname()); ?>
<?= PHP_EOL; ?>
ACK_HOSTUUID: <?= $Host->getUuid(); ?>
<?= PHP_EOL; ?>
ACK_SERVICEDESC: <?= h($Service->getServicename()); ?>
<?= PHP_EOL; ?>
ACK_SERVICEUUID: <?= $Service->getUuid(); ?>
<?= PHP_EOL; ?>
ACK_STATE: <?= h($args->getOption('servicestate')); ?>
<?= PHP_EOL; ?>
ACK_NOTIFICATIONTYPE: SERVICE
--- END ACK2 INFORMATION ---
<?php endif; ?>
-->

<?php //@formatter:on ?>
