<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ArreglosMecanicos Model
 *
 * @method \App\Model\Entity\ArreglosMecanico get($primaryKey, $options = [])
 * @method \App\Model\Entity\ArreglosMecanico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArreglosMecanico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArreglosMecanico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanico findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArreglosMecanicosTable extends Table
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

        $this->setTable('arreglos_mecanicos');
        $this->setDisplayField('idarreglos_mecanicos');
        $this->setPrimaryKey('idarreglos_mecanicos');

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

        $this->hasOne('Parcelas', [
            'foreignKey' => 'idparcelas',
            'bindingKey' => 'parcelas_idparcelas', //actual
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
            ->integer('idarreglos_mecanicos')
            ->allowEmptyString('idarreglos_mecanicos', null, 'create');

        $validator
            ->date('fecha')
            ->requirePresence('fecha', 'create')
            ->notEmptyDate('fecha');

        $validator
            ->scalar('num_comprobante')
            ->maxLength('num_comprobante', 100)
            ->allowEmptyString('num_comprobante');

        $validator
            ->scalar('concepto')
            ->maxLength('concepto', 255)
            ->allowEmptyString('concepto');

        $validator
            ->numeric('mano_obra')
            ->allowEmptyString('mano_obra');

        $validator
            ->numeric('repuestos')
            ->allowEmptyString('repuestos');

        $validator
            ->numeric('total')
            ->allowEmptyString('total');

        $validator
            ->integer('maquinas_idmaquinas')
            ->requirePresence('maquinas_idmaquinas', 'create')
            ->notEmptyString('maquinas_idmaquinas');

        $validator
            ->integer('parcelas_idparcelas')
            ->allowEmptyString('parcelas_idparcelas');

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
    public function findGetArreglosByConditions(Query $query, $options = null)
    {

        //Cuando en las condiciones viene el 0, significa que tiene que traer todos
        $conditions = [];

        if(isset($options['worksgroup'])){
            if($options['worksgroup'] != 0 ){
                $conditions['worksgroups_idworksgroups'] = $options['worksgroup'];
            }
        }
        if(isset($options['lotes_idlotes'])){
            if($options['lotes_idlotes'] != 0 && $options['lotes_idlotes'] != null) {
                $conditions['Remitos.lotes_idlotes'] = $options['lotes_idlotes'];
            }
        }
        if(isset($options['parcelas_idparcelas'])){
            if($options['parcelas_idparcelas'] != 0 && $options['parcelas_idparcelas'] != null){
                $conditions['parcelas_idparcelas'] = $options['parcelas_idparcelas'];
            }
        }
        if(isset($options['propietarios_idpropietarios'])){
            if($options['propietarios_idpropietarios'] != 0 && $options['propietarios_idpropietarios'] != null) {
                $conditions['propietarios_idpropietarios'] = $options['propietarios_idpropietarios'];
            }
        }

        if(isset($options['destinos_iddestinos'])){
            if($options['destinos_iddestinos'] != 0 && $options['destinos_iddestinos'] != null){
                $conditions['destinos_iddestinos'] = $options['destinos_iddestinos'];
            }
        }
        if(isset($options['destinos_iddestinos'])){
            if($options['destinos_iddestinos'] != 0 && $options['destinos_iddestinos'] != null){
                $conditions['destinos_iddestinos'] = $options['destinos_iddestinos'];
            }
        }

        $conditions['empresas_idempresas'] = $options['empresas_idempresas'];


        $conditions['MONTH(fecha) ='] = $options['mes'];
        $conditions['YEAR(fecha) ='] = $options['year'];

        $conditions['maquinas_idmaquinas'] = $options['maquina'];



        $result = $query
            ->where($conditions);


        return $result;
    }

}
