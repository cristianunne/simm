<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RemitosMaquinas Model
 *
 * @method \App\Model\Entity\RemitosMaquina get($primaryKey, $options = [])
 * @method \App\Model\Entity\RemitosMaquina newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RemitosMaquina[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RemitosMaquina|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RemitosMaquina saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RemitosMaquina patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RemitosMaquina[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RemitosMaquina findOrCreate($search, callable $callback = null, $options = [])
 */
class RemitosMaquinasTable extends Table
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

        $this->setTable('remitos_maquinas');
        $this->setDisplayField('idremitos_maquinas');
        $this->setPrimaryKey('idremitos_maquinas');

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

        $this->hasOne('Remitos', [
            'foreignKey' => 'idremitos',
            'bindingKey' => 'remitos_idremitos', //actual
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
            ->integer('idremitos_maquinas')
            ->allowEmptyString('idremitos_maquinas', null, 'create');

        $validator
            ->integer('remitos_idremitos')
            ->requirePresence('remitos_idremitos', 'create')
            ->notEmptyString('remitos_idremitos');

        $validator
            ->numeric('alquiler_ton')
            ->allowEmptyString('alquiler_ton');

        $validator
            ->integer('operarios_idoperarios')
            ->requirePresence('operarios_idoperarios', 'create')
            ->notEmptyString('operarios_idoperarios');

        $validator
            ->integer('maquinas_idmaquinas')
            ->requirePresence('maquinas_idmaquinas', 'create')
            ->notEmptyString('maquinas_idmaquinas');

        return $validator;
    }



    public function findGetMaquinasByRemitos(Query $query, $options = [])
    {

        $array_result = [];

        if(count($options) > 0)
        {
            $conditions['remitos_idremitos IN'] = $options;


            $res = $query->where($conditions);
            foreach ($query as $q){
                $array_result[$q->maquinas_idmaquinas] = $q->maquinas_idmaquinas;
            }

            return $array_result;
        }

      return false;

    }

}
