<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>


<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>
        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-building"></i>
                    Empresas Habilitadas
                </h3>
            </div>

            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Empresa', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Empresas', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= h('') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Dirección') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Email') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Telefono') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Logo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($empresas_active as $empresa): ?>
                    <tr>
                        <td class="dt-center">
                            <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                ['controller' => 'Pages', 'action' => 'indexUser', $empresa->idempresas], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                        </td>
                        <td class="dt-center"><?= h($empresa->name) ?></td>
                        <td class="dt-center"><?= h($empresa->address) ?></td>
                        <td class="dt-center"><?= h($empresa->email) ?></td>
                        <td class="dt-center"><?= h($empresa->phone) ?></td>

                        <?php if($empresa->active == 1):  ?>
                            <td class="dt-center"><?= h('Si') ?></td>
                        <?php else: ?>
                            <td class="dt-center"><?= h('No') ?></td>
                        <?php endif;?>


                        <?php if($empresa->logo == ''):  ?>
                            <td class="dt-center"><?php echo $this->Html->image('logos/edificio.png', ["alt" => 'User Image' ,
                                    "class" => 'img-circle user-profile']) ?></td>
                        <?php else: ?>
                            <td class="dt-center"> <?php echo $this->Html->image($empresa->logo , ["alt" => 'User Image' ,
                                    "class" => 'img-circle user-profile', 'pathPrefix' => $empresa->folder]) ?></td>
                        <?php endif;?>

                        <td class="dt-center"><?= h($empresa->created->format('d-m-Y')) ?></td>

                        <td class="actions" style="text-align: center">
                            <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                ['action' => 'edit', $empresa->idempresas], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                            <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                ['action' => 'delete', $empresa->idempresas],
                                ['confirm' => __('Eliminar {0}?', $empresa->name), 'class' => 'btn btn-danger','escape' => false]) ?>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>



        <div class="card color-palette-box">

            <div class="card-header bg-maroon">
                <h3 class="card-title">
                    <i class="fas fa-building"></i>
                    Empresas Inhabilitadas
                </h3>
            </div>

            <div class="card-body table-responsive">

                <table id="tabladata_2" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= h('') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Dirección') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Email') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Telefono') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Logo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($empresas_dactive as $empresa): ?>
                        <tr>
                            <td class="dt-center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $empresa->idempresas], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                            </td>
                            <td class="dt-center"><?= h($empresa->name) ?></td>
                            <td class="dt-center"><?= h($empresa->address) ?></td>
                            <td class="dt-center"><?= h($empresa->email) ?></td>
                            <td class="dt-center"><?= h($empresa->phone) ?></td>

                            <?php if($empresa->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>


                            <?php if($empresa->logo == ''):  ?>
                                <td class="dt-center"><?php echo $this->Html->image('logos/edificio.png', ["alt" => 'User Image' ,
                                        "class" => 'img-circle user-profile']) ?></td>
                            <?php else: ?>
                                <td class="dt-center"> <?php echo $this->Html->image($empresa->logo , ["alt" => 'User Image' ,
                                        "class" => 'img-circle user-profile', 'pathPrefix' => $empresa->folder]) ?></td>
                            <?php endif;?>

                            <td class="dt-center"><?= h($empresa->created->format('d-m-Y')) ?></td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $empresa->idempresas], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                    ['action' => 'delete', $empresa->idempresas],
                                    ['confirm' => __('Eliminar {0}?', $empresa->name), 'class' => 'btn btn-danger','escape' => false]) ?>

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
        })
    })
</script>
