<?php

namespace Dbml\Dbml;

use phpDocumentor\Reflection\Types\Boolean;

class DbmlTable {

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool|Boolean
     */
    private $isTableAlreadyDefined = false;

    /**
     * @var DbmlColumn[]
     */
    private $columns = [];

    /**
     * @var DbmlIndex[]
     */
    private $indexes = [];

    /**
     * @var DbmlAssociation[]
     */
    private $associations = [];

    /**
     * @var null|string
     */
    private $comment;

    /**
     * @param string $name name of table in database
     * @param Boolean $isTableAlreadyDefined
     */
    public function __construct(string $name, bool $isTableAlreadyDefined = false) {
        $this->name = $name;
        $this->isTableAlreadyDefined = $isTableAlreadyDefined;
    }

    public function addColumn(DbmlColumn $column) {
        $this->columns[] = $column;
    }

    public function addIndex(DbmlIndex $index) {
        $this->indexes[] = $index;
    }

    public function addAssociation(DbmlAssociation $assoc) {
        $this->associations[] = $assoc;
    }

    public function setComment(string $comment) {
        $this->comment = $comment;
    }

    /**
     * @param string|null $notes Optional table comment
     * @return string
     */
    public function toDbml() {
        $dbml = sprintf('Table "%s" {%s', $this->name, PHP_EOL);
        foreach ($this->columns as $column) {
            $dbml .= $column->toDbml();
        }

        if ($this->comment) {
            $dbml .= sprintf('%s  Note: "%s"%s', PHP_EOL, $this->comment, PHP_EOL);
        }

        if (!empty($this->indexes)) {
            $dbml .= PHP_EOL;
            $dbml .= '  Indexes {' . PHP_EOL;
            foreach ($this->indexes as $index) {
                $dbml .= $index->toDbml();
            }
            $dbml .= '  }' . PHP_EOL;
        }

        $dbml .= '}' . PHP_EOL;

        if ($this->isTableAlreadyDefined) {
            // Table is already defined in DBML. So only add the associations
            $dbml = '';
        }

        if (!empty($this->associations)) {
            $dbml .= PHP_EOL;
            foreach ($this->associations as $association) {
                $dbml .= $association->toDbml();
            }
        }

        return $dbml . PHP_EOL;
    }
}
