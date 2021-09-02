<div>
    <flippy vertical
            class="col-lg-12"
            flip="['custom:FLIP_EVENT_OUT']"
            flip-back="['custom:FLIP_EVENT_IN']"
            duration="800"
            timing-function="ease-in-out">

        <flippy-front class="fixFlippy">
            <div class="row">
                <div class="col-lg-1">
                    <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark"
                       ng-click="showConfig()">
                        <i class="fa fa-cog fa-sm"></i>
                    </a>
                </div>
            </div>
            <div class="container-fluid padding-top-10 text-center">
                <div class="d-flex flex-row">
                    <div class="p-1 bg-color-grayDark text-white tactical-overview-first-flex-item">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <div class="bg-up text-white tactical-overview-flex-item padding-top-50 padding-bottom-50">1234567</div>
                    <div class="bg-down text-white tactical-overview-flex-item">2748357</div>
                    <div class="bg-unreachable text-white tactical-overview-flex-item">64333</div>
                </div>
                <div class="d-flex flex-row">
                    <div class="p-1 bg-color-grayDark tactical-overview-first-flex-item">
                    </div>
                    <div class="bg-color-grayDark tactical-overview-flex-item font-lg text-white text-left">Unhandled Hosts</div>
                    <div class="bg-down-soft tactical-overview-flex-item font-xl text-white">65554</div>
                    <div class="bg-unreachable-soft tactical-overview-flex-item font-xl text-white">33</div>
                </div>
                <div class="d-flex flex-row">
                    <div class="p-1 tactical-overview-first-flex-item">
                        <i class="fa fa-user text-primary" title="is acknowledged"></i>
                    </div>
                    <div class="up tactical-overview-flex-item font-xl">0</div>
                    <div class="down  tactical-overview-flex-item font-xl">54</div>
                    <div class="text-unreachable tactical-overview-flex-item font-xl">5767</div>
                </div>

                <div class="d-flex flex-row">
                    <div class="p-1 tactical-overview-first-flex-item">
                        <i class="fa fa-power-off text-primary" title="is in downtime"></i>
                    </div>
                    <div class="up tactical-overview-flex-item font-xl">0</div>
                    <div class="down tactical-overview-flex-item font-xl">568</div>
                    <div class="text-unreachable tactical-overview-flex-item font-xl">399</div>
                </div>
            </div>

        </flippy-front>
        <flippy-back class="fixFlippy">
            <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark margin-bottom-10"
               ng-click="hideConfig()">
                <i class="fa fa-eye fa-sm"></i>
            </a>
            <div class="padding-10" style="border: 1px solid #c3c3c3;">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend fa fa-desktop"></i></span>
                                </div>
                                <input type="text" class="form-control"
                                       placeholder="<?php echo __('Filter by host name'); ?>"
                                       ng-model="filter.Host.name"
                                       ng-model-options="{debounce: 500}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend fa fa-filter"></i></span>
                                </div>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="<?php echo __('Filter by IP address'); ?>"
                                       ng-model="filter.Host.address"
                                       ng-model-options="{debounce: 500}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           data-role="tagsinput"
                                           id="ServicesKeywordsInput"
                                           placeholder="<?php echo __('Filter by tags'); ?>"
                                           ng-model="filter.Host.keywords"
                                           ng-model-options="{debounce: 500}"
                                           style="display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input type="text" class="input-sm"
                                           data-role="tagsinput"
                                           id="ServicesNotKeywordsInput"
                                           placeholder="<?php echo __('Filter by excluded tags'); ?>"
                                           ng-model="filter.Host.not_keywords"
                                           ng-model-options="{debounce: 500}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-primary float-right"
                                ng-click="saveSettings()">
                            <?php echo __('Save'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </flippy-back>
    </flippy>
</div>
