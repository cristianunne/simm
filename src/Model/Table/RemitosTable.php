<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Files\ImportStatementSniff;

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
class RemitosTable extends Table
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

        $this->hasOne('Lotes', [
            'foreignKey' => 'idlotes',
            'bindingKey' => 'lotes_idlotes', //actual
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Parcelas', [
            'foreignKey' => 'idparcelas',
            'bindingKey' => 'parcelas_idparcelas', //actual
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

        /*$this->belongsToMany('Maquinas', [
            'foreignKey' => 'remitos_idremitos',
            'bindingKey' => 'idremitos', //actual
            'targetForeignKey' => 'maquinas_idmaquinas',
            'joinTable' => 'RemitosMaquinas',
            'joinType' => 'INNER'
        ]);*/
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
            ->allowEmptyString('parcelas_idparcelas');

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
            ->numeric('ton')
            ->allowEmptyString('ton');

        $validator
            ->integer('lotes_idlotes')
            ->requirePresence('lotes_idlotes', 'create')
            ->notEmptyString('lotes_idlotes');

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

    public function findRemitosByConditions(Query $query, $options)
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

        if(isset($options['productos_idproductos'])){
            if($options['productos_idproductos'] != 0 && $options['productos_idproductos'] != null){
                $conditions['productos_idproductos'] = $options['productos_idproductos'];
            }
        }
        $conditions['empresas_idempresas'] = $options['empresas_idempresas'];


        if($conditions['empresas_idempresas'] == null){
            return false;
        }


        $date_start = $options['fecha_inicio'];
        $date_end = $options['fecha_fin'];

        $conditions['fecha >='] = $date_start;
        $conditions['fecha <='] = $date_end;

        $result = $query->where($conditions);

        $array_result = [];
        foreach ($result as $rem){

            $array_result[] = $rem->idremitos;
        }

        return $array_result;
    }

    public function findRemitosByConditionsQuery(Query $query, $options)
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

        $result = $query
            ->where($conditions);


        return $result;
    }

    public function findRemitosByConditionsQueryMaquina(Query $query, $options)
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
                $conditions['lotes_idlotes'] = $options['lotes_idlotes'];
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


        //$conditions['empresas_idempresas'] = $options['empresas_idempresas'];


        $conditions['MONTH(fecha) ='] = $options['mes'];
        $conditions['YEAR(fecha) ='] = $options['year'];

        $conditions['maquinas_idmaquinas'] = $options['maquina'];




        $result = $query
            ->distinct(['idremitos'])
            ->innerJoinWith('RemitosMaquinas')
            ->where($conditions);


        return $result;
    }

    public function findRemitosByConditionsQueryMaquinaTransporte(Query $query, $options)
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
                $conditions['lotes_idlotes'] = $options['lotes_idlotes'];
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


        //$conditions['empresas_idempresas'] = $options['empresas_idempresas'];


        $date_start = $options['fecha_inicio'];
        $date_end = $options['fecha_fin'];

        $conditions['fecha >='] = $date_start;
        $conditions['fecha <='] = $date_end;

        $conditions['maquinas_idmaquinas'] = $options['maquina'];




        $result = $query
            ->distinct(['idremitos'])
            ->innerJoinWith('RemitosMaquinas')
            ->where($conditions);


        $array_result = [];

        foreach ($result as $rem){

            $array_result[] = $rem->idremitos;
        }

        return $array_result;

    }




    public function findDestinosByRemitos(Query $query, $remitos)
    {



        $result = $query->select(['destinos_iddestinos'])
            ->distinct(['destinos_iddestinos'])
            ->where(['idremitos IN' => $remitos]);

        $array_result = [];

        foreach ($result as $rem){

            $array_result[] = $rem->destinos_iddestinos;
        }

        return $array_result;
    }

    public function findPropietariosByRemitos(Query $query, $remitos)
    {
        //Cuando en las condiciones viene el 0, significa que tiene que traer todos



        $result = $query->select(['propietarios_idpropietarios'])
            ->distinct(['propietarios_idpropietarios'])
            ->where(['idremitos IN' => $remitos]);

        $array_result = [];

        foreach ($result as $rem){

            $array_result[] = $rem->propietarios_idpropietarios;
        }

        return $array_result;
    }

    public function findGetProductosDistinctByRemitos(Query $query, $array_remitos)
    {
        $result = $query->select(['productos_idproductos'])
            ->distinct(['productos_idproductos'])
            ->where(['idremitos IN ' => $array_remitos]);

        $array_result = [];

        foreach ($result as $rem){
            $array_result[$rem->productos_idproductos] = $rem->productos_idproductos;
        }

        return $array_result;
    }

    public function findGetLotesDistinctByRemitos(Query $query, $array_remitos)
    {
        $result = $query->select(['lotes_idlotes'])
            ->distinct(['lotes_idlotes'])
            ->where(['idremitos IN ' => $array_remitos]);

        $array_result = [];

        foreach ($result as $rem){
            $array_result[$rem->lotes_idlotes] = $rem->lotes_idlotes;
        }

        return $array_result;
    }

    public function findGetDestinosDistinctByRemitos(Query $query, $array_remitos)
    {
        $result = $query->select(['destinos_iddestinos'])
            ->distinct(['destinos_iddestinos'])
            ->where(['idremitos IN ' => $array_remitos]);

        $array_result = [];

        foreach ($result as $rem){
            $array_result[$rem->destinos_iddestinos] = $rem->destinos_iddestinos;
        }

        return $array_result;
    }


    public function findGetParcelasDistinctByRemitos(Query $query, $array_remitos)
    {
        $result = $query->select(['parcelas_idparcelas'])
            ->distinct(['parcelas_idparcelas'])
            ->where(['idremitos IN ' => $array_remitos]);

        $array_result = [];

        foreach ($result as $rem){
            $array_result[] = $rem->parcelas_idparcelas;
        }

        return $array_result;
    }

    public function findRemitosByConditionsAllData(Query $query, $options)
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


        $date_start = $options['fecha_inicio'];
        $date_end = $options['fecha_fin'];


        $conditions['fecha >='] = $date_start;
        $conditions['fecha <='] = $date_end;



        $result = $query->where($conditions);


        return $result;
    }


    /** Utilizo el metodo para devolver un arreglo de los remitos ***/
    public function findRemitosByDate(Query $query, $options = [])
    {

        $array_result = [];

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

    public function findRemitosByRemitos(Query $query, $remitos = [])
    {

        //Aca puedo traer anidado los datos

        $result = $query ->contain([
            'RemitosMaquinas' => ['Maquinas' => ['CostosMaquinas' => ['CentrosCostos']]]
        ])
            ->where([
            'Remitos.idremitos IN' => $remitos
        ]);

        return $result;

    }

    public function findGetTotalToneladas(Query $query, $remitos = [])
    {

        $result = $query
            ->select(['sum' => $query->func()->sum('ton')])
            ->where([
                'Remitos.idremitos IN' => $remitos
            ]);

        $toneladas = $result->toArray()[0]['sum'];

        return $toneladas;

    }

    public function findGetTotalToneladasByProductos(Query $query, $remitos = null)
    {

        $result = $query
            ->select(['productos_idproductos', 'sum' => $query->func()->sum('ton')])
            ->where([
                'Remitos.idremitos IN' => $remitos
            ])->group('productos_idproductos');

        return $result;

    }

    public function findGetWorksGroupDistinct(Query $query, $options = null)
    {

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


        $conditions["DATE_FORMAT(fecha,'%Y-%m') >="] = $options['fecha_inicio'];
        $conditions["DATE_FORMAT(fecha,'%Y-%m') <="] = $options['fecha_fin'];

        $result = $query->select(['worksgroups_idworksgroups'])
            ->where($conditions)->distinct(['worksgroups_idworksgroups']);


        return $result;

    }

    public function findGetSumaToneladasByWorksgroup(Query $query, $options = null)
    {

        $conditions = [];


        $conditions['worksgroups_idworksgroups ='] = $options['worksgroup'];
        $conditions['MONTH(fecha) ='] = $options['mes'];
        $conditions['YEAR(fecha) ='] = $options['year'];

        $result = $query->select(['worksgroups_idworksgroups',
            'toneladas' => $query->func()->sum('ton')])
            ->where($conditions)->distinct(['worksgroups_idworksgroups']);

        return $result;

    }

    public function findGetSumaToneladasByMaquina(Query $query, $options = null)
    {

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


        if(isset($options['destinos_iddestinos'])){
            if($options['destinos_iddestinos'] != 0 && $options['destinos_iddestinos'] != null){
                $conditions['destinos_iddestinos'] = $options['destinos_iddestinos'];
            }
        }


        $conditions['MONTH(fecha) ='] = $options['mes'];
        $conditions['YEAR(fecha) ='] = $options['year'];

        //$conditions['RemitosMaquinas.maquinas_idmaquinas'] = $options['maquina'];

        debug($conditions);

        $result = $query ->innerJoinWith('RemitosMaquinas')->where($conditions);


        /*$result = $query->select(['RemitosMaquinas.maquinas_idmaquinas',
            'toneladas' => $query->func()->sum('ton')])
            ->innerJoinWith('RemitosMaquinas')
            ->where($conditions)->distinct(['RemitosMaquinas.maquinas_idmaquinas']);*/

        //debug($result->toArray());

        return $result;

    }


    public function findGetRemitosByConditionsByMaquinaTransporte(Query $query, $options = null)
    {

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

        if(isset($options['productos_idproductos'])){
            if($options['productos_idproductos'] != 0 && $options['productos_idproductos'] != null){
                $conditions['productos_idproductos'] = $options['productos_idproductos'];
            }
        }
        $conditions['Remitos.empresas_idempresas'] = $options['empresas_idempresas'];




        if($conditions['Remitos.empresas_idempresas'] == null){
            return false;
        }


        $date_start = $options['fecha_inicio'];
        $date_end = $options['fecha_fin'];

        $conditions['fecha >='] = $date_start;
        $conditions['fecha <='] = $date_end;




        $result = $query
            ->innerJoinWith('RemitosMaquinas', function (Query $q2){
                return $q2->innerJoinWith('Maquinas', function (Query $q3){
                    return $q3->innerJoinWith('CostosMaquinas', function (Query $q4){

                        return $q4->innerJoinWith('CentrosCostos', function (Query $q5){
                            return $q5->where(['CentrosCostos.categoria LIKE' => 'Transporte']);
                        });

                    });
                });
            })

            ->where($conditions);

        $array_result = [];
        foreach ($result as $rem){

            $array_result[] = $rem->idremitos;
        }


        return $array_result;

    }



}
