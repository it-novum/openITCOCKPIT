<?php

namespace TelegramModule\Lib;


use itnovum\openITCOCKPIT\Core\Menu\MenuCategory;
use itnovum\openITCOCKPIT\Core\Menu\MenuHeadline;
use itnovum\openITCOCKPIT\Core\Menu\MenuInterface;
use itnovum\openITCOCKPIT\Core\Menu\MenuLink;

class Menu implements MenuInterface {

    /**
     * @return array
     */
    public function getHeadlines() {
        $Overview = new MenuHeadline(\itnovum\openITCOCKPIT\Core\Menu\Menu::MENU_CONFIGURATION);
        $Overview
            ->addCategory((new MenuCategory(
                'api_settings',
                __('APIs')
            ))
                ->addLink(new MenuLink(
                    __('Telegram'),
                    'TelegramSettingsIndex',
                    'TelegramSettings',
                    'index',
                    'TelegramModule',
                    'fab fa-telegram',
                    [],
                    1
                ))
            );

        return [$Overview];
    }

}


