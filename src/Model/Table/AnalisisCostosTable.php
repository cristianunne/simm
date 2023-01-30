<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AnalisisCostos Model
 *
 * @method \App\Model\Entity\AnalisisCosto get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnalisisCosto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnalisisCosto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnalisisCosto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnalisisCosto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnalisisCosto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnalisisCosto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnalisisCosto findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnalisisCostosTable extends Table
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

        $this->setTable('analisis_costos');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idanalisis_costos');

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
            ->integer('idanalisis_costos')
            ->allowEmptyString('idanalisis_costos', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('grupo')
            ->maxLength('grupo', 100)
            ->requirePresence('grupo', 'create')
            ->notEmptyString('grupo');

        $validator
            ->scalar('path')
            ->maxLength('path', 255)
            ->requirePresence('path', 'create')
            ->notEmptyString('path');

        return $validator;
    }
}
