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
                                <th scope="col" class="actions"><?= __('Actualizar') ?></th>
                                <th scope="col" class="actions"><?= __('Histórico') ?></th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($destinos_productos as $destino): ?>
                                <tr>
                                    <td class="dt-center"><?= h($destino->producto->name) ?></td>
                                    <td class="dt-center"><?= h($destino->precio) ?></td>
                                    <td class="actions" style="text-align: center">
                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-sync', 'aria-hidden' => 'true']),
                                        ['action' => 'updatePrice', $destino->iddestinos_productos], ['class' => 'btn bg-purple', 'escape' => false]) ?>
                                    </td>

                                    <td class="actions" style="text-align: center">
                                        <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                            ['action' => 'viewHistory', $destino->destinos_iddestinos, $destino->productos_idproductos],
                                            ['class' => 'btn bg-navy', 'escape' => false]) ?>

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
