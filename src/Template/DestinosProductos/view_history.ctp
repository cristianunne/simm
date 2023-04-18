<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>
        <div class="row">
            <div class="col-md-7" style="margin: 0 auto;">
                <!-- Main content -->

                <div class="card color-palette-box">

                    <div class="card-header bg-navy">
                        <h3 class="card-title">
                            <?php echo $this->Html->image('precio_destino_navy.png' , ["alt" => 'User Image' ,
                                "class" => 'img img-header-panel', 'pathPrefix' => '/webroot/img/icons/']) ?>
                            Ver Precio por Destino
                        </h3>
                    </div>

                    <div class="card-body table-responsive">

                        <table id="tabladata" class="table table-bordered table-hover dataTable">
                            <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('Producto') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Precio') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Fecha de Creación') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Fecha de Cierre') ?></th>
                                <th scope="col" class="actions"><?= __('Actualizar') ?></th>
                                <th scope="col" class="actions"><?= __('Acciones') ?></th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($destinos_productos as $destino): ?>
                                <tr>
                                    <td class="dt-center"><?= h($destino->producto->name) ?></td>
                                    <td class="dt-center"><?= h($destino->precio) ?></td>

                                    <?php if($destino->active == 1):  ?>
                                        <td class="dt-center"><?= h('Si') ?></td>
                                    <?php else: ?>
                                        <td class="dt-center"><?= h('No') ?></td>
                                    <?php endif;?>



                                    <?php if(is_null($destino->created)):  ?>
                                        <td class="actions" style="text-align: center">
                                        </td>
                                    <?php else: ?>
                                        <td class="dt-center"><?= h($destino->created->format('d-m-Y')) ?></td>
                                    <?php endif;?>


                                    <?php if(is_null($destino->finished)):  ?>
                                        <td class="actions" style="text-align: center">
                                        </td>
                                    <?php else: ?>
                                        <td class="dt-center"><?= h($destino->finished->format('d-m-Y')) ?></td>
                                    <?php endif;?>

                                    <?php if($destino->active == 1):  ?>
                                        <td class="actions" style="text-align: center">
                                            <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-sync', 'aria-hidden' => 'true']),
                                                ['action' => 'updatePrice', $destino->iddestinos_productos], ['class' => 'btn bg-purple', 'escape' => false]) ?>
                                        </td>
                                    <?php else: ?>
                                        <td class="dt-center"></td>
                                    <?php endif;?>


                                    <td class="actions" style="text-align: center">
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                                ['action' => 'delete', $destino->iddestinos_productos, $destino->destinos_iddestinos, $destino->productos_idproductos],
                                                ['confirm' => __('Eliminar {0}?', ($destino->producto->name . ': ' .$destino->precio)),
                                                    'class' => 'btn btn-danger','escape' => false]) ?>

                                    </td>

                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <br>
                        <div class="pull-left">
                            <?= $this->Html->link("Volver", ['action' => 'viewPricesByDestino', $id_destino], ['class' => 'btn btn-danger btn-flat']) ?>
                        </div>
                    </div>
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
