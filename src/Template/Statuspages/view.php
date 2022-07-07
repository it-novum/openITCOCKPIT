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
    <div class="jumbotron">
        <h1 class="display-4"><?= $Statuspage['statuspage']['name']; ?></h1>
        <p class="lead"><?= $Statuspage['statuspage']['description']; ?></p>
        <hr class="my-4">
        <div class="alert alert-success" role="alert">
            All systems operational <i class="fas fa-check"></i> <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="row">
        <?php foreach ($Statuspage as $key => $item):
            if ($key == 'statuspage') {
                continue;
            }
            foreach ($item as $subKey => $obj):
                ?>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $obj['name'] ?></h5>
                            <p class="card-text"><?= $obj['humanState'] ?> - <?= $obj['currentState'] ?> <i class="fas fa-check-circle"></i> <i class="fas fa-times-circle"></i></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
