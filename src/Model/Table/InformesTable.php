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
 */
class InformesTable extends Table
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->dateTime('fecha')
            ->notEmptyDateTime('fecha');

        $validator
            ->scalar('path')
            ->maxLength('path', 255)
            ->requirePresence('path', 'create')
            ->notEmptyString('path');

        $validator
            ->scalar('grupo')
            ->maxLength('grupo', 100)
            ->requirePresence('grupo', 'create')
            ->notEmptyString('grupo');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        return $validator;
    }
}
