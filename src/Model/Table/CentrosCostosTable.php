<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Test\Fixture\ThingsFixture;
use Cake\Validation\Validator;

/**
 * CentrosCostos Model
 *
 * @method \App\Model\Entity\CentrosCosto get($primaryKey, $options = [])
 * @method \App\Model\Entity\CentrosCosto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CentrosCosto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CentrosCosto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CentrosCosto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CentrosCosto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CentrosCosto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CentrosCosto findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CentrosCostosTable extends Table
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

        $this->setTable('centros_costos');
        $this->setDisplayField('name');
        $this->setPrimaryKey('idcentros_costos');

        $this->addBehavior('Timestamp');

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
            ->integer('idcentros_costos')
            ->allowEmptyString('idcentros_costos', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('orden')
            ->maxLength('orden', 45)
            ->requirePresence('orden', 'create')
            ->notEmptyString('orden');

        $validator
            ->date('finished')
            ->allowEmptyDate('finished');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->integer('empresas_idempresas')
            ->requirePresence('empresas_idempresas', 'create')
            ->notEmptyString('empresas_idempresas');

        $validator
            ->integer('users_idusers')
            ->requirePresence('users_idusers', 'create')
            ->notEmptyString('users_idusers');

        $validator
            ->scalar('categoria')
            ->maxLength('categoria', 45)
            ->requirePresence('categoria', 'create')
            ->notEmptyString('categoria');

        return $validator;
    }


    public function findGetFletero(Query $query, $options = null)
    {

        $id_remito = $options['id_remito'];





    }
}
