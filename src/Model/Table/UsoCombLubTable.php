<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsoCombLub Model
 *
 * @method \App\Model\Entity\UsoCombLub get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsoCombLub newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsoCombLub[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsoCombLub|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsoCombLub saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsoCombLub patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsoCombLub[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsoCombLub findOrCreate($search, callable $callback = null, $options = [])
 */
class UsoCombLubTable extends Table
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

        $this->setTable('uso_comb_lub');
        $this->setDisplayField('iduso_comb_lub');
        $this->setPrimaryKey('iduso_comb_lub');

        $this->hasOne('UsoMaquinaria', [
            'foreignKey' => 'iduso_maquinaria',
            'bindingKey' => 'uso_maquinaria_iduso_maquinaria', //actual
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
            ->integer('iduso_comb_lub')
            ->allowEmptyString('iduso_comb_lub', null, 'create');

        $validator
            ->scalar('categoria')
            ->maxLength('categoria', 45)
            ->requirePresence('categoria', 'create')
            ->notEmptyString('categoria');

        $validator
            ->scalar('producto')
            ->maxLength('producto', 100)
            ->requirePresence('producto', 'create')
            ->notEmptyString('producto');

        $validator
            ->numeric('litros')
            ->requirePresence('litros', 'create')
            ->notEmptyString('litros');

        $validator
            ->integer('uso_maquinaria_iduso_maquinaria')
            ->requirePresence('uso_maquinaria_iduso_maquinaria', 'create')
            ->notEmptyString('uso_maquinaria_iduso_maquinaria');

        $validator
            ->numeric('precio')
            ->requirePresence('precio', 'create')
            ->notEmptyString('precio');

        return $validator;
    }
}
