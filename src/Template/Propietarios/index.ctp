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
                    <?php echo $this->Html->image('propietario_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Propietarios
                </h3>
            </div>


            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Propietario', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Propietarios', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>
                <br>
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Ver Inactivos', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                        ['controller' => 'Propietarios', 'action' => 'showInactive'], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"><?= $this->Paginator->sort('Tipo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Apellido') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('DNI') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Dirección') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Email') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Logo/Foto') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($propietarios as $prop): ?>
                        <?php if($prop->tipo == 'Persona'):  ?>
                            <tr>
                                <td class="actions" style="text-align: center">
                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                        ['action' => 'view', $prop->idpropietarios], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                                </td>


                            <td class="dt-center"><?= h($prop->tipo) ?></td>
                            <td class="dt-center"><?= h($prop->firstname) ?></td>
                            <td class="dt-center"><?= h($prop->lastname) ?></td>
                            <td class="dt-center"><?= h($prop->dni) ?></td>
                            <td class="dt-center"><?= h($prop->address) ?></td>
                            <td class="dt-center"><?= h($prop->email) ?></td>

                            <?php if($prop->logo == ''):  ?>
                                <td class="dt-center"></td>
                            <?php else: ?>
                                <td class="dt-center"> <?php echo $this->Html->image($prop->logo , ["alt" => 'User Image' ,
                                        "class" => 'img-circle user-profile', 'pathPrefix' => $prop->folder]) ?></td>
                            <?php endif;?>

                            <?php if($prop->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>

                            <td class="dt-center"><?= h($prop->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($prop->user->lastname . ' ' . $prop->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $prop->idpropietarios], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $prop->idpropietarios],
                                        ['confirm' => __('Eliminar {0}?', $prop->name . $prop->firstname), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php else: ?>

                                    <?php if($current_user['idusers'] == $prop->user->idusers):  ?>
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'delete', $prop->idpropietarios],
                                            ['confirm' => __('Eliminar {0}?', $prop->name . $prop->firstname), 'class' => 'btn btn-danger','escape' => false]) ?>
                                    <?php endif;?>
                                <?php endif;?>

                            </td>

                        </tr>
                        <?php endif;?>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="card-body table-responsive">
                <table id="tabladata_2" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"><?= $this->Paginator->sort('Tipo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Nombre Empresa') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Dirección') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Email') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Logo/Foto') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($propietarios as $prop): ?>

                        <?php if($prop->tipo == 'Empresa'):  ?>

                            <tr>
                                <td class="actions" style="text-align: center">
                                    <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                        ['action' => 'view', $prop->idpropietarios], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                                </td>

                            <td class="dt-center"><?= h($prop->tipo) ?></td>
                            <td class="dt-center"><?= h($prop->name) ?></td>
                            <td class="dt-center"><?= h($prop->address) ?></td>
                            <td class="dt-center"><?= h($prop->email) ?></td>

                            <?php if($prop->logo == ''):  ?>
                                <td class="dt-center"><?php echo $this->Html->image('logos/edificio.png', ["alt" => 'User Image' ,
                                        "class" => 'img-circle user-profile']) ?></td>
                            <?php else: ?>
                                <td class="dt-center"> <?php echo $this->Html->image($prop->logo , ["alt" => 'User Image' ,
                                        "class" => 'img-circle user-profile', 'pathPrefix' => $prop->folder]) ?></td>
                            <?php endif;?>

                            <?php if($prop->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>

                            <td class="dt-center"><?= h($prop->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($prop->user->lastname . ' ' . $prop->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $prop->idpropietarios], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $prop->idpropietarios],
                                        ['confirm' => __('Eliminar {0}?', $prop->name . $prop->firstname), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php else: ?>

                                    <?php if($current_user['idusers'] == $prop->user->idusers):  ?>
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'delete', $prop->idpropietarios],
                                            ['confirm' => __('Eliminar {0}?', $prop->name . $prop->firstname), 'class' => 'btn btn-danger','escape' => false]) ?>
                                    <?php endif;?>
                                <?php endif;?>

                            </td>

                        </tr>

                        <?php endif;?>
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
    })
</script>
