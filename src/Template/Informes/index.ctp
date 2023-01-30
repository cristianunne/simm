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
                    Informes
                </h3>
            </div>


            <div class="card-body table-responsive">

                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Operario', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Informes', 'action' => 'generateExcel'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>


                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('ID') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Grupo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($informes as $inf): ?>

                        <tr>
                            <td class="dt-center"><?= h($inf->idinformes) ?></td>
                            <td class="dt-center"><?= h($inf->grupo) ?></td>
                            <td class="dt-center"><?= h($inf->fecha) ?></td>
                            <td class="dt-center"><?= h($inf->name) ?></td>


                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $inf->idinformes], ['class' => 'btn bg-purple', 'escape' => false]) ?>


                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $inf->idinformes],
                                        ['confirm' => __('Eliminar {0}?', $inf->name), 'class' => 'btn btn-danger','escape' => false]) ?>

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
