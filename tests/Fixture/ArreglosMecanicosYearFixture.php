<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArreglosMecanicosYearFixture
 */
class ArreglosMecanicosYearFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'arreglos_mecanicos_year';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idarreglos_mecanicos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fecha' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'num_comprobante' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'concepto' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'mano_obra' => ['type' => 'decimal', 'length' => 10, 'precision' => 5, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'repuestos' => ['type' => 'decimal', 'length' => 10, 'precision' => 5, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'total' => ['type' => 'decimal', 'length' => 10, 'precision' => 5, 'unsigned' => false, 'null' => true, 'default' => '(_utf8mb4\\\'mano_obra\\\' + _utf8mb4\\\'repuestos\\\')', 'comment' => ''],
        'worksgroups_idworksgroups' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'maquinas_idmaquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parcelas_idparcelas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'empresas_idempresas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_options' => [
            'engine' => null,
            'collation' => null
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
                'mano_obra' => 1.5,
                'repuestos' => 1.5,
                'total' => 1.5,
                'worksgroups_idworksgroups' => 1,
                'maquinas_idmaquinas' => 1,
                'parcelas_idparcelas' => 1,
                'empresas_idempresas' => 1,
                'users_idusers' => 1,
                'created' => 1660885052,
                'modified' => 1660885052,
            ],
        ];
        parent::init();
    }
}
