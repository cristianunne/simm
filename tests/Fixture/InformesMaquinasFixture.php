<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InformesMaquinasFixture
 */
class InformesMaquinasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idinformes_maquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'fecha_inicio' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_fin' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'empresas_idempresas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'path' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'maquinas_idmaquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'lote' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'parcela' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'propietario' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'destino' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'fk_informes_maquinas_1_idx' => ['type' => 'index', 'columns' => ['users_idusers'], 'length' => []],
            'fk_informes_maquinas_2_idx' => ['type' => 'index', 'columns' => ['empresas_idempresas'], 'length' => []],
            'fk_informes_maquinas_3_idx' => ['type' => 'index', 'columns' => ['maquinas_idmaquinas'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idinformes_maquinas'], 'length' => []],
            'fk_informes_maquinas_1' => ['type' => 'foreign', 'columns' => ['users_idusers'], 'references' => ['users', 'idusers'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_informes_maquinas_2' => ['type' => 'foreign', 'columns' => ['empresas_idempresas'], 'references' => ['empresas', 'idempresas'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
            'fk_informes_maquinas_3' => ['type' => 'foreign', 'columns' => ['maquinas_idmaquinas'], 'references' => ['maquinas', 'idmaquinas'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
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
                'idinformes_maquinas' => 1,
                'fecha_inicio' => 'Lorem ip',
                'fecha_fin' => 'Lorem ip',
                'users_idusers' => 1,
                'empresas_idempresas' => 1,
                'created' => 1682460377,
                'modified' => 1682460377,
                'name' => 'Lorem ipsum dolor sit amet',
                'path' => 'Lorem ipsum dolor sit amet',
                'maquinas_idmaquinas' => 1,
                'lote' => 'Lorem ipsum dolor sit amet',
                'parcela' => 'Lorem ipsum dolor sit amet',
                'propietario' => 'Lorem ipsum dolor sit amet',
                'destino' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
