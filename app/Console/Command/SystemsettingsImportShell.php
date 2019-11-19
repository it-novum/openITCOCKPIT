<?php

require_once OLD_APP . 'src' . DS . 'itnovum' . DS . 'openITCOCKPIT' . DS . 'InitialDatabase' . DS . 'Systemsetting.php';

class SystemsettingsImportShell extends AppShell {

    public $uses = ['Systemsetting'];

    public function main() {
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('red', ['text' => 'red']);
        $this->out('Comparing Systemsettings Schema with systemsettings table...     ');
        $mySytemsettings = new itnovum\openITCOCKPIT\InitialDatabase\Systemsetting(new Model());
        $myData = $mySytemsettings->getData();

        $this->_systemsettings = $this->Systemsetting->find('all');
        $imported = 0;
        foreach ($myData as $settingOption) {
            $found = false;
            foreach ($this->_systemsettings as $dbSettingOption) {

                if ($settingOption['Systemsetting']['key'] === $dbSettingOption['Systemsetting']['key'] &&
                    $settingOption['Systemsetting']['section'] === $dbSettingOption['Systemsetting']['section']) {

                    $found = true;
                    break;

                }

            }

            if (!$found) {
                $this->out('Inserting ' . $settingOption['Systemsetting']['key'] . '...     ', false);
                if ($this->Systemsetting->saveAll($settingOption)) {
                    $imported++;
                    $this->out('<green>inserted</green>');
                } else {
                    $this->out('<red>not inserted</red>');
                }
            }
        }

        $this->out('<green>' . $imported . ' row(s) inserted!</green>');
    }

    public function _welcome() {

    }


}