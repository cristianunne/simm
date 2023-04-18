<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DestinosProductos Model
 *
 * @method \App\Model\Entity\DestinosProducto get($primaryKey, $options = [])
 * @method \App\Model\Entity\DestinosProducto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DestinosProducto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DestinosProducto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DestinosProducto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DestinosProducto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DestinosProducto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DestinosProducto findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DestinosProductosTable extends Table
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

        $this->setTable('destinos_productos');
        $this->setDisplayField('iddestinos_productos');
        $this->setPrimaryKey('iddestinos_productos');

        $this->addBehavior('Timestamp');


        $this->hasOne('Destinos', [
            'foreignKey' => 'iddestinos',
            'bindingKey' => 'destinos_iddestinos', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Productos', [
            'foreignKey' => 'idproductos',
            'bindingKey' => 'productos_idproductos', //actual
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
            ->integer('iddestinos_productos')
            ->allowEmptyString('iddestinos_productos', null, 'create');

        $validator
            ->integer('destinos_iddestinos')
            ->requirePresence('destinos_iddestinos', 'create')
            ->notEmptyString('destinos_iddestinos');

        $validator
            ->integer('productos_idproductos')
            ->requirePresence('productos_idproductos', 'create')
            ->notEmptyString('productos_idproductos');

        $validator
            ->numeric('precio')
            ->allowEmptyString('precio');

        $validator
            ->date('finished')
            ->allowEmptyDate('finished');

        $validator
            ->boolean('active')
            ->allowEmptyString('active');

        return $validator;
    }
}
