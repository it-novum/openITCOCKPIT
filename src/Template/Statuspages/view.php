<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statuspage $statuspage
 */

use itnovum\openITCOCKPIT\Core\Views\Logo;

$logo = new Logo();
?>

<pre>
<?php
//print_r($Statuspage);
//print_r($downtimeAndAckHistory);
?>
    </pre>
<div ng-controller="StatuspagesViewController">
    <header id="header" class="page-header" role="banner" style="background-color: #fff; background-image: none;">
        <!-- we need this logo when user switches to nav-function-top -->
        <div class="page-logo">
            <a href="<?php printf('https://%s', $systemaddress); ?>"
               class="page-logo-link press-scale-down d-flex align-items-center position-relative">
                <img src="<?= $logo->getHeaderLogoForHtml(); ?>" alt="SmartAdmin WebApp" aria-roledescription="logo">
                <span class="page-logo-text mr-1" style="color: #000;"><?= $systemname; ?></span>
                <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
            </a>
        </div>
        <div class="ml-auto d-flex">

        </div>
    </header>

    <div class="container" style="margin-top:60px;">
        <div class="d-flex justify-content-center">
            <div class="alert alert-info w-100" role="alert">
                <h4 class="alert-heading"><?= __('Info!') ?></h4>
                <?= __('This is a non public view!'); ?>
            </div>
        </div>
        <div class="d-flex justify-content-center ">
            <div class="jumbotron w-100 bg-white " style="border: 1px solid rgba(0,0,0,.125);">
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
                    <div class="alert alert-secondary" role="alert">
                        <i class="fas fa-times"></i>
                        <?= __('Unknown') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row w-100">
                <?php foreach ($Statuspage as $key => $item):
                    if ($key == 'statuspage') {
                        continue;
                    }
                    foreach ($item as $subKey => $obj):
                        ?>
                        <div class="col-sm-6 no-padding">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-text d-flex">
                                        <span class="text-wrap"><?= $obj['name'] ?></span>
                                        <div class="ml-auto">
                                            <?php if ($obj['inDowntime']): ?>
                                                <i class="fa fa-power-off"></i>
                                            <?php endif; ?>
                                            <?php if ($obj['acknowledged']): ?>
                                                <i class="fas fa-user ng-scope"></i>
                                            <?php endif; ?>
                                            <?php if ($obj['humanState'] == 'up' || $obj['humanState'] == 'ok'): ?>
                                                <i class="fas fa-check-circle text-success ml-auto"></i>
                                            <?php elseif ($obj['humanState'] == 'warning'): ?>
                                                <i class="fas fa-exclamation-circle text-warning ml-auto"></i>
                                            <?php elseif ($obj['humanState'] == 'critical' || $obj['humanState'] == 'down'): ?>
                                                <i class="fas fa-times-circle text-danger ml-auto"></i>
                                            <?php elseif ($obj['humanState'] == 'unreachable' || $obj['humanState'] == 'unknown'): ?>
                                                <i class="fas fa-times-circle text-secondary ml-auto"></i>
                                            <?php endif; ?>
                                        </div>
                                    </h5>
                                    <!-- <p class="card-text"><?= $obj['humanState'] ?>
                                    </p> -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Timeline start -->
        <div class="d-flex justify-content-center margin-top-10">
            <div class="row w-100">
               <!-- <div class="frame-wrap"> -->
                    <div class="col-lg-12 no-padding">
                        <ul class="cbp_tmtimeline">
                            <li>
                                <time class="cbp_tmtime" datetime="18:52:51 - 29.08.2022">
                                    <span>18:52:51 - 29.08.2022</span>
                                    <span>1 day, 1 hour ago</span>
                                </time>
                                <div class="cbp_tmicon txt-color-white"
                                     title="">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="cbp_tmlabel">
                                    <h2 class="font-md">
                                        test message
                                    </h2>
                                </div>
                            </li>
                            <li>
                                <time class="cbp_tmtime" datetime="18:52:51 - 29.08.2022">
                                    <span>18:52:51 - 29.08.2022</span>
                                    <span>1 day, 1 hour ago</span>
                                </time>
                                <div class="cbp_tmicon txt-color-white"
                                     title="">
                                    <i class="fas fa-power-off"></i>
                                </div>
                                <div class="cbp_tmlabel">
                                    <h2 class="font-md">
                                        test message
                                    </h2>
                                </div>
                            </li>
                        </ul>
                    </div>
              <!--  </div> -->
            </div>
        </div>
        <!-- Timeline end -->
    </div>
</div>
