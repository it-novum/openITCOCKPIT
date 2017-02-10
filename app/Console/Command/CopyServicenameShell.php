<?php

App::uses('File', 'Utility');

class CopyServicenameShell extends AppShell
{
    public $uses = ['Servicetemplate'];

    public function main(){
        $this->stdout->styles('green', ['text' => 'green']);
        $this->out('Checking Service template name and service template service name...   ', false);
        $firstServicetemplate = $this->Servicetemplate->find('first', [
            'recursive' => -1
        ]);
        if(empty($firstServicetemplate)){
            $this->out('No servicetemplates found   ', false);
        }elseif(isset($firstServicetemplate['Servicetemplate']['template_name'])){ // column exists
            $this->Servicetemplate->updateAll(
                ['Servicetemplate.template_name' => 'Servicetemplate.name'],
                ['Servicetemplate.template_name' => '']
            );
        }
        $this->out('<green>done</green>');
    }

    public function _welcome(){

    }


}