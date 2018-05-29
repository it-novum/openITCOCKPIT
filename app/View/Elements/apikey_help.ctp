<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
?>

<div class="modal fade" id="ApiKeyOverviewModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title"><i class="fa fa-key"></i> <?php echo __('API Keys Overview'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Click on "Edit profile" in the top left corner.'); ?>
                    </div>
                    <div class="col-xs-12">
                        <?php
                        echo $this->Html->image(
                            '/img/apikey_help/edit_profile.png',
                            ['class' => 'img-responsive']
                        );
                        ?>
                    </div>

                    <div class="col-xs-12">
                        <?php echo _('At the bottom of the page, you can find the "API Keys" section. Click on "Create new API key"'); ?>
                    </div>

                    <div class="col-xs-12">
                        <?php
                        echo $this->Html->image(
                            '/img/apikey_help/create_api_key.png',
                            ['class' => 'img-responsive']
                        );
                        ?>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>