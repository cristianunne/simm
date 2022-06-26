<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProvDeptos Model
 *
 * @method \App\Model\Entity\ProvDepto get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProvDepto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProvDepto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProvDepto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProvDepto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProvDepto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProvDepto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProvDepto findOrCreate($search, callable $callback = null, $options = [])
 */
class ProvDeptosTable extends Table
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

        $this->setTable('prov_deptos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('provincia')
            ->allowEmptyString('provincia');

        $validator
            ->scalar('dpto')
            ->allowEmptyString('dpto');

        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        return $validator;
    }
}
