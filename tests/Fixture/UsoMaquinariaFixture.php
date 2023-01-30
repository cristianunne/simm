<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsoMaquinariaFixture
 */
class UsoMaquinariaFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'uso_maquinaria';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'iduso_maquinaria' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'maquinas_idmaquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parcelas_idparcelas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fecha' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'horas_trabajo' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'empresas_idempresas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_uso_maquinaria_maquinas1_idx' => ['type' => 'index', 'columns' => ['maquinas_idmaquinas'], 'length' => []],
            'fk_uso_maquinaria_parcelas1_idx' => ['type' => 'index', 'columns' => ['parcelas_idparcelas'], 'length' => []],
            'fk_uso_maquinaria_users1_idx' => ['type' => 'index', 'columns' => ['users_idusers'], 'length' => []],
            'fk_uso_maquinaria_empresas1_idx' => ['type' => 'index', 'columns' => ['empresas_idempresas'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['iduso_maquinaria'], 'length' => []],
            'fk_uso_maquinaria_empresas1' => ['type' => 'foreign', 'columns' => ['empresas_idempresas'], 'references' => ['empresas', 'idempresas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_uso_maquinaria_maquinas1' => ['type' => 'foreign', 'columns' => ['maquinas_idmaquinas'], 'references' => ['maquinas', 'idmaquinas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_uso_maquinaria_parcelas1' => ['type' => 'foreign', 'columns' => ['parcelas_idparcelas'], 'references' => ['parcelas', 'idparcelas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_uso_maquinaria_users1' => ['type' => 'foreign', 'columns' => ['users_idusers'], 'references' => ['users', 'idusers'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'iduso_maquinaria' => 1,
                'maquinas_idmaquinas' => 1,
                'parcelas_idparcelas' => 1,
                'fecha' => '2023-01-25',
                'horas_trabajo' => 1,
                'users_idusers' => 1,
                'empresas_idempresas' => 1,
                'created' => 1674675311,
                'modified' => 1674675311,
            ],
        ];
        parent::init();
    }
}
