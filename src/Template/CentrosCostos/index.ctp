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
                    <?php echo $this->Html->image('centro_costos_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Centro de Costos
                </h3>
            </div>

            <div class="card-body table-responsive">

                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>

                    <div>
                        <?= $this->Html->link($this->Html->tag('span', ' Agregar Centro de Costo', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                            ['controller' => 'CentrosCostos', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                    </div>
                <?php endif;?>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>

                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Orden') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Categoria') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($centros_costos as $centro): ?>
                        <tr>
                            <td class="dt-center"><?= h($centro->name) ?></td>
                            <td class="dt-center"><?= h($centro->orden) ?></td>
                            <td class="dt-center"><?= h($centro->categoria) ?></td>
                            <?php if($centro->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>
                            <td class="dt-center"><?= h($centro->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($centro->user->lastname . ' ' . $centro->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>

                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                        ['action' => 'edit', $centro->idcentros_costos], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $centro->idcentros_costos],
                                        ['confirm' => __('Eliminar {0}?', $centro->name), 'class' => 'btn btn-danger','escape' => false]) ?>
                                <?php endif;?>



                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
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

        $('#tabladata_2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        })
    })
</script>

