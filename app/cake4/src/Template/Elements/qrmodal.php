<?php
// Copyright (C) <2015>  <it-novum GmbH>
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
?>
<div class="modal fade" id="qrmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-qrcode"></i> <?php echo __('Scan code'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="form-group form-group-slider">
                        <label class="col col-md-2 control-label" for="QRSize"><?php echo __('Size'); ?></label>
                        <div class="col col-md-10">
                            <input
                                    type="text"
                                    id="QRSize"
                                    maxlength="1"
                                    value=""
                                    class="form-control slider slider-success"
                                    data-slider-min="100"
                                    data-slider-max="300"
                                    data-slider-value="150"
                                    data-slider-selection="before"
                                    data-slider-step="50">
                        </div>
                    </div>
                    <center>
                        <div id="scancodeContainer"></div>
                    </center>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="QRPrint">
                    <i class="fa fa-print"></i> <?php echo __('Print'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>