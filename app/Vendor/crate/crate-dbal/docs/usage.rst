=======================
Usage with Doctrine ORM
=======================

.. code-block:: php

  <?php
  require_once "vendor/autoload.php";

  use Doctrine\ORM\Tools\Setup;
  use Doctrine\ORM\EntityManager;

  $paths = array("/path/to/entity-files");
  $isDevMode = false;

  // the connection configuration
  $params = array(
      'driverClass' => 'Crate\DBAL\Driver\PDOCrate\Driver',
      'host' => 'localhost',
      'port' => 4200
  );

  $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
  $entityManager = EntityManager::create($params, $config);

For a more detailed configuration please refer to the `Doctrine ORM`_
documentation.


Supported Types
===============

The following Crate data types are currently supported:

- ``BOOLEAN``
- ``STRING``
- ``SHORT``
- ``INTEGER``
- ``LONG``
- ``FLOAT``
- ``DOUBLE``
- ``TIMESTAMP``
- ``OBJECT``
- ``ARRAY``

Limitations
===========

The schema for the ``OBJECT`` and ``ARRAY`` data types can be defined only
programmatically.

Example:

.. code-block:: php

  <?php
  use Doctrine\DBAL\Schema\Column;
  use Doctrine\DBAL\Schema\Table;
  use Doctrine\DBAL\Types\Type;
  use Crate\DBAL\Types\MapType;

  $table = new Table('test_table');
  $objDefinition = array(
    'type' => MapType::STRICT,
     'fields' => array(
       new Column('id',  Type::getType('integer'), array()),
       new Column('name',  Type::getType('string'), array()),
       ),
     );
  $table->addColumn('object_column', MapType::NAME,
                    array('platformOptions'=>$objDefinition));
  $schemaManager->createTable($table);


Not Supported
=============

- fulltext indexes
- JOINs in general are not supported,
  however referencing relations can be done without joins
  using Doctrine's lazy loading mechanism with subsequent SELECTs
  (except many-to-many releations)
- `DQL`_ statements with JOINs are not supported



.. _`Doctrine ORM`: http://doctrine-orm.readthedocs.org/en/latest/reference/configuration.html
.. _`DQL`: http://doctrine-orm.readthedocs.org/en/latest/reference/dql-doctrine-query-language.html
