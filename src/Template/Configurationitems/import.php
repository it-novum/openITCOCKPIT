<?php
// Copyright (C) <2018>  <it-novum GmbH>
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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?= __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="WizardsIndex">
            <i class="fa-solid fa-wand-magic-sparkles"></i> <?= __('Export / Import'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa-solid fa-file-export"></i> <?= __('Export'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?= __('Import configuration items from JSON File'); ?>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <?php if ($this->Acl->hasPermission('export', 'configurationitems')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="ConfigurationitemsExport" role="tab">
                                    <i class="fa-solid fa-file-export pr-1"></i><?= __('Export'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('import', 'configurationitems')): ?>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" ui-sref="ConfigurationitemsImport"
                                   role="tab">
                                    <i class="fa-solid fa-file-import pr-1"></i><?= __('Import'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content fuelux">
                    <form ng-submit="submit();" class="form-horizontal">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
