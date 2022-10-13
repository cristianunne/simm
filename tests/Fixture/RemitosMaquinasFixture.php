<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RemitosMaquinasFixture
 */
class RemitosMaquinasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idremitos_maquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'remitos_idremitos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'alquiler_ton' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'operarios_idoperarios' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'maquinas_idmaquinas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_remitos_maquinas_remitos1_idx' => ['type' => 'index', 'columns' => ['remitos_idremitos'], 'length' => []],
            'fk_remitos_maquinas_operarios1_idx' => ['type' => 'index', 'columns' => ['operarios_idoperarios'], 'length' => []],
            'fk_remitos_maquinas_maquinas1_idx' => ['type' => 'index', 'columns' => ['maquinas_idmaquinas'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idremitos_maquinas'], 'length' => []],
            'fk_remitos_maquinas_maquinas1' => ['type' => 'foreign', 'columns' => ['maquinas_idmaquinas'], 'references' => ['maquinas', 'idmaquinas'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_maquinas_operarios1' => ['type' => 'foreign', 'columns' => ['operarios_idoperarios'], 'references' => ['operarios', 'idoperarios'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_remitos_maquinas_remitos1' => ['type' => 'foreign', 'columns' => ['remitos_idremitos'], 'references' => ['remitos', 'idremitos'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'idremitos_maquinas' => 1,
                'remitos_idremitos' => 1,
                'alquiler_ton' => 1,
                'operarios_idoperarios' => 1,
                'maquinas_idmaquinas' => 1,
            ],
        ];
        parent::init();
    }
}
