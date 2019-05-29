<div class="alert alert-danger alert-block" ng-show="!queryHandler.exists">
    <h4 class="alert-heading">
        <i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
    </h4>
    <?php echo __('File {{queryHandler.path}} does not exists'); ?>
</div>
