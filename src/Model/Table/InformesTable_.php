<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Informes Model
 *
 * @method \App\Model\Entity\Informe get($primaryKey, $options = [])
 * @method \App\Model\Entity\Informe newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Informe[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Informe|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Informe saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Informe patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Informe[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Informe findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InformesTable_ extends Table
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

        $this->setTable('informes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idinformes');

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
            ->integer('idinformes')
            ->allowEmptyString('idinformes', null, 'create');

        $validator
            ->date('fecha_inicio')
            ->requirePresence('fecha_inicio', 'create')
            ->notEmptyDate('fecha_inicio');

        $validator
            ->date('fecha_fin')
            ->requirePresence('fecha_fin', 'create')
            ->notEmptyDate('fecha_fin');

        $validator
            ->scalar('worksgroups')
            ->maxLength('worksgroups', 100)
            ->requirePresence('worksgroups', 'create')
            ->notEmptyString('worksgroups');

        $validator
            ->scalar('lote')
            ->maxLength('lote', 100)
            ->allowEmptyString('lote');

        $validator
            ->scalar('parcela')
            ->maxLength('parcela', 100)
            ->allowEmptyString('parcela');

        $validator
            ->scalar('propietario')
            ->maxLength('propietario', 100)
            ->allowEmptyString('propietario');

        $validator
            ->scalar('destino')
            ->maxLength('destino', 100)
            ->allowEmptyString('destino');

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
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('path')
            ->maxLength('path', 255)
            ->allowEmptyString('path');

        return $validator;
    }
}
