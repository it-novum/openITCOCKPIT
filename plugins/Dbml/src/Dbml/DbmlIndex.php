<?php

namespace Dbml\Dbml;

class DbmlIndex {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $columns;

    /**
     * @var string
     */
    private $type;

    public function __construct(string $name, array $columns, string $type) {
        if ($type === 'primary') {
            throw new \Exception('Primary Keys are handled automatically and do not need to be created as index');
        }

        if (!in_array($type, ['index', 'unique'], true)) {
            throw new \Exception(sprintf(
                'Index of type "%s" is not supported. Add it to the file %s',
                $type,
                __FILE__
            ));
        }

        $this->name = $name;
        $this->columns = $columns;
        $this->type = $type;
    }


    /**
     * @return string
     */
    public function toDbml() {
        if (sizeof($this->columns) > 1) {
            $dbml = sprintf('    (%s)', implode(', ', $this->columns));
        } else {
            $dbml = '    ' . $this->columns[0];
        }

        $options = [];
        if ($this->type === 'unique') {
            $options[] = 'unique';
        }

        $options[] = sprintf('name: "%s"', $this->name);

        $dbml .= sprintf(' [%s]', implode(', ', $options));
        return $dbml . PHP_EOL;
    }
}
