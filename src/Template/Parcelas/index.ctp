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
                    <?php echo $this->Html->image('parcela_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Parcelas
                </h3>
            </div>


            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Parcela', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Parcelas', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>
                <br>
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Ver Inactivos', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                        ['controller' => 'Parcelas', 'action' => 'showInactive'], ['class' => 'btn btn-warning', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>

                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Descripción') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Lote') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Propietario') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($parcelas as $parc): ?>
                        <tr>
                            <td class="dt-center"><?= h($parc->name) ?></td>
                            <td class="dt-center"><?= h($parc->description) ?></td>
                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($parc->lote->name,
                                    ['controller' => 'Lotes', 'action' => 'view', $parc->lote->idlotes], ['escape' => false]) ?>
                            </td>

                            <?php if($parc->propietario->tipo == 'Persona'):  ?>

                                <td class="actions" style="text-align: center">
                                    <?= $this->Html->link($parc->propietario->firstname . ' ' . $parc->propietario->lastname,
                                    ['controller' => 'Propietarios', 'action' => 'view', $parc->propietario->idpropietarios], ['escape' => false]) ?>
                                </td>
                            <?php else: ?>
                                <td class="actions" style="text-align: center">
                                <?= $this->Html->link($parc->propietario->name,
                                    ['controller' => 'Propietarios', 'action' => 'view', $parc->propietario->idpropietarios], ['escape' => false]) ?>
                                </td>
                            <?php endif;?>

                            <?php if($parc->active == 1):  ?>
                                <td class="dt-center"><?= h('Si') ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h('No') ?></td>
                            <?php endif;?>
                            <td class="dt-center"><?= h($parc->created->format('d-m-Y')) ?></td>

                            <td class="dt-center"><?= h($parc->user->lastname . ' ' . $parc->user->firstname) ?></td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $parc->idparcelas], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $parc->idparcelas],
                                        ['confirm' => __('Eliminar {0}?', $parc->name), 'class' => 'btn btn-danger','escape' => false]) ?>

                                <?php else: ?>

                                    <?php if($current_user['idusers'] == $parc->user->idusers):  ?>
                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                            ['action' => 'delete', $parc->idparcelas],
                                            ['confirm' => __('Eliminar {0}?', $parc->name), 'class' => 'btn btn-danger','escape' => false]) ?>

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
    })
</script>

