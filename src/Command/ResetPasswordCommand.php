<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Table\SystemsettingsTable;
use App\Model\Table\UsersTable;
use Cake\Console\Arguments;
use Cake\Command\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\Views\Logo;

/**
 * ResetPassword command.
 */
class ResetPasswordCommand extends Command {

    /**
     * @var ConsoleIo
     */
    private $io;

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

        $parser->addOption('print', [
            'help'    => __('If set the new password will get print to the CLI'),
            'boolean' => true,
            'default' => false
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $this->io = $io;

        if ($_SERVER['USER'] !== 'root') {
            $io->error('This command needs to be executed as root user');
            exit(1);
        }

        $print = $args->getOption('print') === true;

        $users = $this->getAllUsersWithPassword();

        $io->info('Please select the your, you want to reset the password for:');

        foreach ($users as $user) {
            $io->out(sprintf(
                    '[%s] %s, %s (%s)',
                    $user['id'],
                    $user['firstname'],
                    $user['lastname'],
                    $user['email']
                )
            );
        }

        $userIds = Hash::extract($users->toArray(), '{n}.id');

        $selection = $io->askChoice('Please select a user ID', $userIds, (string)$userIds[0]);

        $this->resetPassword($selection, $print);
    }

    private function getAllUsersWithPassword() {
        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $users = $UsersTable->find()
            ->where(['password !=' => ''])
            ->disableHydration()
            ->all();

        return $users;
    }

    private function resetPassword($userId, $print = false) {
        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        $systemsettings = $SystemsettingsTable->findAsArray();

        if (!$UsersTable->existsById($userId)) {
            throw new \RuntimeException('User not found!');
        }

        $user = $UsersTable->get($userId);
        $newPassword = $UsersTable->generatePassword();

        $user->set('password', $newPassword);

        $Logo = new Logo();

        $Mailer = new Mailer();
        $Mailer->setFrom($systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'], $systemsettings['MONITORING']['MONITORING.FROM_NAME']);
        $Mailer->addTo($user->get('email'));
        $Mailer->setSubject(__('Your ') . $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'] . __(' got reset!'));
        $Mailer->setEmailFormat('text');
        $Mailer->setAttachments([
            'logo.png' => [
                'file'      => $Logo->getSmallLogoDiskPath(),
                'mimetype'  => 'image/png',
                'contentId' => '100'
            ]
        ]);
        $Mailer->viewBuilder()
            ->setTemplate('reset_password')
            ->setVar('systemname', $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'])
            ->setVar('newPassword', $newPassword);

        $user->set('password', $newPassword);

        $UsersTable->save($user);

        if ($print) {
            $this->io->success(__('New password is: {0}', $newPassword));
        }

        $Mailer->deliver();
        $this->io->success(__('New password was send to {0}', $user->email));
    }
}
