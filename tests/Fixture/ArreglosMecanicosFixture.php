<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArreglosMecanicosFixture
 */
class ArreglosMecanicosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idarreglos_mecanicos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'fecha' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'num_comprobante' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'concepto' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'mano_obra' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => ''],
        'repuestos' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => ''],
        'total' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => '(`mano_obra` + `repuestos`)', 'comment' => ''],
        'maquinas_idmaquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parcelas_idparcelas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'empresas_idempresas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'lotes_idlotes' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_arreglos_mecanicos_maquinas1_idx' => ['type' => 'index', 'columns' => ['maquinas_idmaquinas'], 'length' => []],
            'fk_arreglos_mecanicos_parcelas1_idx' => ['type' => 'index', 'columns' => ['parcelas_idparcelas'], 'length' => []],
            'fk_arreglos_mecanicos_empresas1_idx' => ['type' => 'index', 'columns' => ['empresas_idempresas'], 'length' => []],
            'fk_arreglos_mecanicos_users1_idx' => ['type' => 'index', 'columns' => ['users_idusers'], 'length' => []],
            'fk_arreglos_mecanicos_1_idx' => ['type' => 'index', 'columns' => ['lotes_idlotes'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idarreglos_mecanicos'], 'length' => []],
            'fk_arreglos_mecanicos_1' => ['type' => 'foreign', 'columns' => ['lotes_idlotes'], 'references' => ['lotes', 'idlotes'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_arreglos_mecanicos_empresas1' => ['type' => 'foreign', 'columns' => ['empresas_idempresas'], 'references' => ['empresas', 'idempresas'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
            'fk_arreglos_mecanicos_maquinas1' => ['type' => 'foreign', 'columns' => ['maquinas_idmaquinas'], 'references' => ['maquinas', 'idmaquinas'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
            'fk_arreglos_mecanicos_parcelas1' => ['type' => 'foreign', 'columns' => ['parcelas_idparcelas'], 'references' => ['parcelas', 'idparcelas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_arreglos_mecanicos_users1' => ['type' => 'foreign', 'columns' => ['users_idusers'], 'references' => ['users', 'idusers'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
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
                'idarreglos_mecanicos' => 1,
                'fecha' => '2023-04-20',
                'num_comprobante' => 'Lorem ipsum dolor sit amet',
                'concepto' => 'Lorem ipsum dolor sit amet',
                'mano_obra' => 1,
                'repuestos' => 1,
                'total' => 1,
                'maquinas_idmaquinas' => 1,
                'parcelas_idparcelas' => 1,
                'empresas_idempresas' => 1,
                'users_idusers' => 1,
                'created' => 1681959327,
                'modified' => 1681959327,
                'lotes_idlotes' => 1,
            ],
        ];
        parent::init();
    }
}
