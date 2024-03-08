<?php
// Copyright (C) <2024-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace MSTeamsModule\Lib;


use itnovum\openITCOCKPIT\Core\Menu\MenuCategory;
use itnovum\openITCOCKPIT\Core\Menu\MenuHeadline;
use itnovum\openITCOCKPIT\Core\Menu\MenuInterface;
use itnovum\openITCOCKPIT\Core\Menu\MenuLink;

class Menu implements MenuInterface {

    /**
     * @return array
     */
    public function getHeadlines() {
        $Monitoring = new MenuHeadline(\itnovum\openITCOCKPIT\Core\Menu\Menu::MENU_CONFIGURATION);
        $Monitoring
            ->addCategory((new MenuCategory(
                'api_settings',
                __('APIs')
            ))
                ->addLink(new MenuLink(
                    __('Teams'),
                    'MSTeamsSettingsIndex',
                    'MSTeamsSettings',
                    'index',
                    'MSTeamsModule',
                    'fa fa-code',
                    [],
                    9
                ))
            );

        return [$Monitoring];
    }

}
