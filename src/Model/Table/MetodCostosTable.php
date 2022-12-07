<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MetodCostos Model
 *
 * @method \App\Model\Entity\MetodCosto get($primaryKey, $options = [])
 * @method \App\Model\Entity\MetodCosto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MetodCosto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MetodCosto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MetodCosto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MetodCosto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MetodCosto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MetodCosto findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MetodCostosTable extends Table
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

        $this->setTable('metod_costos');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idmetod_costos');

        $this->addBehavior('Timestamp');

        $this->hasOne('Users', [
            'foreignKey' => 'idusers',
            'bindingKey' => 'users_idusers', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Empresas', [
            'foreignKey' => 'idempresas',
            'bindingKey' => 'empresas_idempresas', //actual
            'joinType' => 'INNER'
        ]);
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
            ->integer('idmetod_costos')
            ->allowEmptyString('idmetod_costos', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('interes')
            ->maxLength('interes', 250)
            ->allowEmptyString('interes');

        $validator
            ->scalar('seguro')
            ->maxLength('seguro', 250)
            ->allowEmptyString('seguro');

        $validator
            ->scalar('dep_maq')
            ->maxLength('dep_maq', 250)
            ->allowEmptyString('dep_maq');

        $validator
            ->scalar('dep_neum')
            ->maxLength('dep_neum', 250)
            ->allowEmptyString('dep_neum');

        $validator
            ->scalar('arreglos_maq')
            ->maxLength('arreglos_maq', 250)
            ->allowEmptyString('arreglos_maq');

        $validator
            ->scalar('cons_comb')
            ->maxLength('cons_comb', 250)
            ->allowEmptyString('cons_comb');

        $validator
            ->scalar('cons_lub')
            ->maxLength('cons_lub', 250)
            ->allowEmptyString('cons_lub');

        $validator
            ->scalar('operador')
            ->maxLength('operador', 250)
            ->allowEmptyString('operador');

        $validator
            ->scalar('mantenimiento')
            ->maxLength('mantenimiento', 250)
            ->allowEmptyString('mantenimiento');

        $validator
            ->scalar('administracion')
            ->maxLength('administracion', 250)
            ->allowEmptyString('administracion');

        $validator
            ->date('finished')
            ->allowEmptyDate('finished');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        return $validator;
    }
}
