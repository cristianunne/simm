<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>

<?php if($current_user['role'] == 'admin' and $id_empresa == null):  ?>
    <?= $this->element('sidebar_admin')?>
<?php else:?>
    <?= $this->element('sidebar')?>
<?php endif;?>


<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('costos_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Metodología de Costos
                </h3>
            </div>


            <div class="card-body table-responsive">
                <?php if($current_user['role'] == 'admin'):  ?>
                    <div>
                        <?= $this->Html->link($this->Html->tag('span', ' Agregar Metodología', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                            ['controller' => 'MetodCostos', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                    </div>
                    <br>
                <?php endif;?>

                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Ver Todos', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                        ['controller' => 'MetodCostos', 'action' => 'viewAll'], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('#') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>
                        <th scope="col" class="actions"><?= __('Histórico') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($metod_costos as $met): ?>
                        <tr>
                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['action' => 'view', $met->idmetod_costos], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                            </td>
                            <td class="dt-center"><?= h($met->name) ?></td>

                            <?php if($met->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>
                            <td class="dt-center"><?= h($met->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($met->user->lastname . ' ' . $met->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">

                                <?php if($current_user['role'] == 'admin'):  ?>
                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-sync', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $met->idmetod_costos], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $met->idmetod_costos],
                                        ['confirm' => __('Eliminar {0}?', $met->name), 'class' => 'btn btn-danger','escape' => false]) ?>
                                <?php endif;?>

                            </td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['action' => 'viewHistory', '?' => ['name' => $met->name]], ['class' => 'btn bg-lightblue', 'escape' => false]) ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </br>
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

