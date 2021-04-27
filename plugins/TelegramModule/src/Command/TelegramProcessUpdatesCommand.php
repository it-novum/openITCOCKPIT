<?php

declare(strict_types=1);

namespace TelegramModule\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use TelegramModule\Lib\TelegramActions;

/**
 * TelegramModule command.
 */
class TelegramProcessUpdatesCommand extends Command implements CronjobInterface {

    /**
     * @param ConsoleOptionParser $parser
     * @return ConsoleOptionParser
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        $parser->addOptions([
            'api-token' => ['help' => __d('oitc_console', 'Overwrites the stored Telegram bot HTTP API token')],
        ]);

        return $parser;
    }

    /**
     * @param Arguments $args
     * @param ConsoleIo $io
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $token = null;
        if ($args->hasOption('api-token') && $args->getOption('api-token') != '') {
            $token = $args->getOption('api-token');
        }

        $TelegramActions = new TelegramActions($token);

        if ($TelegramActions->hasToken() === false) {
            $io->error(__d('oitc_console', 'No telegram bot token configured!'));
        }

        if (!$TelegramActions->isTwoWayWebhookEnabled() && $TelegramActions->hasToken()) {
            $TelegramActions->processUpdates($TelegramActions->getUpdates());
        }
        $io->hr();
    }
}
