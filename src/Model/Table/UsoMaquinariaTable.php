<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsoMaquinaria Model
 *
 * @method \App\Model\Entity\UsoMaquinarium get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsoMaquinarium newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsoMaquinarium[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsoMaquinarium|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsoMaquinarium saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsoMaquinarium patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsoMaquinarium[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsoMaquinarium findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsoMaquinariaTable extends Table
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

        $this->setTable('uso_maquinaria');
        $this->setDisplayField('iduso_maquinaria');
        $this->setPrimaryKey('iduso_maquinaria');

        $this->addBehavior('Timestamp');

        $this->hasOne('Users', [
            'foreignKey' => 'idusers',
            'bindingKey' => 'users_idusers', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Maquinas', [
            'foreignKey' => 'idmaquinas',
            'bindingKey' => 'maquinas_idmaquinas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Empresas', [
            'foreignKey' => 'idempresas',
            'bindingKey' => 'empresas_idempresas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Parcelas', [
            'foreignKey' => 'idparcelas',
            'bindingKey' => 'parcelas_idparcelas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasMany('UsoCombLub', [
            'foreignKey' => 'uso_maquinaria_iduso_maquinaria',
            'bindingKey' => 'iduso_maquinaria', //actual
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
            ->integer('iduso_maquinaria')
            ->allowEmptyString('iduso_maquinaria', null, 'create');

        $validator
            ->integer('maquinas_idmaquinas')
            ->requirePresence('maquinas_idmaquinas', 'create')
            ->notEmptyString('maquinas_idmaquinas');

        $validator
            ->integer('parcelas_idparcelas')
            ->allowEmptyString('parcelas_idparcelas');

        $validator
            ->date('fecha')
            ->requirePresence('fecha', 'create')
            ->notEmptyDate('fecha');

        $validator
            ->numeric('horas_trabajo')
            ->allowEmptyString('horas_trabajo');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        return $validator;
    }


    public function findGetUsoMaquinariaByConditions(Query $query, $options = [])
    {

        //Cuando en las condiciones viene el 0, significa que tiene que traer todos
        $conditions = [];


        if(isset($options['parcelas_idparcelas'])){
            if($options['parcelas_idparcelas'] != 0 && $options['parcelas_idparcelas'] != null){
                $conditions['parcelas_idparcelas'] = $options['parcelas_idparcelas'];
            }
        }



        $conditions['MONTH(fecha) ='] = $options['mes'];
        $conditions['YEAR(fecha) ='] = $options['year'];

        $conditions['maquinas_idmaquinas'] = $options['maquina'];

        $result = $query
            ->contain(['UsoCombLub'])
            ->where($conditions);


        return $result;
    }


    public function findGetUsoMaquinariaByConditionsVariacion(Query $query, $options = [])
    {

        //Cuando en las condiciones viene el 0, significa que tiene que traer todos
        $conditions = [];


        if(isset($options['lotes_idlotes'])){
            if($options['lotes_idlotes'] != 0 && $options['lotes_idlotes'] != null) {
                $conditions['lotes_idlotes'] = $options['lotes_idlotes'];
            }
        }
        if(isset($options['parcelas_idparcelas'])){
            if($options['parcelas_idparcelas'] != 0 && $options['parcelas_idparcelas'] != null){
                $conditions['parcelas_idparcelas'] = $options['parcelas_idparcelas'];
            }
        }



        //$conditions['empresas_idempresas'] = $options['empresas_idempresas'];

        $conditions['MONTH(fecha) ='] = $options['mes'];
        $conditions['YEAR(fecha) ='] = $options['year'];

        $conditions['maquinas_idmaquinas'] = $options['maquina'];


        $result = $query
            ->contain(['Parcelas'])
            ->where($conditions);

        return $result;
    }


}
