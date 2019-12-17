<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

namespace App\Command;

use App\itnovum\openITCOCKPIT\Monitoring\Naemon\ExternalCommands;
use App\Model\Entity\CalendarHoliday;
use App\Model\Entity\Service;
use App\Model\Table\CalendarsTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;

/**
 * Testing command.
 */
class TestingCommand extends Command {

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);
        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $DbBackend = new DbBackend();

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        //$this->Services = TableRegistry::getTableLocator()->get('Services');
        //debug($this->Services->find()->contain(['Mkservicedata', 'Servicecommandargumentvalues'])->where([
        //    'Services.id' => 2
        //])->toArray());//, 'NewModule.Servicecommandargumentvalues']));

        /**
         * Lof of space for your experimental code
         * Have fun :)
         */

        /** @var CalendarsTable $CalendarsTable */
        $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');

        try {
            $calendar = $CalendarsTable->getCalendarById(1);
            foreach ($calendar->get('calendar_holidays') as $holiday) {
                /** @var CalendarHoliday $holiday */
                $timestamp = strtotime(sprintf('%s 00:00', $holiday->get('date')));

                $calendarDay = sprintf('%s 00:00-24:00; %s',
                    strtolower(date('F j', $timestamp)),
                    $holiday->get('name')
                );
                $content .= $this->addContent($calendarDay, 1);
            }
        }catch (RecordNotFoundException $e){
            Log::error('NagiosConfigGenerator: Calendar not found');
            Log::error($e->getMessage());
        }

        die();




        //Merge Calendar records to timeperiod
        if ($timeperiod['Timeperiod']['calendar_id'] > 0) {
            $calendar = $this->Calendar->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Calendar.id' => $timeperiod['Timeperiod']['calendar_id']
                ],
                'contain'    => ['CalendarHoliday']
            ]);
            foreach ($calendar['CalendarHoliday'] as $holiday) {
                $timestamp = strtotime(sprintf('%s 00:00', $holiday['date']));

                $calendarDay = sprintf('%s 00:00-24:00; %s',
                    strtolower(date('F j', $timestamp)),
                    $holiday['name']
                );
                $content .= $this->addContent($calendarDay, 1);
            }
        }

    }
}
