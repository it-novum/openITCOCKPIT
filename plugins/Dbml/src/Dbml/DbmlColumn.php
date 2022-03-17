<?php

namespace Dbml\Dbml;

class DbmlColumn {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int|null
     */
    private $length;

    /**
     * @var bool
     */
    private $unsigned;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var string|null
     */
    private $default;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @var bool
     */
    private $primaryKey;

    /**
     * @var bool
     */
    private $autoIncrement;

    /**
     * @param string $name name of column in database
     * @param array $column
     * @param bool $isPrimaryKey
     * @param string $driverName
     * @throws \Exception
     */
    public function __construct(string $name, array $column, bool $isPrimaryKey, string $driverName) {
        $this->name = $name;
        $this->primaryKey = $isPrimaryKey;

        $typeMap = TypeMaps::getTypeMap($driverName);
        if (!isset($typeMap[$column['type']])) {
            throw new \Exception(sprintf(
                'Unknown data type "%s". Please add it to the file Dbml\Dbml\TypeMaps.php for the Driver "%s".',
                $column['type'],
                $driverName
            ));
        }

        $this->type = $typeMap[$column['type']];
        $this->length = $column['length'];
        $this->unsigned = $column['unsigned'] ?? false;
        $this->nullable = $column['null'];
        $this->default = $column['default'];
        $this->comment = $column['comment'];
        $this->autoIncrement = $column['autoIncrement'] ?? false;
    }

    /**
     * Column as DBML definition.
     * @return string
     */
    public function toDbml() {
        if (empty($this->unsigned)) {
            $dbml = sprintf('  "%s" %s', $this->name, $this->type);
            if ($this->length) {
                $dbml .= sprintf('(%s)', $this->length);
            }
        } else {
            // https://www.dbml.org/docs/#column-settings
            $dbml = sprintf('  "%s" "%s', $this->name, $this->type);
            if ($this->length) {
                $dbml .= sprintf('(%s)', $this->length);
            }
            $dbml .= ' unsigned"';
        }

        $options = [];
        if ($this->primaryKey) {
            $options[] = 'pk';
        }

        if ($this->nullable === false) {
            $options[] = 'not null';
        }

        if ($this->autoIncrement) {
            $options[] = 'increment';
        }

        if ($this->default === null) {
            $options[] = 'default: NULL';
        }

        if ($this->default) {
            $options[] = sprintf('default: "%s"', $this->default);
        }

        if ($this->comment) {
            $options[] = sprintf('note: "%s"', $this->comment);
        }

        if (!empty($options)) {
            $dbml .= sprintf(' [%s]', implode(', ', $options));
        }

        return $dbml . PHP_EOL;
    }

}
