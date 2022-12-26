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
                    <?php echo $this->Html->image('tractor_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Datos Teóricos de Máquinas
                </h3>
            </div>


            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Datos Teóricos', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Maquinas', 'action' => 'addCostos', $id], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>
                <br>
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Ver Todos los Registros', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                        ['controller' => 'Maquinas', 'action' => 'viewAllCostosMaquinas', $id], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col" class="actions"><?= __('Ver') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Marca') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Grupo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Centro de Costos') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Met. de Costos') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Actualizar') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>
                        <th scope="col" class="actions"><?= __('Ver Histórico') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($maquinas_costos as $maq): ?>
                        <tr>
                            <td class="actions" style="text-align: center">
                                <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                   ['class' => 'btn bg-navy', 'escape' => false, 'onClick' => 'getCostosMaquinaById(this)',
                                        'attr2' => $maq->idcostos_maquinas, 'attr' => 'modal_view_costos_maq']) ?>
                            </td>
                            <td class="dt-center"><?= h($maq->maquina->name) ?></td>
                            <td class="dt-center"><?= h($maq->maquina->marca) ?></td>

                            <td class="dt-center"><?= h($maq->worksgroup->name) ?></td>

                            <td class="dt-center"><?= h($maq->centros_costo->name) ?></td>

                            <!-- Uso un for para la metodologia de costos-->
                            <?php foreach ($met_costos_tabla as $met): ?>

                                <?php if($met->id_hash == $maq->metod_costos_hashmetod_costos):  ?>
                                    <td class="dt-center"><?= h($met->name) ?></td>
                                <?php else: ?>
                                    <td class="dt-center"></td>
                                <?php endif;?>

                            <?php endforeach; ?>

                            <td class="dt-center"><?= h($maq->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($maq->user->lastname . ' ' . $maq->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-sync', 'aria-hidden' => 'true']),
                                    ['action' => 'updateCostos', $maq->idcostos_maquinas, $maq->maquina->idmaquinas], ['class' => 'btn bg-yellow', 'escape' => false]) ?>
                            </td>


                            <td class="actions" style="text-align: center">

                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'editCostosMaquina', $maq->idcostos_maquinas, $maq->maquina->idmaquinas], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'deleteCostosMaquina', $maq->idcostos_maquinas, $maq->maquina->idmaquinas],
                                        ['confirm' => __('Eliminar {0}?', 'el registro'), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php else: ?>

                                    <?php if($current_user['idusers'] == $maq->user->idusers):  ?>
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'deleteCostosMaquina', $maq->idcostos_maquinas, $maq->maquina->idmaquinas],
                                            ['confirm' => __('Eliminar {0}?', 'el registro'), 'class' => 'btn btn-danger','escape' => false]) ?>

                                    <?php endif;?>
                                <?php endif;?>
                            </td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['action' => 'viewCostosMaquinaHistory', $maq->hash_id, $id], ['class' => 'btn bg-lightblue', 'escape' => false]) ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                <br>
                <div class="pull-left">
                    <?= $this->Html->link("Volver", ['action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                </div>
            </div>



        </div>


        <!-- End Main content -->
        <?= $this->element('modals/modals_costos_maq_view')?>

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
