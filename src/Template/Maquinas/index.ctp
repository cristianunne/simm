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
                    <?php echo $this->Html->image('tractor_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Máquinas
                </h3>
            </div>


            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Máquina', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Maquinas', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>
                <br>
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Ver Inactivos', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                        ['controller' => 'Maquinas', 'action' => 'showInactive'], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Marca') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Propia?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Datos Teóricos') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($maquinas as $maq): ?>
                        <tr>

                            <td class="dt-center"><?= h($maq->name) ?></td>
                            <td class="dt-center"><?= h($maq->marca) ?></td>

                            <?php if($maq->propia == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>

                            <?php if($maq->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>
                            <td class="dt-center"><?= h($maq->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($maq->user->lastname . ' ' . $maq->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-dollar-sign', 'aria-hidden' => 'true']),
                                    ['controller' => 'Maquinas' , 'action' => 'indexCostos', $maq->idmaquinas], ['class' => 'btn bg-warning', 'escape' => false]) ?>
                            </td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $maq->idmaquinas], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $maq->idmaquinas],
                                        ['confirm' => __('Eliminar {0}?', $maq->name), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php else: ?>

                                    <?php if($current_user['idusers'] == $maq->user->idusers):  ?>
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'delete', $maq->idmaquinas],
                                            ['confirm' => __('Eliminar {0}?', $maq->name), 'class' => 'btn btn-danger','escape' => false]) ?>

                                    <?php endif;?>
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
    })
</script>
