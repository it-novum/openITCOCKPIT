<?php
declare(strict_types=1);

namespace DesignModule\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Designs Model
 *
 * @method \DesignModule\Model\Entity\Design newEmptyEntity()
 * @method \DesignModule\Model\Entity\Design newEntity(array $data, array $options = [])
 * @method \DesignModule\Model\Entity\Design[] newEntities(array $data, array $options = [])
 * @method \DesignModule\Model\Entity\Design get($primaryKey, $options = [])
 * @method \DesignModule\Model\Entity\Design findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \DesignModule\Model\Entity\Design patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \DesignModule\Model\Entity\Design[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \DesignModule\Model\Entity\Design|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \DesignModule\Model\Entity\Design saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \DesignModule\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \DesignModule\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \DesignModule\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \DesignModule\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DesignsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('designs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('page_header')
            ->maxLength('page_header', 255)
            ->requirePresence('page_header', 'create')
            ->notEmptyString('page_header');

        $validator
            ->scalar('header-btn')
            ->maxLength('header-btn', 255)
            ->requirePresence('header-btn', 'create')
            ->notEmptyString('header-btn');

        $validator
            ->scalar('page-sidebar')
            ->maxLength('page-sidebar', 255)
            ->requirePresence('page-sidebar', 'create')
            ->notEmptyString('page-sidebar');

        $validator
            ->scalar('nav-title')
            ->maxLength('nav-title', 255)
            ->requirePresence('nav-title', 'create')
            ->notEmptyString('nav-title');

        $validator
            ->scalar('nav-menu')
            ->maxLength('nav-menu', 255)
            ->requirePresence('nav-menu', 'create')
            ->notEmptyString('nav-menu');

        $validator
            ->scalar('nav-menu-hover')
            ->maxLength('nav-menu-hover', 255)
            ->requirePresence('nav-menu-hover', 'create')
            ->notEmptyString('nav-menu-hover');

        $validator
            ->scalar('nav-tabs')
            ->maxLength('nav-tabs', 255)
            ->requirePresence('nav-tabs', 'create')
            ->notEmptyString('nav-tabs');

        $validator
            ->scalar('nav-tabs-hover')
            ->maxLength('nav-tabs-hover', 255)
            ->requirePresence('nav-tabs-hover', 'create')
            ->notEmptyString('nav-tabs-hover');

        $validator
            ->scalar('page-content')
            ->maxLength('page-content', 255)
            ->requirePresence('page-content', 'create')
            ->notEmptyString('page-content');

        $validator
            ->scalar('page-content-wrapper')
            ->maxLength('page-content-wrapper', 255)
            ->requirePresence('page-content-wrapper', 'create')
            ->notEmptyString('page-content-wrapper');

        $validator
            ->scalar('panel-hdr')
            ->maxLength('panel-hdr', 255)
            ->requirePresence('panel-hdr', 'create')
            ->notEmptyString('panel-hdr');

        $validator
            ->scalar('panel')
            ->maxLength('panel', 255)
            ->requirePresence('panel', 'create')
            ->notEmptyString('panel');

        $validator
            ->scalar('breadcrumb-links')
            ->maxLength('breadcrumb-links', 255)
            ->requirePresence('breadcrumb-links', 'create')
            ->notEmptyString('breadcrumb-links');

        $validator
            ->requirePresence('logo-in-header', 'create')
            ->notEmptyString('logo-in-header');

        return $validator;
    }
}
