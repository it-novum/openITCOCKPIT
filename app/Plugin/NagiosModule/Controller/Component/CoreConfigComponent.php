<?php

use Cake\ORM\TableRegistry;

Class CoreConfigComponent extends Component {

    /*
     * NOTICE:
     * There is also a Model/Coreconfig.php, for parts where you cant use this component! For example in shell tasks!
     */

    public function initialize(Controller $controller) {
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();
        $this->Config = [];
    }

    public function read($key = null) {
        if (empty($this->Config)) {
            $this->loadConfigAsArray();
        }
        if (isset($this->Config[$key])) {
            return $this->Config[$key];
        }

        return flase;
    }

    public function _read($key = null) {
        return $this->read($key);
    }

    public function loadConfigAsArray() {
        $config = $this->_systemsettings['MONITORING']['MONITORING.CORECONFIG'];
        $coreconfig = fopen($config, "r");
        while (!feof($coreconfig)) {
            $line = trim(fgets($coreconfig));
            $strpos = strpos($line, '#');

            if ($line != '' && ($strpos === false || $strpos > 0)) {
                $parsed = explode('=', $line, 2);
                $this->Config[$parsed[0]] = $parsed[1];
            }
        }
    }
}