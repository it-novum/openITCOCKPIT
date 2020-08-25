<?php


namespace App\itnovum\openITCOCKPIT\ImportTemplates;


use App\Model\Table\CommandsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\MacrosTable;
use App\Model\Table\ServicetemplategroupsTable;
use App\Model\Table\ServicetemplatesTable;
use itnovum\openITCOCKPIT\Core\UUID;

class TemplateImport {

    /**
     * @var array
     */
    private $mapping = [];

    /**
     * @var CommandsTable
     */
    private $CommandsTable;

    /**
     * @var MacrosTable
     */
    private $MacrosTable;

    /**
     * @var ServicetemplatesTable
     */
    private $ServicetemplatesTable;

    /**
     * @var HosttemplatesTable
     */
    private $HosttemplatesTable;

    /**
     * @var ServicetemplategroupsTable
     */
    private $ServicetemplategroupsTable;

    /**
     * TemplateImport constructor.
     * @param CommandsTable $CommandsTable
     * @param MacrosTable $MacrosTable
     * @param ServicetemplatesTable $ServicetemplatesTable
     * @param HosttemplatesTable $HosttemplatesTable
     */
    public function __construct(CommandsTable $CommandsTable, MacrosTable $MacrosTable, ServicetemplatesTable $ServicetemplatesTable, HosttemplatesTable $HosttemplatesTable, ServicetemplategroupsTable $ServicetemplategroupsTable) {
        $this->CommandsTable = $CommandsTable;
        $this->MacrosTable = $MacrosTable;
        $this->ServicetemplatesTable = $ServicetemplatesTable;
        $this->HosttemplatesTable = $HosttemplatesTable;
        $this->ServicetemplategroupsTable = $ServicetemplategroupsTable;
    }

    /**
     * @param $command_line
     * @return string
     */
    private function replaceMacroNames($command_line) {
        $commandLineParts = explode(' ', $command_line);
        $newCommandLineParts = [];
        foreach ($commandLineParts as $commandLinePart) {
            if (strstr($commandLinePart, '$USER')) {
                foreach ($this->mapping['macros'] as $oldName => $newName) {
                    if (strstr($commandLinePart, $oldName)) {
                        $newCommandLineParts[] = str_replace($oldName, $newName, $commandLinePart);
                        continue 2;
                    }
                }
            }
            $newCommandLineParts[] = $commandLinePart;
        }
        return implode(' ', $newCommandLineParts);
    }


    public function import($data) {
        if (!empty($data['Macros'])) {
            $this->importMacros($data['Macros']);
        }

        if(!empty($data['Commands'])){
            $this->importCommands($data['Commands']);
        }

        if (!empty($data['Servicetemplates'])) {
            $this->importServicetemplates($data['Servicetemplates']);
        }

    }

    private function importMacros($macros) {
        $this->mapping['macros'] = [];
        foreach ($macros as $macro) {
            $existingMacro = $this->MacrosTable->find()
                ->select(['id'])
                ->where([
                    'value'       => $macro['value'],
                    'description' => $macro['description'],
                ])
                ->first();

            if (!empty($existingMacro)) {
                $this->mapping['macros'][$macro['name']] = $existingMacro->get('name');
                continue;
            }
            $availableMacroNames = $this->MacrosTable->getAvailableMacroNames();
            if (!empty($availableMacroNames)) {
                $newMacroName = array_key_first($availableMacroNames);
                $this->mapping['macros'][$macro['name']] = $newMacroName;
                $macro['name'] = $newMacroName;
                $entity = $this->MacrosTable->newEntity($macro);
                $this->MacrosTable->save($entity);
            }
            //no macro names available anymore
        }
    }

    private function importCommands($commands){
        foreach ($commands as $command) {
            if ($this->CommandsTable->existsByUuid($command['uuid'])){
                //skip command if its already exists
                continue;
            }
        }
    }

    private function importServicetemplates($servicetemplates, $commands) {
        foreach ($servicetemplates as $servicetemplate){

            if ($this->ServicetemplatesTable->existsByUuid($servicetemplate['uuid'])) {
                $existingServicetemplate = $this->ServicetemplatesTable->getServicetemplateByUuid($servicetemplate['uuid']);
                $this->mapping['servicetemplates'][$servicetemplate['id']] = $existingServicetemplate['Servicetemplate']['id'];
                //skip current template if it already exists
                continue;
            }

            if(UUID::is_valid($servicetemplate['command_id'])){

                continue;
            }


        }
    }


}