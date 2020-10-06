<?php

declare(strict_types=1);

namespace TelegramModule\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use TelegramModule\Lib\TelegramActions;

/**
 * TelegramModule command.
 */
class TelegramProcessUpdatesCommand extends Command {

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
        if ($args->hasOption('api-token') && $args->getOption('api-token') != '') {
            $token = $args->getOption('api-token');
        }

        $TelegramActions = new TelegramActions(isset($token) && $token !== "" ? $token : null);
        if (!$TelegramActions->isTwoWayWebhookEnabled()) {
            $TelegramActions->processUpdates($TelegramActions->getUpdates());
        }
    }
}
