<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statuspage $statuspage
 */
?>

<pre>
<?php
//print_r($Statuspage);
?>
    </pre>
<div ng-controller="StatuspagesViewController">
    <div class="d-flex justify-content-center">
        <div class="alert alert-info w-75" role="alert">
            <h4 class="alert-heading"><?= __('Info!') ?></h4>
            <?= __('This is a non public view!'); ?>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div class="jumbotron w-75">
            <h1 class="display-4"><?= $Statuspage['statuspage']['name']; ?></h1>
            <p class="lead"><?= $Statuspage['statuspage']['description']; ?></p>
            <hr class="my-4">
            <?php if ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'up' || $Statuspage['statuspage']['cumulatedState']['humanState'] == 'ok'): ?>
                <div class="alert alert-success" role="alert">
                    <?= __('All systems operational'); ?> <i class="fas fa-check"></i>
                </div>
            <?php elseif ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'warning'): ?>
                <div class="alert alert-warning" role="alert">
                    <?= __('Warning') ?> <i class="fas fa-exclamation-circle"></i>
                </div>
            <?php elseif ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'critical' || $Statuspage['statuspage']['cumulatedState']['humanState'] == 'down'): ?>
                <div class="alert alert-danger" role="alert">
                    <?= __('Critical') ?> <i class="fas fa-times"></i>
                </div>
            <?php elseif ($Statuspage['statuspage']['cumulatedState']['humanState'] == 'unreachable' || $Statuspage['statuspage']['cumulatedState']['humanState'] == 'unknown'): ?>
                <div class="alert alert-secondary" role="alert">
                    <?= __('Unknown') ?> <i class="fas fa-times"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div class="row w-75">
            <?php foreach ($Statuspage as $key => $item):
                if ($key == 'statuspage') {
                    continue;
                }
                foreach ($item as $subKey => $obj):
                    ?>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title d-flex"><?= $obj['name'] ?>
                                    <?php if ($obj['humanState'] == 'up' || $obj['humanState'] == 'ok'): ?>
                                        <i class="fas fa-check-circle text-success ml-auto"></i>
                                    <?php elseif ($obj['humanState'] == 'warning'): ?>
                                        <i class="fas fa-exclamation-circle text-warning ml-auto"></i>
                                    <?php elseif ($obj['humanState'] == 'critical' || $obj['humanState'] == 'down'): ?>
                                        <i class="fas fa-times-circle text-danger ml-auto"></i>
                                    <?php elseif ($obj['humanState'] == 'unreachable' || $obj['humanState'] == 'unknown'): ?>
                                        <i class="fas fa-times-circle text-secondary ml-auto"></i>
                                    <?php endif; ?>
                                </h5>
                                <p class="card-text"><?= $obj['humanState'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
