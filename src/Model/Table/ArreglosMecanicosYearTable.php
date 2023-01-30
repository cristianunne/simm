<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ArreglosMecanicosYear Model
 *
 * @method \App\Model\Entity\ArreglosMecanicosYear get($primaryKey, $options = [])
 * @method \App\Model\Entity\ArreglosMecanicosYear newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanicosYear[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanicosYear|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArreglosMecanicosYear saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArreglosMecanicosYear patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanicosYear[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ArreglosMecanicosYear findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArreglosMecanicosYearTable extends Table
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

        $this->setTable('arreglos_mecanicos_year');

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
            ->notEmptyString('idarreglos_mecanicos');

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
            ->decimal('mano_obra')
            ->allowEmptyString('mano_obra');

        $validator
            ->decimal('repuestos')
            ->allowEmptyString('repuestos');

        $validator
            ->decimal('total')
            ->allowEmptyString('total');

        $validator
            ->integer('worksgroups_idworksgroups')
            ->requirePresence('worksgroups_idworksgroups', 'create')
            ->notEmptyString('worksgroups_idworksgroups');

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
}
