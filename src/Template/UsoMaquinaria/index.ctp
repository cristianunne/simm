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
                    Uso de Maquinaria
                </h3>
            </div>

            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Nuevo Registro', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'UsoMaquinaria', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('ID') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Maquina') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Lote') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Parcela') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Horas de Trabajo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Combustibles/Lubricantes') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Modificación') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($uso_maquinas as $uso_maq): ?>
                        <tr>
                            <td class="dt-center"><?= h($uso_maq->iduso_maquinaria) ?></td>
                            <td class="dt-center"><?= h($uso_maq->maquina->name) ?></td>

                            <?php if (is_null($uso_maq->parcelas_idparcelas)): ?>
                                <td class="dt-center"><?= h('') ?></td>
                                <td class="dt-center"><?= h('') ?></td>
                            <?php else: ?>

                                <td class="actions" style="text-align: center">
                                    <?= $this->Html->link($uso_maq->parcela->lote->name,
                                        ['controller' => 'Lotes', 'action' => 'view', $uso_maq->parcela->lote->idlotes], ['escape' => false]) ?>
                                </td>
                                <td class="dt-center"><?= h($uso_maq->parcela->name) ?></td>

                            <?php endif; ?>


                            <td class="dt-center"><?= h($uso_maq->fecha->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($uso_maq->horas_trabajo) ?></td>
                            <td class="actions" style="text-align: center">

                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['action' => 'viewCombLub', $uso_maq->iduso_maquinaria], ['class' => 'btn bg-navy', 'escape' => false,
                                        'target=' => '_blank']) ?>

                            </td>

                            <td class="dt-center"><?= h($uso_maq->modified->format('d-m-Y (H:i:s)')) ?></td>

                            <td class="dt-center"><?= h($uso_maq->user->lastname . ' ' . $uso_maq->user->firstname) ?></td>
                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $uso_maq->iduso_maquinaria], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $uso_maq->iduso_maquinaria],
                                        ['confirm' => __('Eliminar {0}?', $uso_maq->iduso_maquinaria), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php else: ?>

                                    <?php if($current_user['idusers'] == $uso_maq->user->idusers):  ?>
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'delete', $uso_maq->iduso_maquinaria],
                                            ['confirm' => __('Eliminar {0}?', $uso_maq->iduso_maquinaria), 'class' => 'btn btn-danger','escape' => false]) ?>

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

        $('#tabladata_2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        });

        $('#tabladata_3').DataTable({
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
