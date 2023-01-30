<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ListaConstantes Model
 *
 * @method \App\Model\Entity\ListaConstante get($primaryKey, $options = [])
 * @method \App\Model\Entity\ListaConstante newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ListaConstante[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ListaConstante|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ListaConstante saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ListaConstante patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ListaConstante[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ListaConstante findOrCreate($search, callable $callback = null, $options = [])
 */
class ListaConstantesTable extends Table
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

        $this->setTable('lista_constantes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idlista_constantes');

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
            ->integer('idlista_constantes')
            ->allowEmptyString('idlista_constantes', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 45)
            ->allowEmptyString('name');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        return $validator;
    }
}
