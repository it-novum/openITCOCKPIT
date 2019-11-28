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

declare(strict_types=1);

namespace itnovum\openITCOCKPIT\Core\Menu;


class MenuHeadline {

    private $name;

    private $alias;

    private $order;

    private $items = [];

    /**
     * menuHeadline constructor.
     * @param string $name
     * @param int $order
     */
    public function __construct(string $name, string $alias, int $order = 0) {
        $this->name = $name;
        $this->alias = $alias;
        $this->order = $order;
    }

    /**
     * @param MenuLink $MenuLink
     * @return $this
     */
    public function addLink(MenuLink $MenuLink) {
        return $this->addItem($MenuLink);
    }

    /**
     * @param MenuCategory $MenuCategory
     * @return $this
     */
    public function addCategory(MenuCategory $MenuCategory) {
        return $this->addItem($MenuCategory);
    }

    /**
     * @param MenuCategory|MenuLink $menuItem
     * @return $this
     */
    private function addItem($menuItem) {

        $items = $this->items;
        $items[] = $menuItem;

        $this->items = [];

        $indexesToOrder = [];
        foreach ($items as $index => $item) {
            /** @var MenuCategory|MenuLink */
            $indexesToOrder[$index] = $item->getOrder();
        }

        asort($indexesToOrder);

        foreach ($indexesToOrder as $index => $orderNumber) {
            $this->items[] = $items[$index];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAlias(): string {
        return $this->alias;
    }

    /**
     * @return int
     */
    public function getOrder(): int {
        return $this->order;
    }

    /**
     * @return array
     */
    public function getItems(): array {
        return $this->items;
    }

}
