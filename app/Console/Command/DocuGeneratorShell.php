<?php

define('SYSSETT_TAB1', '    ');
define('SYSSETT_TAB2', '        ');
define('SYSSETT_TAB3', '            ');
define('SYSSETT_TAB4', '                ');

App::uses('File', 'Utility');
require_once OLD_APP . 'src' . DS . 'itnovum' . DS . 'openITCOCKPIT' . DS . 'InitialDatabase' . DS . 'Systemsetting.php';

class DocuGeneratorShell extends AppShell {

    public function main() {
        $this->stdout->styles('green', ['text' => 'green']);
        $mySytemsettings = new itnovum\openITCOCKPIT\InitialDatabase\Systemsetting(new Model());
        $myData = $mySytemsettings->getData();

        // generating api/systemsettings
        $this->out('generating api/systemsettings.md...    ', false);
        $systemSettingsNew = new File(OLD_APP . DS . 'docs' . DS . 'en' . DS . 'api' . DS . 'systemsettings.md', true, 0777);
        $systemSettingsNew->delete();
        $systemSettingsNew->write($this->getApiPreText(), 'w');

        $currentArea = null;
        foreach ($myData as $index => $settingArr) {
            $keyArr = explode('.', $settingArr['Systemsetting']['key']);
            if ($currentArea !== $keyArr[0]) {
                if (!is_null($currentArea)) {
                    $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB2 . '],', 'a');
                }
                $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB2 . '"' . $keyArr[0] . '": [', 'a');
                $currentArea = $keyArr[0];
            }
            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB3 . '{', 'a');

            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB4 . '"id": "' . ($index + 1) . '",', 'a');
            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB4 . '"key": "' . $settingArr['Systemsetting']['key'] . '",', 'a');
            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB4 . '"value": "' . $settingArr['Systemsetting']['value'] . '",', 'a');
            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB4 . '"info": "' . $settingArr['Systemsetting']['info'] . '",', 'a');
            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB4 . '"section": "' . $settingArr['Systemsetting']['section'] . '",', 'a');
            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB4 . '"created": "' . date('Y-m-d H:i:s') . '",', 'a');
            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB4 . '"modified": "' . date('Y-m-d H:i:s') . '"', 'a');

            $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB3 . '}', 'a');
        }
        $systemSettingsNew->write(PHP_EOL . SYSSETT_TAB2 . ']' . PHP_EOL . SYSSETT_TAB1, 'a');


        $systemSettingsNew->write($this->getApiPostText(), 'a');
        $systemSettingsNew->close();
        $this->out('<green>done</green>');

        // generating administration/systemsettings
        $this->out('generating administration/systemsettings.md...    ', false);
        unset($systemSettingsNew);
        $systemSettingsNew = new File(OLD_APP . DS . 'docs' . DS . 'en' . DS . 'administration' . DS . 'systemsettings.md', true, 0777);
        $systemSettingsNew->delete();
        $systemSettingsNew->write($this->getAdminPreText(), 'w');

        $currentArea = null;
        foreach ($myData as $index => $settingArr) {
            $keyArr = explode('.', $settingArr['Systemsetting']['key']);
            if ($currentArea !== $keyArr[0]) {
                $systemSettingsNew->write(PHP_EOL . PHP_EOL . '###### ' . $keyArr[0], 'a');
                $currentArea = $keyArr[0];
            }

            $systemSettingsNew->write(PHP_EOL . '* **' . str_replace($keyArr[0] . '.', '', $settingArr['Systemsetting']['key']) . '** - ' . $settingArr['Systemsetting']['info'], 'a');
        }


        $systemSettingsNew->close();
        $this->out('<green>done</green>');
    }

    public function _welcome() {

    }

    private function getApiPreText() {
        return '## Query systemsettings:

<div class="input-group">
    <span class="input-group-addon bg-color-green txt-color-white">GET</span>
    <input type="text" class="form-control" readonly="readonly" value="/systemsettings.json">
</div>
<br />
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Response: application/json</h3>
    </div>
    <div class="panel-body">
        <pre>
{
    "all_systemsettings": {';
    }

    private function getApiPostText() {
        return '}
}
        </pre>
    </div>
</div>';
    }

    private function getAdminPreText() {
        return '[//]: # (Links)
[settings]: /systemsettings "System settings"

[//]: # (Pictures)

[//]: # (Content)

## What can I set up in the system settings?

[Here][settings] you configure settings for your web server, sudo server, monitoring, system, front end, check_mk and archive.

## Which settings can I set?/*

You can set settings that belong to your web server, sudo server, monitoring, system, front end, check_mk and archive.

You can always get a hint if you hover over
<a class="btn-xs" data-original-title="Gives you a hint." data-placement="left" rel="tooltip" data-container="body"><i class="fa fa-info-circle fa-2x"></i></a>.

Click on <a class="btn btn-xs btn-primary">Save</a> to save your configuration.

Click on <a class="btn btn-xs btn-default">Cancel</a> if you want to discard your changes.';
    }


}