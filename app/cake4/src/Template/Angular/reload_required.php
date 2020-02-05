<div id="angularRequirePageReloadModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary txt-color-white">
                <h5 class="modal-title">
                    <i class="fa fa-edit"></i>
                    <?php echo __('Reload of interface required'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('To apply changes, a reload of the interface is required.'); ?>
                    </div>

                    <div class="col-lg-12 text-info">
                        <?php echo __('Automatically reload in {{ delay - i }} seconds.'); ?>
                    </div>

                    <div class="col-lg-12 margin-top-10">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-downtime" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
