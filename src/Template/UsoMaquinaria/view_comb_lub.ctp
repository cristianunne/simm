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
                    Combustible y Lubricantes
                </h3>
            </div>
            <div class="card-body table-responsive">
                <div class="col-md-12">
                    <table id="tabladata" class="table table-bordered table-hover dataTable">
                        <thead>
                        <tr>

                            <th scope="col"><?= $this->Paginator->sort('N°') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('Categoria') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('Producto') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('Litros') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('Precio ($/l)') ?></th>
                            <th scope="col" class="actions"><?= __('Acciones') ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $i = 1; ?>
                        <?php foreach ($uso_comb_lub as $comb): ?>

                                <tr>
                                    <td class="dt-center"><?= h($i) ?></td>
                                    <td class="dt-center"><?= h($comb->categoria) ?></td>
                                    <td class="dt-center"><?= h($comb->producto) ?></td>
                                    <td class="dt-center"><?= h($comb->litros) ?></td>
                                    <td class="dt-center"><?= h($comb->precio) ?></td>
                                    <td class="dt-center">

                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'deleteUsoCombSimple', $comb->iduso_comb_lub, $comb->uso_maquinaria_iduso_maquinaria],
                                            ['confirm' => __('Eliminar {0}?', $comb->producto), 'class' => 'btn btn-danger','escape' => false]) ?>


                                    </td>

                                    <?php $i = $i + 1; ?>
                                </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
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


