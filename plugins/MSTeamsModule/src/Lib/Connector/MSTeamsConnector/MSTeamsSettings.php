<?php declare(strict_types=1);

namespace MSTeamsModule\Lib\Connector\MSTeamsConnector;

use App\Model\Table\SystemsettingsTable;
use Cake\ORM\TableRegistry;
use MSTeamsModule\Model\Table\MsteamsSettingsTable;

final class MSTeamsSettings {
    /** @var string */
    public $url;

    /** @var string */
    public $apiKey;

    /** @var bool */
    public $useProxy;

    /** @var string */
    public $oitcUrl;

    /**
     * I will solely build the Credentials object.
     * @return self
     */
    public static function fetch(): self {
        /** @var MsteamsSettingsTable $MSTeamsSettingsTable */
        $MSTeamsSettingsTable = TableRegistry::getTableLocator()->get('MSTeamsModule.MsteamsSettings');
        $teamsSettings = $MSTeamsSettingsTable
            ->find()
            ->where([
                'id' => 1
            ])
            ->firstOrFail();


        /** @var SystemsettingsTable $SystemSettingsTable */
        $SystemSettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $result = $SystemSettingsTable->getSystemsettingByKey('SYSTEM.ADDRESS');

        $credentials = new self();
        $credentials->url = $teamsSettings->webhook_url;
        $credentials->apiKey = $teamsSettings->apikey;
        $credentials->useProxy = $teamsSettings->use_proxy;
        $credentials->oitcUrl = sprintf('https://%s', $result->get('value'));
        return $credentials;
    }
}