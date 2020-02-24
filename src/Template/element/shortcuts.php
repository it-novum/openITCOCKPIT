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
<div class="modal fade" id="ShortcutsHelp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Keyboard Shortcuts'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <h5><?php echo __('Global Shortcuts'); ?></h5>
                    <div class="col-md-4"><?php echo __('Open shortcut help'); ?>:</div>
                    <div class="col-md-6">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">Ctrl</a>
                        +
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">H</a>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                            <strong>H</strong>
                        </a>
                    </div>

                    <br/>
                    <br/>
                    <div class="col-md-4"><?php echo __('Open lockscreen'); ?>:</div>
                    <div class="col-md-6">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">Ctrl</a>
                        +
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">L</a>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                            <strong>L</strong>
                        </a>
                    </div>

                    <br/>
                    <br/>
                    <div class="col-md-4"><?php echo __('Logout'); ?>:</div>
                    <div class="col-md-6">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">Alt</a>
                        +
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">L</a>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-random"></i>
                            <strong>L</strong>
                        </a>
                    </div>

                    <br/>
                    <br/>
                    <div class="col-md-4"><?php echo __('Close overlays'); ?>:</div>
                    <div class="col-md-6">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">Esc</a>
                    </div>

                    <br/>
                    <br/>
                    <div class="col-md-4"><?php echo __('Collapse menu'); ?>:</div>
                    <div class="col-md-6">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">Alt</a>
                        +
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">C</a>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-random"></i>
                            <strong>C</strong>
                        </a>
                    </div>

                    <br/>
                    <br/>
                    <div class="col-md-4"><?php echo __('Submit'); ?>:</div>
                    <div class="col-md-6">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">Return</a>
                    </div>

                    <br/>

                    <br/>
                    <h5 class="text-left"><?php echo __('List Shortcuts'); ?></h5>
                    <div class="col-md-4"><?php echo __('Open Search'); ?>:</div>
                    <div class="col-md-6">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">Alt</a>
                        +
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">F</a>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-random"></i>
                            <strong>F</strong>
                        </a>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close (ESC)'); ?>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->