<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MetcostosEmpresas Model
 *
 * @method \App\Model\Entity\MetcostosEmpresa get($primaryKey, $options = [])
 * @method \App\Model\Entity\MetcostosEmpresa newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MetcostosEmpresa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MetcostosEmpresa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MetcostosEmpresa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MetcostosEmpresa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MetcostosEmpresa[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MetcostosEmpresa findOrCreate($search, callable $callback = null, $options = [])
 */
class MetcostosEmpresasTable extends Table
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

        $this->setTable('metcostos_empresas');
        $this->setDisplayField('idmetcostos_empresas');
        $this->setPrimaryKey('idmetcostos_empresas');

        $this->hasOne('Users', [
            'foreignKey' => 'idusers',
            'bindingKey' => 'users_idusers', //actual
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
            ->integer('idmetcostos_empresas')
            ->allowEmptyString('idmetcostos_empresas', null, 'create');

        $validator
            ->integer('metcostos_idmetcostos')
            ->requirePresence('metcostos_idmetcostos', 'create')
            ->notEmptyString('metcostos_idmetcostos');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        return $validator;
    }
}
