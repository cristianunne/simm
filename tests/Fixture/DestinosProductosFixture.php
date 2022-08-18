<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DestinosProductosFixture
 */
class DestinosProductosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'iddestinos_productos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'destinos_iddestinos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'productos_idproductos' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'precio' => ['type' => 'decimal', 'length' => 10, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'created' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'finished' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '1', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_destinos_productos_destinos1_idx' => ['type' => 'index', 'columns' => ['destinos_iddestinos'], 'length' => []],
            'fk_destinos_productos_productos1_idx' => ['type' => 'index', 'columns' => ['productos_idproductos'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['iddestinos_productos'], 'length' => []],
            'fk_destinos_productos_destinos1' => ['type' => 'foreign', 'columns' => ['destinos_iddestinos'], 'references' => ['destinos', 'iddestinos'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_destinos_productos_productos1' => ['type' => 'foreign', 'columns' => ['productos_idproductos'], 'references' => ['productos', 'idproductos'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'iddestinos_productos' => 1,
                'destinos_iddestinos' => 1,
                'productos_idproductos' => 1,
                'precio' => 1.5,
                'created' => '2022-07-11',
                'finished' => '2022-07-11',
                'active' => 1,
            ],
        ];
        parent::init();
    }
}
