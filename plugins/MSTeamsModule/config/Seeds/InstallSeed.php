<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Class InstallSeed
 *
 * Created:
 * oitc4 bake seed -p SlackModule --table commands --data Install
 *
 * Apply:
 * oitc4 migrations seed -p SlackModule
 */
class InstallSeed extends AbstractSeed {

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run(): void {
        //Commands
        $table = $this->table('commands');

        $data = [
            [
                'name'         => 'host-notifiy-by-teams',
                'command_line' => '/opt/openitc/frontend/bin/cake MSTeamsModule.teams_notification --type Host --notificationtype $NOTIFICATIONTYPE$ --hostuuid "$HOSTNAME$" --state "$HOSTSTATEID$" --output "$HOSTOUTPUT$"',
                'command_type' => NOTIFICATION_COMMAND,
                'human_args'   => null,
                'uuid'         => \itnovum\openITCOCKPIT\Core\UUID::v4(),
                'description'  => 'Send host notifications to Microsoft Teams'
            ],
            [
                'name'         => 'service-notify-by-teams',
                'command_line' => '/opt/openitc/frontend/bin/cake MSTeamsModule.teams_notification --type Service --notificationtype $NOTIFICATIONTYPE$ --hostuuid "$HOSTNAME$" --serviceuuid "$SERVICEDESC$" --state "$SERVICESTATEID$" --output "$SERVICEOUTPUT$""',
                'command_type' => NOTIFICATION_COMMAND,
                'human_args'   => null,
                'uuid'         => \itnovum\openITCOCKPIT\Core\UUID::v4(),
                'description'  => 'Send service notifications to Microsoft Teams'
            ]
        ];

        //Check if records exists
        foreach ($data as $index => $record) {
            $QueryBuilder = $this->getAdapter()->getQueryBuilder();

            $stm = $QueryBuilder->select('*')
                ->from($table->getName())
                ->where([
                    'command_type' => NOTIFICATION_COMMAND,
                    'name'         => $record['name']
                ])
                ->execute();
            $result = $stm->fetchAll();

            if (empty($result)) {
                $table->insert($record)->save();
            }
        }
    }
}
