<?php

namespace App\itnovum\openITCOCKPIT\Core;

use App\Model\Table\SystemsettingsTable;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;

/**
 *
 */
class SystemHealthNotification {

    /**
     * @var array
     */
    private $mailingList = [];

    /**
     * CRITICAL, WARNING or OK
     * @var string
     */
    private $state;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param array $mailingList
     * @param string $state
     */
    public function __construct(array $mailingList, string $state) {
        $this->mailingList = $mailingList;
        $this->state = $state;
    }

    /**
     * @return array
     */
    public function getMailingList(): array {
        return $this->mailingList;
    }

    /**
     * Mailing list structure (key: email, value: name of contact)
     *
     * @param array $mailingList
     * @return void
     */
    public function setMailingList(array $mailingList): void {
        $this->mailingList = $mailingList;
    }

    /**
     * @return string
     */
    public function getState(): string {
        return $this->state;
    }

    /**
     * @param string $state
     * @return void
     */
    public function setState(string $state): void {
        $this->state = $state;
    }

    public function getData(): array {
        return $this->data;
    }

    public function setData(array $data): void {
        $this->data = $data;
    }

    /**
     * @return ServicestatusIcon
     */
    private function getStatusIcon() {
        switch (strtoupper($this->state)) {
            case 'OK':
                $stateId = 0;
                break;
            case 'WARNING':
                $stateId = 1;
                break;
            case 'CRITICAL':
                $stateId = 2;
                break;
            default:
                $stateId = 3;
                break;
        }

        return new ServicestatusIcon($stateId);
    }

    /**
     * @return void
     */
    public function sendNotification() {

        if (!empty($this->getMailingList())) {

            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettings = $SystemsettingsTable->findAsArray();
            $statusIcon = $this->getStatusIcon();

            foreach ($this->getMailingList() as $email => $name) {

                $Logo = new Logo();

                $Mailer = new Mailer();
                $Mailer->setFrom($systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'], $systemsettings['MONITORING']['MONITORING.FROM_NAME']);
                $Mailer->addTo($email, $name);
                $Mailer->setSubject($statusIcon->getEmoji() . ' ' . __('System health is {0}', $this->getState()));
                $Mailer->setEmailFormat('both');
                $Mailer->setAttachments([
                    'logo.png' => [
                        'file'      => $Logo->getSmallLogoDiskPath(),
                        'mimetype'  => 'image/png',
                        'contentId' => '100'
                    ]
                ]);

                $Mailer->viewBuilder()
                    ->setTemplate('notification_system_health')
                    ->setVar('systemname', $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'])
                    ->setVar('StatusIcon', $statusIcon)
                    ->setVar('systemAddress', $systemsettings['SYSTEM']['SYSTEM.ADDRESS'])
                    ->setVar('systemHealth', $this->data);

                $Mailer->deliver();
            }
        }

    }

}
