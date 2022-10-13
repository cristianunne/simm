<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RemitosFixture
 */
class RemitosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idremitos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'remito_number' => ['type' => 'biginteger', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'hash_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'worksgroups_idworksgroups' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parcelas_idparcelas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'propietarios_idpropietarios' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'productos_idproductos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'precio_ton' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'empresas_idempresas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'destinos_iddestinos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ton' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'fk_remitos_worksgroups1_idx' => ['type' => 'index', 'columns' => ['worksgroups_idworksgroups'], 'length' => []],
            'fk_remitos_parcelas1_idx' => ['type' => 'index', 'columns' => ['parcelas_idparcelas'], 'length' => []],
            'fk_remitos_propietarios1_idx' => ['type' => 'index', 'columns' => ['propietarios_idpropietarios'], 'length' => []],
            'fk_remitos_productos1_idx' => ['type' => 'index', 'columns' => ['productos_idproductos'], 'length' => []],
            'fk_remitos_users1_idx' => ['type' => 'index', 'columns' => ['users_idusers'], 'length' => []],
            'fk_remitos_empresas1_idx' => ['type' => 'index', 'columns' => ['empresas_idempresas'], 'length' => []],
            'fk_remitos_destinos1_idx' => ['type' => 'index', 'columns' => ['destinos_iddestinos'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idremitos'], 'length' => []],
            'fk_remitos_destinos1' => ['type' => 'foreign', 'columns' => ['destinos_iddestinos'], 'references' => ['destinos', 'iddestinos'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_empresas1' => ['type' => 'foreign', 'columns' => ['empresas_idempresas'], 'references' => ['empresas', 'idempresas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_parcelas1' => ['type' => 'foreign', 'columns' => ['parcelas_idparcelas'], 'references' => ['parcelas', 'idparcelas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_productos1' => ['type' => 'foreign', 'columns' => ['productos_idproductos'], 'references' => ['productos', 'idproductos'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_propietarios1' => ['type' => 'foreign', 'columns' => ['propietarios_idpropietarios'], 'references' => ['propietarios', 'idpropietarios'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_users1' => ['type' => 'foreign', 'columns' => ['users_idusers'], 'references' => ['users', 'idusers'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_worksgroups1' => ['type' => 'foreign', 'columns' => ['worksgroups_idworksgroups'], 'references' => ['worksgroups', 'idworksgroups'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_0900_ai_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'idremitos' => 1,
                'remito_number' => 1,
                'hash_id' => 'Lorem ipsum dolor sit amet',
                'fecha' => '2022-10-03',
                'worksgroups_idworksgroups' => 1,
                'parcelas_idparcelas' => 1,
                'propietarios_idpropietarios' => 1,
                'productos_idproductos' => 1,
                'precio_ton' => 1,
                'users_idusers' => 1,
                'empresas_idempresas' => 1,
                'created' => 1664770768,
                'modified' => 1664770768,
                'active' => 1,
                'destinos_iddestinos' => 1,
                'ton' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
