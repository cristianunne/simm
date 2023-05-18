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
                    <?php echo $this->Html->image('informes_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Informes por Propietarios
                </h3>

            </div>


            <div class="card-body table-responsive">

                <?php if(isset($id_informe)):  ?>
                    <?= $this->Html->link($this->Html->tag('span', ' Descargar último Informe', ['class' => 'glyphicon far fa-file-excel', 'aria-hidden' => 'true']),
                        ['controller' => 'InformesResumen', 'action' => 'downloadAsExcel', $id_informe],
                        ['class' => 'btn btn-success', 'escape' => false]) ?>
                <?php endif;?>




                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('ID') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Informe') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Inicio') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Fin') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Categoria') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Destino') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Producto') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Descarga') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($informes_resumen as $inf): ?>

                        <tr>
                            <td class="dt-center"><?= h($inf->idinformes_resumen) ?></td>
                            <td class="dt-center"><?= h($inf->created) ?></td>
                            <td class="dt-center"><?= h($inf->fecha_inicio->format('d-m-Y')) ?></td>
                            <td class="dt-center"><?= h($inf->fecha_fin->format('d-m-Y')) ?></td>
                            <td class="dt-center"><?= h($inf->categoria) ?></td>
                            <td class="dt-center"><?= h($inf->clasificador) ?></td>
                            <td class="dt-center"><?= h($inf->producto) ?></td>

                            <?php if(!empty($inf->path)):  ?>

                                <td class="dt-center">
                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'glyphicon far fa-file-excel', 'aria-hidden' => 'true']),
                                        ['controller' => 'InformesResumen', 'action' => 'downloadAsExcel', $inf->idinformes_resumen],
                                        ['class' => 'btn btn-success', 'escape' => false]) ?>
                                </td>
                            <?php else:?>
                                <td class="dt-center"></td>
                            <?php endif;?>


                            <td class="actions" style="text-align: center;">

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $inf->idinformes_resumen],
                                        ['confirm' => __('Eliminar {0}?', $inf->idinformes_resumen), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php endif;?>


                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="form-group" style="margin-top: 20px; margin-left: 20px;">

                <div class="pull-left">
                    <?= $this->Html->link("Volver", ['controller' => 'InformesResumen', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
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
