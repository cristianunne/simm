<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InformesVariaciones Model
 *
 * @method \App\Model\Entity\InformesVariacione get($primaryKey, $options = [])
 * @method \App\Model\Entity\InformesVariacione newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InformesVariacione[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InformesVariacione|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InformesVariacione saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InformesVariacione patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InformesVariacione[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InformesVariacione findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InformesVariacionesTable extends Table
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

        $this->setTable('informes_variaciones');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idinformes_variaciones');

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
            ->integer('idinformes_variaciones')
            ->allowEmptyString('idinformes_variaciones', null, 'create');

        $validator
            ->scalar('fecha_inicio')
            ->maxLength('fecha_inicio', 45)
            ->requirePresence('fecha_inicio', 'create')
            ->notEmptyString('fecha_inicio');

        $validator
            ->scalar('fecha_fin')
            ->maxLength('fecha_fin', 45)
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
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('path')
            ->maxLength('path', 255)
            ->requirePresence('path', 'create')
            ->notEmptyString('path');

        $validator
            ->scalar('tipo')
            ->maxLength('tipo', 100)
            ->allowEmptyString('tipo');

        $validator
            ->scalar('maquina')
            ->maxLength('maquina', 100)
            ->allowEmptyString('maquina');

        $validator
            ->scalar('worksgroups')
            ->maxLength('worksgroups', 100)
            ->allowEmptyString('worksgroups');

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
