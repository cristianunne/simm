<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Parcelas Model
 *
 * @method \App\Model\Entity\Parcela get($primaryKey, $options = [])
 * @method \App\Model\Entity\Parcela newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Parcela[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Parcela|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Parcela saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Parcela patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Parcela[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Parcela findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ParcelasTable extends Table
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

        $this->setTable('parcelas');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idparcelas');

        $this->addBehavior('Timestamp');

        $this->hasOne('Users', [
            'foreignKey' => 'idusers',
            'bindingKey' => 'users_idusers', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Propietarios', [
            'foreignKey' => 'idpropietarios',
            'bindingKey' => 'propietarios_idpropietarios', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Lotes', [
            'foreignKey' => 'idlotes',
            'bindingKey' => 'lotes_idlotes', //actual
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
            ->integer('idparcelas')
            ->allowEmptyString('idparcelas', null, 'create')
            ->add('idparcelas', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->date('finished')
            ->allowEmptyDate('finished');

        $validator
            ->integer('lotes_idlotes')
            ->requirePresence('lotes_idlotes', 'create')
            ->notEmptyString('lotes_idlotes');

        $validator
            ->integer('propietarios_idpropietarios')
            ->requirePresence('propietarios_idpropietarios', 'create')
            ->notEmptyString('propietarios_idpropietarios');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['idparcelas']));

        return $rules;
    }
}
