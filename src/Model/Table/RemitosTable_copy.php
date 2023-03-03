<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Remitos Model
 *
 * @property &\Cake\ORM\Association\BelongsTo $Hashes
 * @property &\Cake\ORM\Association\BelongsToMany $Maquinas
 *
 * @method \App\Model\Entity\Remito get($primaryKey, $options = [])
 * @method \App\Model\Entity\Remito newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Remito[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Remito|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Remito saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Remito patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Remito[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Remito findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RemitosTable_copy extends Table
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

        $this->setTable('remitos');
        $this->setDisplayField('idremitos');
        $this->setPrimaryKey('idremitos');

        $this->addBehavior('Timestamp');

        $this->hasOne('Users', [
            'foreignKey' => 'idusers',
            'bindingKey' => 'users_idusers', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Worksgroups', [
            'foreignKey' => 'idworksgroups',
            'bindingKey' => 'worksgroups_idworksgroups', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Propietarios', [
            'foreignKey' => 'idpropietarios',
            'bindingKey' => 'propietarios_idpropietarios', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Parcelas', [
            'foreignKey' => 'idparcelas',
            'bindingKey' => 'parcelas_idparcelas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Productos', [
            'foreignKey' => 'idproductos',
            'bindingKey' => 'productos_idproductos', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Destinos', [
            'foreignKey' => 'iddestinos',
            'bindingKey' => 'destinos_iddestinos', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasMany('RemitosMaquinas', [
            'foreignKey' => 'remitos_idremitos',
            'bindingKey' => 'idremitos', //actual
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
            ->integer('idremitos')
            ->allowEmptyString('idremitos', null, 'create');

        $validator
            ->requirePresence('remito_number', 'create')
            ->notEmptyString('remito_number');

        $validator
            ->date('fecha')
            ->requirePresence('fecha', 'create')
            ->notEmptyDate('fecha');

        $validator
            ->integer('worksgroups_idworksgroups')
            ->requirePresence('worksgroups_idworksgroups', 'create')
            ->notEmptyString('worksgroups_idworksgroups');

        $validator
            ->integer('parcelas_idparcelas')
            ->requirePresence('parcelas_idparcelas', 'create')
            ->notEmptyString('parcelas_idparcelas');

        $validator
            ->integer('propietarios_idpropietarios')
            ->requirePresence('propietarios_idpropietarios', 'create')
            ->notEmptyString('propietarios_idpropietarios');

        $validator
            ->integer('productos_idproductos')
            ->requirePresence('productos_idproductos', 'create')
            ->notEmptyString('productos_idproductos');

        $validator
            ->numeric('precio_ton')
            ->requirePresence('precio_ton', 'create')
            ->notEmptyString('precio_ton');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->integer('destinos_iddestinos')
            ->requirePresence('destinos_iddestinos', 'create')
            ->notEmptyString('destinos_iddestinos');

        $validator
            ->scalar('ton')
            ->maxLength('ton', 45)
            ->allowEmptyString('ton');

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

        return $rules;
    }

    public function findRemitosByConditions(Query $query, array $options)
    {
        //Cuando en las condiciones viene el 0, significa que tiene que traer todos
        $conditions = [];


        $options = $options[0];

        if($options['worksgroup'] != 0){
            $conditions['worksgroups_idworksgroups'] = $options['worksgroup'];
        }

        if($options['lotes_idlotes'] != 0){
            $conditions['Parcelas.lotes_idlotes'] = $options['lotes_idlotes'];
        }

        if($options['parcelas_idparcelas'] != 0){
            $conditions['parcelas_idparcelas'] = $options['parcelas_idparcelas'];
        }

        if($options['propietarios_idpropietarios'] != 0){
            $conditions['propietarios_idpropietarios'] = $options['propietarios_idpropietarios'];
        }

        if($options['destinos_iddestinos'] != 0){
            $conditions['destinos_iddestinos'] = $options['destinos_iddestinos'];
        }


        $date_start = $options['fecha_inicio'];
        $date_end = $options['fecha_fin'];


        $conditions['fecha >='] = $date_start;
        $conditions['fecha <='] = $date_end;

        debug($conditions);

        return $query->where($conditions)
            ->innerJoinWith('Parcelas')
            ->contain(['RemitosMaquinas' => ['Maquinas' => ['UsoMaquinaria' => ['UsoCombLub'], 'ArreglosMecanicos'], 'Operarios']]);
    }

    /** Utilizo el metodo para devolver un arreglo de los remitos ***/
    public function findRemitosByDate(Query $query, $options = [])
    {

        $array_result = [];

        $options = $options[0];
        $date_start = $options['fecha_inicio'];
        $date_end = $options['fecha_fin'];

        $conditions = [];

        $conditions['fecha >='] = $date_start;
        $conditions['fecha <='] = $date_end;

        $result = $query->where($conditions);

        //Recorro el resultado y lo convierto en un arreglo de una dimension
        foreach ($result as $rem){

            $array_result[] = $rem->idremitos;
        }


        return $array_result;


    }

}
