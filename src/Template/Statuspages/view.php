<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statuspage $statuspage
 */
?>

<pre>
<?php
print_r($Statuspage);
?>
    </pre>
<div ng-controller="StatuspagesViewController">
    <div class="d-flex justify-content-center">
        <div class="jumbotron w-75">
            <h1 class="display-4"><?= $Statuspage['statuspage']['name']; ?></h1>
            <p class="lead"><?= $Statuspage['statuspage']['description']; ?></p>
            <hr class="my-4">
            <?php
            //@TODO add cumulated state for alert
            ?>
            <div class="alert alert-success" role="alert">
                All systems operational <i class="fas fa-check"></i> <i class="fas fa-times"></i>
            </div>
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
