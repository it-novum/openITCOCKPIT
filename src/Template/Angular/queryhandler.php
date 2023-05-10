<div class="alert alert-danger alert-block" ng-show="!queryHandler.exists && !queryHandler.isContainer">
    <h4 class="alert-heading">
        <i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
    </h4>
    <?php echo __('File {{queryHandler.path}} does not exists'); ?>
</div>
