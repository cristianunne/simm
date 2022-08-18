
<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container" style="max-width: 1500px;">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('salario_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Salarios
                </h3>
            </div>


            <div class="card-body table-responsive">
                <br>
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Ver Todos los registros', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                        ['controller' => 'OperariosMaquinas', 'action' => 'viewAll'], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('Apellido') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('DNI') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Maquina') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Salario') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($operarios_maquinas as $oper): ?>

                        <tr>
                            <td class="dt-center"><?= h($oper->operario->lastname) ?></td>
                            <td class="dt-center"><?= h($oper->operario->firstname) ?></td>
                            <td class="dt-center"><?= h($oper->operario->dni) ?></td>
                            <td class="dt-center"><?= h($oper->maquina->marca . ': ' . $oper->maquina->name) ?></td>
                            <td class="dt-center"><?= h($oper->sueldo) ?></td>


                            <?php if($oper->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>

                            <td class="dt-center"><?= h($oper->created->format('d-m-Y')) ?></td>

                            <td class="actions" style="text-align: center">
                                <?php if($oper->active == true):  ?>
                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-sync', 'aria-hidden' => 'true']),
                                        ['action' => 'updateSalary', $oper->idoperarios_maquinas, $oper->operarios_idoperarios,
                                            $oper->maquinas_idmaquinas], ['class' => 'btn bg-purple', 'escape' => false]) ?>
                                <?php endif;?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $oper->idoperarios_maquinas],
                                        ['confirm' => __('Eliminar {0}?', $oper->name), 'class' => 'btn btn-danger','escape' => false]) ?>
                                <?php endif;?>
                            </td>


                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                <br>
                <div class="pull-left">
                    <?= $this->Html->link("Volver", ['controller' => 'OperariosMaquinas', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
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
