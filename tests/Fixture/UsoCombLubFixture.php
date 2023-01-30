<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsoCombLubFixture
 */
class UsoCombLubFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'uso_comb_lub';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'iduso_comb_lub' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'categoria' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'producto' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'litros' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'uso_maquinaria_iduso_maquinaria' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'precio' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        '_indexes' => [
            'fk_uso_comb_lub_1_idx' => ['type' => 'index', 'columns' => ['uso_maquinaria_iduso_maquinaria'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['iduso_comb_lub'], 'length' => []],
            'fk_uso_comb_lub_1' => ['type' => 'foreign', 'columns' => ['uso_maquinaria_iduso_maquinaria'], 'references' => ['uso_maquinaria', 'iduso_maquinaria'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
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
                'iduso_comb_lub' => 1,
                'categoria' => 'Lorem ipsum dolor sit amet',
                'producto' => 'Lorem ipsum dolor sit amet',
                'litros' => 1,
                'uso_maquinaria_iduso_maquinaria' => 1,
                'precio' => 1,
            ],
        ];
        parent::init();
    }
}
