<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ListaConstantesFilter Model
 *
 * @method \App\Model\Entity\ListaConstantesFilter get($primaryKey, $options = [])
 * @method \App\Model\Entity\ListaConstantesFilter newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ListaConstantesFilter[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ListaConstantesFilter|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ListaConstantesFilter saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ListaConstantesFilter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ListaConstantesFilter[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ListaConstantesFilter findOrCreate($search, callable $callback = null, $options = [])
 */
class ListaConstantesFilterTable extends Table
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

        $this->setTable('lista_constantes_filter');
        $this->setDisplayField('name');
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
            ->scalar('name')
            ->maxLength('name', 45)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        return $validator;
    }
}
