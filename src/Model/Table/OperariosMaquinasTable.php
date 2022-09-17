<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OperariosMaquinas Model
 *
 * @method \App\Model\Entity\OperariosMaquina get($primaryKey, $options = [])
 * @method \App\Model\Entity\OperariosMaquina newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OperariosMaquina[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OperariosMaquina|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OperariosMaquina saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OperariosMaquina patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OperariosMaquina[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OperariosMaquina findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OperariosMaquinasTable extends Table
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

        $this->setTable('operarios_maquinas');
        $this->setDisplayField('idoperarios_maquinas');
        $this->setPrimaryKey('idoperarios_maquinas');

        $this->addBehavior('Timestamp');

        $this->hasOne('Maquinas', [
            'foreignKey' => 'idmaquinas',
            'bindingKey' => 'maquinas_idmaquinas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Operarios', [
            'foreignKey' => 'idoperarios',
            'bindingKey' => 'operarios_idoperarios', //actual
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
            ->integer('idoperarios_maquinas')
            ->allowEmptyString('idoperarios_maquinas', null, 'create');

        $validator
            ->decimal('sueldo')
            ->allowEmptyString('sueldo');

        $validator
            ->integer('operarios_idoperarios')
            ->requirePresence('operarios_idoperarios', 'create')
            ->notEmptyString('operarios_idoperarios');

        $validator
            ->integer('maquinas_idmaquinas')
            ->requirePresence('maquinas_idmaquinas', 'create')
            ->notEmptyString('maquinas_idmaquinas');

        $validator
            ->date('created')
            ->allowEmptyDate('created');

        $validator
            ->date('finished')
            ->allowEmptyDate('finished');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        return $validator;
    }
}
