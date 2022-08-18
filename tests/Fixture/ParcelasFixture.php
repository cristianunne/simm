<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ParcelasFixture
 */
class ParcelasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idparcelas' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_0900_ai_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'finished' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'lotes_idlotes' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'propietarios_idpropietarios' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'users_idusers' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_parcelas_lotes1_idx' => ['type' => 'index', 'columns' => ['lotes_idlotes'], 'length' => []],
            'fk_parcelas_propietarios1_idx' => ['type' => 'index', 'columns' => ['propietarios_idpropietarios'], 'length' => []],
            'fk_parcelas_users1_idx' => ['type' => 'index', 'columns' => ['users_idusers'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idparcelas'], 'length' => []],
            'idparcelas_UNIQUE' => ['type' => 'unique', 'columns' => ['idparcelas'], 'length' => []],
            'fk_parcelas_lotes1' => ['type' => 'foreign', 'columns' => ['lotes_idlotes'], 'references' => ['lotes', 'idlotes'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_parcelas_propietarios1' => ['type' => 'foreign', 'columns' => ['propietarios_idpropietarios'], 'references' => ['propietarios', 'idpropietarios'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_parcelas_users1' => ['type' => 'foreign', 'columns' => ['users_idusers'], 'references' => ['users', 'idusers'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'idparcelas' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-06-27',
                'finished' => '2022-06-27',
                'lotes_idlotes' => 1,
                'propietarios_idpropietarios' => 1,
                'users_idusers' => 1,
                'active' => 1,
            ],
        ];
        parent::init();
    }
}
