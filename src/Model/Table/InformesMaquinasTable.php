<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InformesMaquinas Model
 *
 * @method \App\Model\Entity\InformesMaquina get($primaryKey, $options = [])
 * @method \App\Model\Entity\InformesMaquina newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InformesMaquina[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InformesMaquina|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InformesMaquina saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InformesMaquina patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InformesMaquina[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InformesMaquina findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InformesMaquinasTable extends Table
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

        $this->setTable('informes_maquinas');
        $this->setDisplayField('idinformes_maquinas');
        $this->setPrimaryKey('idinformes_maquinas');

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
            ->integer('idinformes_maquinas')
            ->allowEmptyString('idinformes_maquinas', null, 'create');

        $validator
            ->scalar('fecha_inicio')
            ->maxLength('fecha_inicio', 10)
            ->requirePresence('fecha_inicio', 'create')
            ->notEmptyString('fecha_inicio');

        $validator
            ->scalar('fecha_fin')
            ->maxLength('fecha_fin', 10)
            ->requirePresence('fecha_fin', 'create')
            ->notEmptyString('fecha_fin');

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
            ->integer('maquinas_idmaquinas')
            ->requirePresence('maquinas_idmaquinas', 'create')
            ->notEmptyString('maquinas_idmaquinas');

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

        return $validator;
    }
}
