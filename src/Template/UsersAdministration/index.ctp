<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>


<?php if(!empty($id_empresa)): ?>

    <?= $this->element('sidebar')?>

<?php else:?>

    <?php if($current_user['role'] == 'admin'):  ?>
        <?= $this->element('sidebar_admin')?>
    <?php else:?>
        <?= $this->element('sidebar')?>
    <?php endif;?>

<?php endif; ?>

<div class="content-wrapper">
    <div class="container">

        <!-- Main content -->
        <div class="card color-palette-box">

            <?= $this->Flash->render() ?>

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-user"></i>
                    Usuarios Registrados
                </h3>
            </div>

            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Agregar Usuario', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'UsersAdministration', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Apellido') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Rol') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('¿Activo?') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Foto') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha de Alta') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Empresa') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>
                        <th scope="col" class="actions"><?= __('Reset Password') ?></th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="dt-center"><?= h($user->firstname) ?></td>
                        <td class="dt-center"><?= h($user->lastname) ?></td>

                        <?php if($user->role == 'admin'):  ?>
                            <td class="dt-center" style="color: navy;"><?= h('Administrador') ?></td>

                        <?php elseif ($user->role == 'supervisor'): ?>
                            <td class="dt-center" style="color: darkslategrey;"><?= h('Supervisor') ?></td>

                        <?php else: ?>
                            <td class="dt-center" style="color: #a9a901; font-weight: normal;"><?= h('Usuario') ?></td>
                        <?php endif;?>


                        <?php if($user->active == 1):  ?>
                            <td class="dt-center"><?= h('Si') ?></td>
                        <?php else: ?>
                            <td class="dt-center"><?= h('No') ?></td>
                        <?php endif;?>

                        <?php if($user->photo == ''):  ?>
                            <td class="dt-center"><?php echo $this->Html->image('user.png', ["alt" => 'User Image' ,
                                "class" => 'img-circle user-profile']) ?></td>
                        <?php else: ?>
                            <td class="dt-center"> <?php echo $this->Html->image($user->photo , ["alt" => 'User Image' ,
                                "class" => 'img-circle user-profile', 'pathPrefix' => $user->folder]) ?></td>
                        <?php endif;?>

                        <td class="dt-center"><?= h($user->created->format('d-m-Y')) ?></td>

                        <td><?= h($user->empresas['name']) ?></td>


                        <td class="actions" style="text-align: center">
                            <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                ['controller' => 'UsersAdministration', 'action' => 'edit', $user->idusers], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                            <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                ['action' => 'delete', $user->idusers],
                                ['confirm' => __('Eliminar {0}?', $user->idusers), 'class' => 'btn btn-danger','escape' => false]) ?>

                        </td>

                        <td class="actions" style="text-align: center">

                            <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-key', 'aria-hidden' => 'true'])),
                                ['action' => 'resetPassword', $user->idusers],
                                ['confirm' => __('Resetear password de {0}?', $user->lastname), 'class' => 'btn btn-danger','escape' => false]) ?>

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
        })
    })
</script>



