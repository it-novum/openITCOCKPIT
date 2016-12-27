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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-book fa-fw "></i>
            <?php echo __('Documentation'); ?>
            <span>>
                <?php echo __('openITCOCKPIT'); ?>
			</span>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-book"></i> </span>
        <h2><?php echo __('Documentation'); ?></h2>
        <?php if ($renderPage === true): ?>
            <div class="widget-toolbar" role="menu">
                <div class="btn btn-default btn-xs" iconcolor="white" id="doku_back"><i
                            class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to overview'); ?>
                </div>
            </div>
        <?php endif; ?>
    </header>
    <div>
        <div class="widget-body">
            <?php if ($renderPage === false): ?>
                <div class="input-group input-group-lg">
                    <input class="form-control input-lg" placeholder="<?php echo __('Type to search...'); ?>"
                           id="search-documentation" type="text">
                    <div class="input-group-btn">
                        <a href="javascript:void(0);" class="btn btn-default">
                            &nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-search fa-lg"></i>&nbsp;&nbsp;&nbsp;
                        </a>
                    </div>
                </div>
                <div class="wiki-search-results"></div>
                <div class="docs-container">
                    <?php foreach ($wiki as $categoryUrl => $category): ?>
                        <div class="wiki-category">
                            <h3><?php echo h($category['name']); ?></h3>
                            <?php foreach ($category['children'] as $pageUrl => $page): ?>
                                <?php $currentLink = Router::url([
                                    'controller' => 'documentations',
                                    'action'     => 'wiki',
                                    $categoryUrl,
                                    $pageUrl,
                                    $language]); ?>
                                <div class="search-results clearfix">
                                    <h4><a href="<?php echo $currentLink; ?>"><?php echo h($page['name']); ?></a></h4>
                                    <?php if (isset($page['description'])): ?>
                                        <div>
                                            <p class="description">
                                                <?php echo h($page['description']) ?>
                                            </p>
                                        </div>
                                    <?php endif ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($renderPage === true): ?>
                <h1>
                    <?php if ($icon !== ''): ?>
                        <?php echo '<i class="'.$icon.'" style="margin-right: 0.5em;"></i>'; ?>
                    <?php endif; ?>
                    <?php echo $subjectTitle; ?>
                </h1>
                <hr/>
                <?php echo $parsedMarkdown; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
