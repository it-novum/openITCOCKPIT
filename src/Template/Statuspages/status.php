<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statuspage $statuspage
 */

use itnovum\openITCOCKPIT\Core\Views\Logo;

$logo = new Logo();
?>

<div ng-controller="StatuspagesViewController">
    <header id="header" class="page-header" role="banner" style="background-color: #fff; background-image: none;">
        <!-- we need this logo when user switches to nav-function-top -->
        <div class="page-logo">
            <a href="/"
               class="page-logo-link press-scale-down d-flex align-items-center position-relative">
                <img src="<?= $logo->getHeaderLogoForHtml(); ?>" alt="SmartAdmin WebApp" aria-roledescription="logo">
                <span class="page-logo-text mr-1" style="color: #000;"><?= $systemname; ?></span>
                <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
            </a>
        </div>
        <div class="ml-auto d-flex">

        </div>
    </header>

    <div class="container" style="margin-top:75px;">
        <div class="d-flex justify-content-center ">
            <div class="jumbotron w-100 bg-white padding-bottom-2 margin-bottom-25"
                 style="border: 1px solid rgba(0,0,0,.125);">
                <h1 class="display-4"><?= $Statuspage['statuspage']['name']; ?></h1>
                <p class="lead"><?= $Statuspage['statuspage']['description']; ?></p>
                <hr class="my-4">
                <?php if ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'up' || $Statuspage['statuspage']['cumulatedState']['humanState'] == 'ok'): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check"></i>
                        <?= __('All systems operational'); ?>
                    </div>
                <?php elseif ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'warning'): ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= __('Performance Issues') ?>
                    </div>
                <?php elseif ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'critical' || $Statuspage['statuspage']['cumulatedState']['humanState'] == 'down'): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-times"></i>
                        <?= __('Partial Outage') ?>
                    </div>
                <?php elseif ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'unreachable' || $Statuspage['statuspage']['cumulatedState']['humanState'] == 'unknown'): ?>
                    <div class="alert alert-secondary bg-unknown txt-color-white " role="alert">
                        <i class="fas fa-times"></i>
                        <?= __('Unknown') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-flex justify-content-center margin-bottom-25">
            <div class="row w-100">
                <?php foreach ($Statuspage as $key => $item):
                    if ($key == 'statuspage') {
                        continue;
                    }
                    foreach ($item as $subKey => $obj):
                        if ($key === 'hosts') {
                            if ($obj['currentState'] < $obj['cumulatedServiceState']) {
                                $obj['humanState'] = $obj['cumulatedServiceHumansState'];
                            }
                        }

                        $statusmessage = '';
                        $ackMessage = '';
                        $downtimeMessage = '';
                        switch ($key) {
                            case 'hosts':

                                if ($obj['currentState'] < $obj['cumulatedServiceState']) {
                                    $obj['humanState'] = $obj['cumulatedServiceHumansState'];
                                }

                                if ($obj['serviceAcknowledged']) {
                                    $ackMessage = __('There is a acknowledged service');
                                }
                                if ($obj['acknowledged']) {
                                    $ackMessage = __($obj['name'] . ' is acknowledged');
                                }
                                if ($obj['serviceInDowntime']) {
                                    $downtimeMessage = __('There is a service in downtime');
                                }
                                if ($obj['inDowntime']) {
                                    $downtimeMessage = __($obj['name'] . ' is in downtime');
                                }

                                $statusmessage = $ackMessage;
                                if (!empty($downtimeMessage)) {
                                    $statusmessage = $downtimeMessage;
                                }
                                break;
                            case 'services':
                                if ($obj['acknowledged']) {
                                    $ackMessage = __($obj['name'] . ' is acknowledged');
                                }
                                if ($obj['inDowntime']) {
                                    $downtimeMessage = __($obj['name'] . ' is in downtime');
                                }

                                $statusmessage = $ackMessage;
                                if (!empty($downtimeMessage)) {
                                    $statusmessage = $downtimeMessage;
                                }
                                break;
                            case 'hostgroups':
                                if ($obj['acknowledged']) {
                                    $ackMessage = __('Hosts of Hostgroup: ' . $obj['name'] . ' are acknowledged');
                                }
                                if ($obj['inDowntime']) {
                                    $downtimeMessage = __('Hosts of Hostgroup: ' . $obj['name'] . ' are in downtime');
                                }

                                $statusmessage = $ackMessage;
                                if (!empty($downtimeMessage)) {
                                    $statusmessage = $downtimeMessage;
                                }
                                break;
                            case 'servicegroups':
                                if ($obj['acknowledged']) {
                                    $ackMessage = __('Hosts of Servicegroup: ' . $obj['name'] . ' are acknowledged');
                                }
                                if ($obj['inDowntime']) {
                                    $downtimeMessage = __('Hosts of Servicegroup: ' . $obj['name'] . ' are in downtime');
                                }

                                $statusmessage = $ackMessage;
                                if (!empty($downtimeMessage)) {
                                    $statusmessage = $downtimeMessage;
                                }
                                break;
                        }
                        ?>
                        <div class="col-sm-6 no-padding">
                            <div class="card" style="min-height: 95px;">
                                <div class="card-body">
                                    <h5 class="card-text d-flex">
                                        <span class="text-wrap"><?= $obj['name'] ?></span>
                                        <div class="ml-auto">
                                            <?php if ($obj['inDowntime']): ?>
                                                <i class="fa fa-power-off fa-xl"></i>
                                            <?php endif; ?>
                                            <?php if ($obj['acknowledged']): ?>
                                                <i class="fas fa-user fa-xl"></i>
                                            <?php endif; ?>
                                            <?php if ($obj['humanState'] == 'up' || $obj['humanState'] == 'ok'): ?>
                                                <i class="fas fa-check-circle fa-xl text-success ml-auto"></i>
                                            <?php elseif ($obj['humanState'] == 'warning'): ?>
                                                <i class="fas fa-exclamation-circle fa-xl text-warning ml-auto"></i>
                                            <?php elseif ($obj['humanState'] == 'critical' || $obj['humanState'] == 'down'): ?>
                                                <i class="fas fa-times-circle fa-xl text-danger ml-auto"></i>
                                            <?php elseif ($obj['humanState'] == 'unreachable' || $obj['humanState'] == 'unknown'): ?>
                                                <i class="fas fa-times-circle fa-xl text-secondary ml-auto"></i>
                                            <?php endif; ?>
                                        </div>
                                    </h5>
                                    <p class="card-text"><?= $statusmessage; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Timeline start -->
        <div class="d-flex justify-content-center">
            <div class="row w-100">
                <!-- <div class="frame-wrap"> -->
                <div class="col-lg-12 no-padding">
                    <ul class="cbp_tmtimeline">
                        <?php
                        foreach ($downtimeAndAckHistory as $history):
                            ?>
                            <li>
                                <time class="cbp_tmtime" datetime="18:52:51 - 29.08.2022">
                                    <?php if ($history['type'] == 'acknowledgement'): ?>
                                        <span><?= $history['entry_time']; ?></span>
                                        <span><?= $history['entry_time_in_words']; ?></span>
                                    <?php else: ?>
                                        <span><?= $history['scheduled_start_time']; ?></span>
                                        <span><?= $history['scheduled_start_time_in_words']; ?></span>
                                    <?php endif; ?>
                                </time>
                                <div class="cbp_tmicon bg-white"
                                     title="<?= $history['type'] ?>">
                                    <?php if ($history['type'] == 'acknowledgement'): ?>
                                        <i class="fas fa-user color-black"></i>
                                    <?php else: ?>
                                        <i class="fas fa-power-off color-black"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="cbp_tmlabel">
                                    <h2 class="font-md">
                                        <?php
                                        $message = '';
                                        if (!empty($history['name'])) {
                                            //element itself is in downtime or ack'd
                                            $displayName = $history['name'];
                                            $displayType = $history['selfType'];
                                            if ($history['type'] == 'acknowledgement') {
                                                $message = __($displayName. ' is acknowledged');
                                            }
                                            if ($history['type'] == 'downtime') {
                                                $message = __($displayName.' is in downtime');
                                            }
                                        }
                                        if (!empty($history['parentType']) && !empty($history['parentName'])) {
                                            //subelement of this is in downtime or ack'd
                                            $displayName = $history['parentName'];
                                            $displayType = ucfirst($history['parentType']);
                                            if ($history['type'] == 'acknowledgement') {
                                                $message = __('There is a acknowledged ' . $history['selfType']);
                                            }
                                            if ($history['type'] == 'downtime') {
                                                $message = __('There is a ' . $history['selfType'] . ' in downtime');
                                            }

                                        }

                                        echo $displayType . ': ' . $displayName;
                                        ?>
                                    </h2>

                                    <blockquote
                                        class="blockquote changelog-blockquote-primary statuspage-history-checkmarks">
                                        <span>
                                            <footer class="padding-left-10 blockquote-footer">
                                                Comment: <span><?= $message; ?></span>
                                            </footer>
                                        </span>
                                        <?php if ($history['type'] == 'downtime'): ?>
                                            <span>
                                                <footer class="padding-left-10 blockquote-footer">
                                                    From: <span><?= $history['scheduled_start_time']; ?></span>
                                                </footer>
                                            </span>
                                            <span>
                                                <footer class="padding-left-10 blockquote-footer">
                                                    To: <span><?= $history['scheduled_end_time']; ?></span>
                                                </footer>
                                            </span>
                                        <?php endif; ?>
                                    </blockquote>
                                </div>
                            </li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

