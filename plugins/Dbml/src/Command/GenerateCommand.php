<?php
/**
 * Copyright (c) it-novum GmbH (https://it-novum.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) it-novum GmbH (https://it-novum.com)
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

declare(strict_types=1);

namespace Dbml\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\App;
use Cake\Core\Plugin;
use Cake\ORM\Association;
use Cake\ORM\TableRegistry;
use Dbml\Dbml\DbmlAssociation;
use Dbml\Dbml\DbmlColumn;
use Dbml\Dbml\DbmlIndex;
use Dbml\Dbml\DbmlTable;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Generate command.
 */
class GenerateCommand extends Command {

    /**
     * Plugins which tables will be ignored during DBML schema generation
     * @var string[]
     */
    protected $blockedPlugins = [
        'DebugKit',
        'Migrations',
        'Acl'
    ];

    /**
     * @var ConsoleIo
     */
    protected $io;

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
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
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $this->io = $io;

        //$tables = ConnectionManager::get('default')->getSchemaCollection()->listTables();
        //debug($tables);

        //$Table = TableRegistry::getTableLocator()->get('Commands');
        $tables = $this->getTables();

        // Used to filter for duplicates (AcknowledgementHostsTable and AcknowledgementServicesTable are both using the SQL table nagios_acknowledgements for example)
        $duplicates = [];

        $DBML = '';
        foreach ($tables as $pluginName => $pluginTables) {
            foreach ($pluginTables as $table) {
                // Remove file extension
                $tableName = pathinfo($table->getFilename(), \PATHINFO_FILENAME);

                //Remove "Table" suffix
                $tableName = substr($tableName, 0, (strlen('Table') * -1));
                if ($pluginName !== 'APP') {
                    $tableName = $pluginName . '.' . $tableName;
                }

                $Table = TableRegistry::getTableLocator()->get($tableName);

                $driverName = get_class($Table->getConnection()->getDriver());
                try {
                    $isAlreadyDefined = false;
                    if(isset($duplicates[$Table->getTable()])){
                        $isAlreadyDefined = true;
                    }
                    $duplicates[$Table->getTable()] = $Table->getTable();

                    $schema = $Table->getSchema();

                    $DbmlTable = new DbmlTable(
                        $schema->name(),
                        $isAlreadyDefined
                    );
                    $DbmlTable->setComment('CakePHP table Object: ' . $tableName);

                    foreach ($schema->columns() as $columnName) {
                        $column = $schema->getColumn($columnName);
                        $primaryKeyFields = $schema->getPrimaryKey();

                        $isPrimaryKey = in_array($columnName, $primaryKeyFields, true);
                        $DbmlColumn = new DbmlColumn($columnName, $column, $isPrimaryKey, $driverName);

                        $DbmlTable->addColumn($DbmlColumn);
                    }

                    foreach ($schema->constraints() as $constraintName) {
                        $index = $schema->getConstraint($constraintName);
                        if ($index['type'] === 'primary') {
                            continue;
                        }

                        $DbmlIndex = new DbmlIndex($constraintName, $index['columns'], $index['type']);
                        $DbmlTable->addIndex($DbmlIndex);
                    }

                    foreach ($schema->indexes() as $indexName) {
                        $index = $schema->getIndex($indexName);
                        $DbmlIndex = new DbmlIndex($indexName, $index['columns'], $index['type']);
                        $DbmlTable->addIndex($DbmlIndex);
                    }

                    /**
                     * CakePHP associations:
                     * - HasOne => 1:1
                     * - HasMany => 1:n
                     * - BelongsTo => n:1
                     * - BelongsToMany => n:n
                     *
                     * DBML example:
                     * - "> many-to-one", "< one-to-many", "- one-to-one"
                     * - Ref: users.usergroup_id > usergroups.id
                     */
                    foreach ($Table->associations() as $associationName => $association) {
                        $className = $association->getClassName();
                        $AssocTable = TableRegistry::getTableLocator()->get($className);

                        if($association->type() == Association::ONE_TO_ONE ){
                            // HasOne
                            // DBML: -

                            debug($associationName);
                            debug($association);
                            die();


                            /** @var Association\HasOne $association */

                            // usergroups.id ON aros.foreign_key
                            $DbmlAssociation = new DbmlAssociation(
                                $Table->getTable(), //usergroups
                                $AssocTable->getTable(), //aros
                                $association->getPrimaryKey(), //usergroups.id
                                $association->getForeignKey(), //aros.foreign_key
                                'one-to-one',
                                $association->getDependent()
                            );

                            $DbmlTable->addAssociation($DbmlAssociation);
                        }

                        if($association->type() == Association::ONE_TO_MANY ){
                            // HasMany
                            // DBML: <

                            /** @var Association\HasMany $association */

                            // containers.id ON tenants.container_id
                            $DbmlAssociation = new DbmlAssociation(
                                $Table->getTable(), // containers
                                $AssocTable->getTable(), // tenants
                                $association->getPrimaryKey(), // containers.id
                                $association->getForeignKey(), // tenants.container_id
                                'one-to-many',
                                $association->getDependent()
                            );

                            $DbmlTable->addAssociation($DbmlAssociation);
                        }

                        if($association->type() == Association::MANY_TO_ONE ){
                            // BelongsTo
                            // DBML: >

                            /** @var Association\BelongsTo $association */
                            // services.servicetemplate_id ON servicetemplates.id
                            $DbmlAssociation = new DbmlAssociation(
                                $Table->getTable(), // services
                                $AssocTable->getTable(), // servicetemplates
                                $association->getForeignKey(), //services.servicetemplate_id
                                $association->getPrimaryKey(), // servicetemplates.id

                                'many-to-one',
                                $association->getDependent()
                            );

                            $DbmlTable->addAssociation($DbmlAssociation);
                        }

                        if($association->type() == Association::MANY_TO_MANY ){
                            // BelongsToMany

                            // @todo implement me!
                        }
                    }

                    $DBML .= $DbmlTable->toDbml();
                } catch (\Exception $e) {
                    debug(sprintf('Current Table: "%s" - Error: %s', $tableName, $e->getMessage()));
                }
            }
        }

        //$this->io->out($DBML);
    }


    /**
     * Return a List of available Table classes.
     *
     * For plugins, it is required that they are loaded by the Bootstrap (Application.php)
     *
     * @return array
     */
    protected function getTables(): array {
        $result = [
            'APP' => $this->getTablesFromFilesystem(),
        ];


        $plugins = Plugin::loaded();
        foreach ($plugins as $plugin) {
            if (in_array($plugin, $this->blockedPlugins, true)) {
                continue;
            }

            $pluginTables = $this->getTablesFromFilesystem($plugin);
            foreach ($pluginTables as $pluginTable) {
                if (strpos($pluginTable->getFilename(), 'AppModel') !== false) {
                    continue;
                }

                $result[$plugin][] = $pluginTable;
            }
        }

        return $result;
    }


    /**
     * Scrape through the file system and get all Table Class files
     *
     * @return SplFileInfo[]
     */
    protected function getTablesFromFilesystem(?string $plugin = null): array {
        $paths = App::classPath('Model/Table', $plugin);
        foreach ($paths as $index => $path) {
            if (!is_dir($path)) {
                unset($paths[$index]);
            }
        }

        if (empty($paths)) {
            $this->io->out(sprintf('No Tables found for %s', $plugin ? $plugin : 'APP'), 1, ConsoleIo::VERBOSE);
            return [];
        }

        $finder = new Finder();
        $finder->files()->in($paths)->name('/^(\w+)Table\.php$/');


        $tables = [];
        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $tables[] = $file;
        }

        return $tables;
    }

}
