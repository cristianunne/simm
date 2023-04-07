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
                    Datos Teóricos de Máquinas
                </h3>
            </div>


            <div class="card-body table-responsive">

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
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($maquinas_costos as $maq): ?>
                        <tr>
                            <td class="actions" style="text-align: center">
                                <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['class' => 'btn bg-navy', 'escape' => false, 'onClick' => 'getCostosMaquinaById(this)',
                                        'attr2' => $maq->idcostos_maquinas, 'attr' => 'modal_view_costos_maq', 'attr3' => 'history_costos']) ?>
                            </td>

                            <td class="dt-center"><?= h($maq->maquina->name) ?></td>
                            <td class="dt-center"><?= h($maq->maquina->marca) ?></td>

                            <td class="dt-center"><?= h($maq->worksgroup->name) ?></td>

                            <td class="dt-center"><?= h($maq->centros_costos[0]->name) ?></td>

                            <!-- Uso un for para la metodologia de costos-->
                            <?php foreach ($met_costos_tabla as $met): ?>

                                <?php if($met->id_hash == $maq->metod_costos_hashmetod_costos):  ?>
                                    <td class="dt-center"><?= h($met->name) ?></td>
                                <?php else: ?>
                                    <td class="dt-center"></td>
                                <?php endif;?>

                            <?php endforeach; ?>

                            <td class="dt-center"><?= h($maq->created->format('d-m-Y')) ?></td>


                            <?php if($maq->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>


                            <td class="dt-center"><?= h($maq->user->lastname . ' ' . $maq->user->firstname) ?></td>


                            <td class="actions" style="text-align: center">

                                <?php if($maq->active == true):  ?>
                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-sync', 'aria-hidden' => 'true']),
                                        ['action' => 'updateCostos', $maq->idcostos_maquinas, $maq->maquina->idmaquinas], ['class' => 'btn bg-purple', 'escape' => false]) ?>
                                <?php endif;?>


                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'deleteCostosMaquina', $maq->idcostos_maquinas, 0, $hash_id],
                                        ['confirm' => __('Eliminar {0}?', $maq->name), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php else: ?>

                                    <?php if($current_user['idusers'] == $maq->user->idusers):  ?>
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'delete', $maq->idcostos_maquinas, 0, $hash_id],
                                            ['confirm' => __('Eliminar {0}?', $maq->name), 'class' => 'btn btn-danger','escape' => false]) ?>

                                    <?php endif;?>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                <br>
                <div class="pull-left">
                    <?= $this->Html->link("Volver", ['action' => 'indexCostos', $id_maquina], ['class' => 'btn btn-danger btn-flat']) ?>
                </div>
            </div>
        </div>
        <!-- Main content -->

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
