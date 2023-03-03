<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Maquinas Model
 *
 * @property \App\Model\Table\OperariosTable&\Cake\ORM\Association\BelongsToMany $Operarios
 *
 * @method \App\Model\Entity\Maquina get($primaryKey, $options = [])
 * @method \App\Model\Entity\Maquina newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Maquina[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Maquina|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Maquina saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Maquina patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Maquina[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Maquina findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MaquinasTable extends Table
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

        $this->setTable('maquinas');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idmaquinas');

        $this->addBehavior('Timestamp');

        /*$this->belongsToMany('Operarios', [
            'foreignKey' => 'maquina_id',
            'targetForeignKey' => 'operario_id',
            'joinTable' => 'operarios_maquinas',
        ]);*/

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

        //AGregare una relacion BelongTo aqui


        $this->hasMany('OperariosMaquinas', [
            'foreignKey' => 'maquinas_idmaquinas',
            'bindingKey' => 'idmaquinas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasMany('CostosMaquinas', [
            'foreignKey' => 'maquinas_idmaquinas',
            'bindingKey' => 'idmaquinas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasMany('UsoMaquinaria', [
            'foreignKey' => 'maquinas_idmaquinas',
            'bindingKey' => 'idmaquinas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasMany('ArreglosMecanicos', [
            'foreignKey' => 'maquinas_idmaquinas',
            'bindingKey' => 'idmaquinas', //actual
            'joinType' => 'INNER'
        ]);

        /** Esto es un BelonTo **/

        $this->belongsToMany('Remitos', [
            'foreignKey' => 'maquinas_idmaquinas',
            'bindingKey' => 'idmaquinas', //actual
            'targetForeignKey' => 'remitos_idremitos',
            'joinTable' => 'RemitosMaquinas',
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
            ->integer('idmaquinas')
            ->allowEmptyString('idmaquinas', null, 'create')
            ->add('idmaquinas', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('marca')
            ->maxLength('marca', 150)
            ->allowEmptyString('marca');

        $validator
            ->boolean('propia')
            ->requirePresence('propia', 'create')
            ->notEmptyString('propia');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->date('finished')
            ->allowEmptyDate('finished');

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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['idmaquinas']));

        return $rules;
    }

    /**
     * @param Query $
     * @param $option
     * @return array
     * Return maquinas by date especified in remitos
     */
    public function findGetMaquinasByDateRemitos(Query query, $option = [])
    {

        $array_maquinas = [];





        return $array_maquinas;
    }


}
