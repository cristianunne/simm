<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CostosMaquinas Model
 *
 * @property &\Cake\ORM\Association\BelongsTo $Hashes
 *
 * @method \App\Model\Entity\CostosMaquina get($primaryKey, $options = [])
 * @method \App\Model\Entity\CostosMaquina newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CostosMaquina[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CostosMaquina|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CostosMaquina saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CostosMaquina patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CostosMaquina[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CostosMaquina findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CostosMaquinasTable extends Table
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

        $this->setTable('costos_maquinas');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idcostos_maquinas');

        $this->addBehavior('Timestamp');


        $this->hasOne('Maquinas', [
            'foreignKey' => 'idmaquinas',
            'bindingKey' => 'maquinas_idmaquinas', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Worksgroups', [
            'foreignKey' => 'idworksgroups',
            'bindingKey' => 'worksgroups_idworksgroups', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('CentrosCostos', [
            'foreignKey' => 'idcentros_costos',
            'bindingKey' => 'centros_costos_idcentros_costos', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('MetodCostos', [
            'foreignKey' => 'idmetod_costos',
            'bindingKey' => 'metod_costos_idmetod_costos', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Users', [
            'foreignKey' => 'idusers',
            'bindingKey' => 'users_idusers', //actual
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
            ->integer('idcostos_maquinas')
            ->allowEmptyString('idcostos_maquinas', null, 'create')
            ->add('idcostos_maquinas', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->numeric('val_adq')
            ->allowEmptyString('val_adq');

        $validator
            ->numeric('val_neum')
            ->allowEmptyString('val_neum');

        $validator
            ->numeric('vida_util')
            ->allowEmptyString('vida_util');

        $validator
            ->numeric('vida_util_neum')
            ->allowEmptyString('vida_util_neum');

        $validator
            ->numeric('horas_total_uso')
            ->allowEmptyString('horas_total_uso');

        $validator
            ->numeric('horas_efec_uso')
            ->allowEmptyString('horas_efec_uso');

        $validator
            ->numeric('horas_mens_uso')
            ->allowEmptyString('horas_mens_uso');

        $validator
            ->numeric('horas_dia_uso')
            ->allowEmptyString('horas_dia_uso');

        $validator
            ->numeric('tasa_int_simple')
            ->allowEmptyString('tasa_int_simple');

        $validator
            ->numeric('factor_cor')
            ->allowEmptyString('factor_cor');

        $validator
            ->numeric('coef_err_mec')
            ->allowEmptyString('coef_err_mec');

        $validator
            ->numeric('consumo')
            ->allowEmptyString('consumo');

        $validator
            ->numeric('lubricante')
            ->allowEmptyString('lubricante');

        $validator
            ->numeric('costo_alquiler')
            ->allowEmptyString('costo_alquiler');

        $validator
            ->integer('maquinas_idmaquinas')
            ->requirePresence('maquinas_idmaquinas', 'create')
            ->notEmptyString('maquinas_idmaquinas');

        $validator
            ->date('finished')
            ->allowEmptyDate('finished');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmptyString('active');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        $validator
            ->integer('worksgroups_idworksgroups')
            ->requirePresence('worksgroups_idworksgroups', 'create')
            ->notEmptyString('worksgroups_idworksgroups');

        $validator
            ->integer('centros_costos_idcentros_costos')
            ->requirePresence('centros_costos_idcentros_costos', 'create')
            ->notEmptyString('centros_costos_idcentros_costos');

        $validator
            ->integer('metod_costos_idmetod_costos')
            ->requirePresence('metod_costos_idmetod_costos', 'create')
            ->notEmptyString('metod_costos_idmetod_costos');

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
        $rules->add($rules->isUnique(['idcostos_maquinas']));

        return $rules;
    }
}
