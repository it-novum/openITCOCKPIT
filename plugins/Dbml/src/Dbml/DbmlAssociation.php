<?php

namespace Dbml\Dbml;

class DbmlAssociation {

    /**
     * @var string[]
     */
    private $supportedTypes = [
        'one-to-one'  => '-',
        'one-to-many' => '<',
        'many-to-one' => '>',
        //'many-to-many' // Unsupported by DBAL - use two many-to-one relationships
    ];

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $targetTableName;

    /**
     * @var string[]
     */
    private $foreignKeys;

    /**
     * @var string[]
     */
    private $targetForeignKeys;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $deleteCascade;

    /**
     * @param string $tableName
     * @param string $targetTableName
     * @param string|array $foreignKeys
     * @param string|array $targetForeignKeys
     * @param string $type
     * @param bool $deleteCascade
     * @throws \Exception
     */
    public function __construct(string $tableName, string $targetTableName, $foreignKeys, $targetForeignKeys, string $type, bool $deleteCascade = false) {
        if (!in_array($type, array_keys($this->supportedTypes))) {
            throw new \Exception(sprintf(
                    'Association type "%s" is not supported by now. Add it in file %s', $type, __FILE__)
            );
        }
        $this->type = $type;

        $this->tableName = $tableName;
        $this->targetTableName = $targetTableName;
        if (!is_array($foreignKeys)) {
            $foreignKeys = [$foreignKeys];
        }
        $this->foreignKeys = $foreignKeys;

        if (!is_array($targetForeignKeys)) {
            $targetForeignKeys = [$targetForeignKeys];
        }
        $this->targetForeignKeys = $targetForeignKeys;
        $this->deleteCascade = $deleteCascade;
    }


    public function toDbml() {
        // Examples:
        // Ref: products.merchant_id > merchants.id // many-to-one
        // Ref: merchant_periods.(merchant_id, country_code) > merchants.(id, country_code) //composite foreign key
        $dbml = sprintf('Ref: %s.', $this->tableName);

        if (sizeof($this->foreignKeys) > 1) {
            $dbml .= sprintf('(%s)', implode(', ', $this->foreignKeys));
        } else {
            $dbml .= $this->foreignKeys[0];
        }

        $dbml .= sprintf(' %s ', $this->supportedTypes[$this->type]);

        $dbml .= sprintf('%s.', $this->targetTableName);
        if (sizeof($this->targetForeignKeys) > 1) {
            $dbml .= sprintf('(%s)', implode(', ', $this->targetForeignKeys));
        } else {
            $dbml .= $this->targetForeignKeys[0];
        }

        return $dbml . PHP_EOL;
    }

}
