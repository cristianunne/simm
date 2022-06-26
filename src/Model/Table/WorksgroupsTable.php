<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Worksgroups Model
 *
 * @property \App\Model\Table\HashesTable&\Cake\ORM\Association\BelongsTo $Hashes
 *
 * @method \App\Model\Entity\Worksgroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Worksgroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Worksgroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Worksgroup|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Worksgroup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Worksgroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Worksgroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Worksgroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorksgroupsTable extends Table
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

        $this->setTable('worksgroups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idworksgroups');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hashes', [
            'foreignKey' => 'hash_id',
            'joinType' => 'INNER',
        ]);

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
            ->integer('idworksgroups')
            ->allowEmptyString('idworksgroups', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
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
            ->boolean('active')
            ->allowEmptyString('active');

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
