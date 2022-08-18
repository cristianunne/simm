<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container" style="max-width: 600px;">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('tractor_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Constantes
                </h3>
            </div>


            <div class="card-body table-responsive">
                <div>
                    <?= $this->Form->button($this->Html->tag('span', ' Agregar Constante', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                        ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'attr' => 'modal_add' , 'onclick' => 'showModalAll(this)']) ?>
                </div>

                <br>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lista_constantes as $item): ?>
                        <tr>

                            <td class="dt-center"><?= h($item->name) ?></td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                    ['action' => 'delete', $item->idlista_constantes],
                                    ['confirm' => __('Eliminar {0}?', $item->name), 'class' => 'btn btn-danger','escape' => false]) ?>

                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                <br>
                <div class="pull-left">
                    <?= $this->Html->link("Volver", ['controller' => 'Constantes', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header bg-navy">
                    <h4 class="modal-title" id="tittle_modal_propietarios">Nueva Constante</h4>
                    <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_add"
                            onclick="closeModal(this)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $this->Form->create($lista_constantes_ent, []) ?>
                    <div class="form-group">
                        <?=  $this->Form->label('Nombre: ') ?>
                        <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'Nombre']) ?>
                    </div>

                    <div class="modal-footer">

                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>
                            </div>

                        </div>
                    </div>

                    <?= $this->Form->end() ?>
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
<?= $this->Html->script('simm.js') ?>
