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

$current = Router::parse(Router::url());
$hasChildren = function ($item) {
    if (isset($item['children']) && !empty($item['children'])) {
        return true;
    }

    return false;
};

$isActive = function ($item, $current, $hasChildren = false, $isParent = true) {
    if ($item['url']['controller'] == $current['controller']) {
        return true;
    }
    if ($hasChildren === true && $isParent === false) {
        foreach ($item['children'] as $mainItem => $mainData) {
            if ($mainData['url']['controller'] == $current['controller']) {
                return true;
            }

        }
    }

    return false;
};

$isActiveAction = function ($item, $current, $isActiveController = false, $use_parent = true) {
    if ($use_parent === true) {
        if (isset($item['parent_controller'])) {
            if (($item['url']['action'] == $current['action'] && $item['url']['controller'] == $current['controller'])) {
                //if(($item['url']['action'] == $current['action'] && $item['parent_controller'] == $current['controller']) || ($item['url']['controller'] == $current['controller'] && isset($item['parent_controller']))){
                return true;
            }
        }
    }

    return false;
};

$mainMenu = $menu;
?>
<ul>
    <li>
        <div class="clearfix padding-10">
            <input type="text"
                   placeholder="<?php echo __('Type to search'); ?>"
                   class="form-control pull-left"
                   id="filterMainMenu"
                   title="<?php echo __('If you type the menu will instantly searched'); ?>&#10;<?php echo __('If you press return, the system will run a host search'); ?>"
            />
            <a href="/search/index" class="form-control pull-right no-padding" id="searchMainMenu"><i
                        class="fa fa-search-plus"></i></a>
        </div>
        <div id="menuSearchResult"></div>
    </li>
    <?php
    foreach ($mainMenu as $mainItem => $mainData):
        if (isset($mainData['url']['plugin']) && $mainData['url']['plugin'] == ''):
            $mainData['url'] = Hash::remove($mainData['url'], 'plugin');
        endif;
        ?>
        <li class="<?php echo $isActive($mainData, $current, $hasChildren($mainData)) ? 'active' : '' ?> <?php if ($hasChildren($mainData) && $isActive($mainData, $current, false)): echo 'open';
        else: echo ''; endif; ?>">
            <?php
            $linkFirstMenuTitle = sprintf('<i class="fa fa-lg fa-fw fa-%s"></i> <span class="menu-item-parent">%s</span>', $mainData['icon'], $mainData['title']);
            if (isset($mainData['url']) && !empty($mainData['url']) && !$hasChildren($mainData)):
                if (isset($mainData['url']['plugin'])):
                    echo $this->Html->link($linkFirstMenuTitle, (isset($mainData['url']) ? $mainData['url'] : '#'), [
                        'escape' => false,
                        'title'  => $mainData['title'],
                    ]);
                else:
                    ?>
                    <a href="/<?php echo $mainData['url']['controller']; ?>/<?php echo $mainData['url']['action']; ?>"><?php printf('<i class="fa fa-lg fa-fw fa-%s"></i> <span class="menu-item-parent">%s</span>', $mainData['icon'], $mainData['title']); ?></a>
                    <?php
                endif;
            else:
                if ($mainData['title'] == 'Dashboard'):
                    ?>
                    <a href="/"><?php printf('<i class="fa fa-lg fa-fw fa-%s"></i> <span class="menu-item-parent">%s</span>', $mainData['icon'], $mainData['title']); ?></a>
                <?php else: ?>
                    <a href="#"><?php printf('<i class="fa fa-lg fa-fw fa-%s"></i> <span class="menu-item-parent">%s</span>', $mainData['icon'], $mainData['title']); ?></a>
                    <?php
                endif;
            endif;

            if (!empty($mainData['children'])): ?>
                <ul style="<?php echo $isActive($mainData, $current, $hasChildren($mainData), false) ? 'display:block;' : '' ?>">
                    <?php
                    foreach ($mainData['children'] as $subItem => $subData):
                        if (isset($subData['url']['plugin']) && $subData['url']['plugin'] == ''):
                            $subData['url'] = Hash::remove($subData['url'], 'plugin');
                        endif;
                        $linkSecondMenuTitle = sprintf('<i class="fa fa-lg fa-fw fa-%s"></i> <span class="menu-item-parent">%s</span>', $subData['icon'], $subData['title']);
                        ?>
                        <li class="<?php echo $isActiveAction($subData, $current, $isActive($mainData, $current)) ? 'active' : '' ?>">
                            <?php
                            if (isset($subData['url']['plugin'])):
                                echo $this->Html->link($linkSecondMenuTitle, isset($subData['url']) ? $subData['url'] : '#', [
                                    'escape' => false,
                                    'title'  => $subData['title'],
                                ]);
                            else:
                                ?>
                                <a href="/<?php echo $subData['url']['controller']; ?>/<?php echo $subData['url']['action']; ?>"><?php printf('<i class="fa fa-lg fa-fw fa-%s"></i> <span class="menu-item-parent">%s</span>', $subData['icon'], $subData['title']); ?></a>
                                <?php
                            endif;

                            if (!empty($subData['children'])): ?>
                                <ul>
                                    <?php foreach ($subData['children'] as $subSubItem => $subSubData):
                                        $linkThirdMenuTitle = sprintf('<i class="fa fa-lg fa-fw fa-%s"></i> <span class="menu-item-parent">%s</span>', $subSubData['icon'], $subSubData['title']);
                                        ?>
                                        <li>
                                            <?php
                                            echo $this->Html->link($linkThirdMenuTitle, isset($subSubData['url']) ? $subSubData['url'] : '#', [
                                                'escape' => false,
                                                'title'  => $subSubData['title'],
                                            ]); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
