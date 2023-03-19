<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InformesResumen Model
 *
 * @method \App\Model\Entity\InformesResuman get($primaryKey, $options = [])
 * @method \App\Model\Entity\InformesResuman newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InformesResuman[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InformesResuman|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InformesResuman saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InformesResuman patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InformesResuman[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InformesResuman findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InformesResumenTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('informes_resumen');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idinformes_resumen');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('idinformes_resumen')
            ->allowEmptyString('idinformes_resumen', null, 'create');

        $validator
            ->date('fecha_inicio')
            ->requirePresence('fecha_inicio', 'create')
            ->notEmptyDate('fecha_inicio');

        $validator
            ->date('fecha_fin')
            ->requirePresence('fecha_fin', 'create')
            ->notEmptyDate('fecha_fin');

        $validator
            ->scalar('categoria')
            ->maxLength('categoria', 45)
            ->requirePresence('categoria', 'create')
            ->notEmptyString('categoria');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->scalar('path')
            ->maxLength('path', 255)
            ->allowEmptyString('path');

        $validator
            ->scalar('clasificador')
            ->maxLength('clasificador', 105)
            ->requirePresence('clasificador', 'create')
            ->notEmptyString('clasificador');

        $validator
            ->scalar('producto')
            ->maxLength('producto', 100)
            ->notEmptyString('producto');

        return $validator;
    }
}
