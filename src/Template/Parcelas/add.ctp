<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

    <div class="content-wrapper">
        <div class="container">

            <!-- Main content -->
            <div class="card color-palette-box">

                <?= $this->Flash->render() ?>

                <div class="card-header bg-navy">
                    <h3 class="card-title">
                        <?php echo $this->Html->image('parcela_navy.png' , ["alt" => 'User Image' ,
                            "class" => 'img img-header-panel', 'pathPrefix' => '/webroot/img/icons/']) ?>
                         Nueva Parcela
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                            <?= $this->Form->create($parcelas, ['enctype' => 'multipart/form-data']) ?>


                            <div class="form-group" id="group-firstname">
                                <?=  $this->Form->label('Nombre/s: ') ?>
                                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'Nombre']) ?>
                            </div>

                            <div class="form-group" id="group-lastname">
                                <?=  $this->Form->label('Descripción: ') ?>
                                <?= $this->Form->textArea('description', ['class' => 'form-control']) ?>
                            </div>
                            <?=  $this->Form->label('Propietario: ') ?>
                            <div style="display: flex;">
                                <?= $this->Form->text('propietario_name', ['class' => 'form-control', 'placeholder' => 'Propietario',
                                    'disabled', 'id' => 'input_prop', 'style'=> 'margin-right: 10px;']) ?>

                                <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                    ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModal()']) ?>

                            </div>

                            <div class="form-group" style="display: none;">
                                <?= $this->Form->text('propietarios_idpropietarios', ['class' => 'form-control', 'id' => 'propietarios_idpropietarios']) ?>
                            </div>

                            <br>
                            <?=  $this->Form->label('Lotes: ') ?>
                            <div style="display: flex;">
                                <?= $this->Form->text('lotes_name', ['class' => 'form-control', 'placeholder' => 'Lotes',
                                    'disabled', 'id' => 'input_lotes', 'style'=> 'margin-right: 10px;']) ?>

                                <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                    ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalLotes()']) ?>

                            </div>

                            <div class="form-group" id="group-firstname" style="display: none;">
                                <?= $this->Form->text('lotes_idlotes', ['class' => 'form-control', 'id' => 'lotes_idlotes']) ?>
                            </div>



                            <div class="form-group" style="margin-top: 40px;">
                                <div class="pull-right">
                                    <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>
                                </div>
                                <div class="pull-left">
                                    <?= $this->Html->link("Volver", ['controller' => 'Parcelas', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                                </div>

                            </div>


                            <?= $this->Form->end() ?>
                        </br>

                    </div>
                </div>

            </div> <!-- End Main content -->


            <!-- Modal -->
            <div class="modal fade" id="modal_propietarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-navy">
                            <h4 class="modal-title" id="tittle_modal_propietarios">Selección de Propietario</h4>
                            <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_propietarios"
                                    onclick="closeModal(this)">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <?= $this->Form->control('tipo', ['options' => ['Persona' => 'Persona', 'Empresa' => 'Empresa'],
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => 'Jurisdicción',
                                    'label' => 'Tipo:', 'onChange' => 'selectTypePropietarioModal(this)']) ?>
                            </div>

                            <div id="div_tabladata_prop_1" style="display: none;">
                            <table id="tabladata" name="tabla_persona" class="table table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Apellido') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('DNI') ?></th>
                                    <th scope="col" class="actions"><?= __('Acciones') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($propietarios as $prop): ?>
                                    <?php if($prop->tipo == 'Persona'):  ?>
                                        <tr>
                                            <td class="dt-center"><?= h($prop->firstname) ?></td>
                                            <td class="dt-center"><?= h($prop->lastname) ?></td>
                                            <td class="dt-center"><?= h($prop->dni) ?></td>

                                            <td class="actions" style="text-align: center">
                                                <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                                    ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                        'attr' => $prop->firstname . ' ' .$prop->lastname, 'attr2' =>  $prop->idpropietarios,
                                                        'onclick' => 'selectProp(this)']) ?>
                                            </td>

                                        </tr>
                                    <?php endif;?>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                            </div>
                            <div id="div_tabladata_prop_2" style="display: none;">
                                <table id="tabladata_2" class="table table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th scope="col"><?= $this->Paginator->sort('Nombre Empresa') ?></th>
                                    <th scope="col" class="actions"><?= __('Acciones') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($propietarios as $prop): ?>

                                    <?php if($prop->tipo == 'Empresa'):  ?>
                                        <tr>
                                            <td class="dt-center"><?= h($prop->name) ?></td>
                                            <td class="actions" style="text-align: center">

                                                <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                                    ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                        'attr' => $prop->name, 'attr2' =>  $prop->idpropietarios, 'onclick' => 'selectProp(this)']) ?>

                                            </td>

                                        </tr>

                                    <?php endif;?>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                            </div>


                        <div class="modal-footer">
                            <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_propietarios" onclick="closeModal(this)">Salir</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


                <!-- Modal -->
            <div class="modal fade" id="modal_lotes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg " role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-navy">
                                <h4 class="modal-title" id="tittle_modal_propietarios">Selección de Lotes</h4>
                                <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_lotes"
                                        onclick="closeModal(this)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div id="div_tabladata">
                                    <table id="tabladata_3" name="tabla_persona" class="table table-bordered table-hover dataTable">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('Provincia') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('Departamento') ?></th>
                                            <th scope="col" class="actions"><?= __('Acciones') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($lotes as $lote): ?>

                                                <tr>
                                                    <td class="dt-center"><?= h($lote->name) ?></td>
                                                    <td class="dt-center"><?= h($lote->provincia) ?></td>
                                                    <td class="dt-center"><?= h($lote->departamento) ?></td>

                                                    <td class="actions" style="text-align: center">
                                                        <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                                            ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                                'attr' => $lote->name, 'attr2' =>  $lote->idlotes,
                                                                'onclick' => 'selectLote(this)']) ?>
                                                    </td>

                                                </tr>
                                        <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="modal-footer">
                                    <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_lotes"
                                            onclick="closeModal(this)">Salir</button>
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
                    "responsive": false,
                    "pageLength": 10
                });
                $('#tabladata_2').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": false,
                    "pageLength": 10
                });

           $('#tabladata_3').DataTable({
               "paging": true,
               "lengthChange": false,
               "searching": true,
               "ordering": true,
               "info": true,
               "autoWidth": false,
               "responsive": false,
               "pageLength": 10
           });
            })
    </script>
<?= $this->Html->script('simm.js') ?>
