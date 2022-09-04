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
        'num_comprobante' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'concepto' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'mano_obra' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'repuestos' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'total' => ['type' => 'decimal', 'length' => 10, 'precision' => 5, 'unsigned' => false, 'null' => true, 'default' => '(_utf8mb4\\\'mano_obra\\\' + _utf8mb4\\\'repuestos\\\')', 'comment' => ''],
        'worksgroups_idworksgroups' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'maquinas_idmaquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parcelas_idparcelas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'empresas_idempresas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_arreglos_mecanicos_worksgroups1_idx' => ['type' => 'index', 'columns' => ['worksgroups_idworksgroups'], 'length' => []],
            'fk_arreglos_mecanicos_maquinas1_idx' => ['type' => 'index', 'columns' => ['maquinas_idmaquinas'], 'length' => []],
            'fk_arreglos_mecanicos_parcelas1_idx' => ['type' => 'index', 'columns' => ['parcelas_idparcelas'], 'length' => []],
            'fk_arreglos_mecanicos_empresas1_idx' => ['type' => 'index', 'columns' => ['empresas_idempresas'], 'length' => []],
            'fk_arreglos_mecanicos_users1_idx' => ['type' => 'index', 'columns' => ['users_idusers'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idarreglos_mecanicos'], 'length' => []],
            'fk_arreglos_mecanicos_empresas1' => ['type' => 'foreign', 'columns' => ['empresas_idempresas'], 'references' => ['empresas', 'idempresas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_arreglos_mecanicos_maquinas1' => ['type' => 'foreign', 'columns' => ['maquinas_idmaquinas'], 'references' => ['maquinas', 'idmaquinas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_arreglos_mecanicos_parcelas1' => ['type' => 'foreign', 'columns' => ['parcelas_idparcelas'], 'references' => ['parcelas', 'idparcelas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_arreglos_mecanicos_users1' => ['type' => 'foreign', 'columns' => ['users_idusers'], 'references' => ['users', 'idusers'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_arreglos_mecanicos_worksgroups1' => ['type' => 'foreign', 'columns' => ['worksgroups_idworksgroups'], 'references' => ['worksgroups', 'idworksgroups'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'idarreglos_mecanicos' => 1,
                'fecha' => '2022-08-19',
                'num_comprobante' => 'Lorem ipsum dolor sit amet',
                'concepto' => 'Lorem ipsum dolor sit amet',
                'mano_obra' => 1,
                'repuestos' => 1,
                'total' => 1.5,
                'worksgroups_idworksgroups' => 1,
                'maquinas_idmaquinas' => 1,
                'parcelas_idparcelas' => 1,
                'empresas_idempresas' => 1,
                'users_idusers' => 1,
                'created' => 1660924145,
                'modified' => 1660924145,
            ],
        ];
        parent::init();
    }
}
