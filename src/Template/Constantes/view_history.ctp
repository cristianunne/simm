<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('constantes_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Constantes - Histórico
                </h3>
            </div>


            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Constante', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Constantes', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Descripción') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Valor') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($constantes as $cons): ?>
                        <tr>

                            <td class="dt-center"><?= h($cons->name) ?></td>
                            <td class="dt-center"><?= h($cons->description) ?></td>
                            <td class="dt-center"><?= h($cons->value) ?></td>

                            <?php if($cons->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>
                            <td class="dt-center"><?= h($cons->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($cons->user->lastname . ' ' . $cons->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $cons->idconstantes],
                                        ['confirm' => __('Eliminar {0}?', $cons->name), 'class' => 'btn btn-danger','escape' => false]) ?>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>

                <br>
                <div class="pull-left">
                    <?= $this->Html->link("Volver", ['controller' => 'Constantes', 'action' => 'view'], ['class' => 'btn btn-danger btn-flat']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('../plugins/datatables/jquery.dataTables.min.js') ?>
<?= $this->Html->script('../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.html5.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.print.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.colVis.min.js') ?>


<script>
    $(function () {
        $('#tabladata').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        });
    })
</script>
