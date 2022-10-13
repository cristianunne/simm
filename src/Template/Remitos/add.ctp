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
                    <?php echo $this->Html->image('remitos_white.png' , ["alt" => 'User Image' ,
                        "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Nuevo Remito
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                        <?= $this->Form->create($remitos_entity, ['']) ?>

                        <div class="row">

                            <div class="col-md-3">

                                <div class="alert alert-default-success" role="alert">
                                        <small>El N° se genera automaticamente.</small>
                                </div>

                                <div class="form-group">
                                    <?=  $this->Form->label('N° de Remito: ') ?>
                                    <?= $this->Form->text('remito_number', ['value' => '',
                                        'class' => 'form-control', 'placeholder' => '', 'disabled', 'style' => ['text-align: right;'], 'id' => 'remito_number']) ?>
                                </div>

                            </div>
                            <hr style="width: 97%; margin-top: 25px;">

                        </div>      <!-- ROW MAIN -->


                        <div class="row">

                            <div class="col-md-3" style="border-right: solid 1px #c1c1c1;">
                                <br>

                                <hr style="margin-top: 25px;">

                                <div class="form-group" id="sandbox-container">
                                    <?=  $this->Form->label('fecha', 'Fecha: ') ?>
                                    <div class="input-append date">
                                        <input id="fecha" name="fecha" type="date" class="span2" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->control('worksgroups_idworksgroups', ['options' => $lista_worksgroups,
                                        'empty' => '(Elija un Grupo)', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Grupo de Trabajo',
                                        'label' => 'Grupo de Trabajo:', 'id' => 'worksgroups_idworksgroups']) ?>
                                </div>

                                <?=  $this->Form->label('Lotes: ') ?>

                                <div style="display: flex;">

                                        <?= $this->Form->control('lotes_idlotes', ['options' => null,
                                            'empty' => 'Elija un Lote', 'type' => 'select',
                                            'class' => 'form-control', 'placeholder' => 'Parcela',
                                            'label' => false, 'id' => 'lotes_idlotes', 'required']) ?>


                                    <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                        ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalLotes()',
                                            'style'=> 'margin-left: 10px;']) ?>

                                </div>

                                <br>


                                <div class="form-group">
                                    <?= $this->Form->control('parcelas_idparcelas', ['options' => null,
                                        'empty' => '(Elija una Parcela)', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Parcela',
                                        'label' => 'Parcela:', 'required', 'id' => 'parcela']) ?>
                                </div>



                                <?=  $this->Form->label('Propietarios: ') ?>
                                <div style="display: flex;">

                                    <?= $this->Form->control('propietarios_idpropietarios', ['options' => null,
                                        'empty' => 'Elija un Propietario', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Propietarios',
                                        'label' => false, 'id' => 'propietarios_idpropietarios', 'required']) ?>


                                    <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                        ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalAll(this)',
                                            'attr' => 'modal_propietarios',  'style'=> 'margin-left: 10px;']) ?>

                                </div>

                                <br>


                                <?=  $this->Form->label('Destinos: ') ?>
                                <div style="display: flex;">

                                    <?= $this->Form->control('destinos_iddestinos', ['options' => null,
                                        'empty' => 'Elija un Destino', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Destinos',
                                        'label' => false, 'id' => 'destinos_iddestinos', 'required']) ?>

                                    <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                        ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalAll(this)',
                                            'attr' => 'modal_destinos', 'style'=> 'margin-left: 10px;']) ?>
                                </div>

                                <br>

                                <?=  $this->Form->label('Producto: ') ?>
                                <div style="display: flex;">
                                    <?= $this->Form->control('productos_idproductos', ['options' => null,
                                        'empty' => 'Elija un Producto', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Productos',
                                        'label' => false, 'id' => 'productos_idproductos', 'required']) ?>


                                    <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                        ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalAll(this)',
                                            'attr' => 'modal_productos', 'style'=> 'margin-left: 10px;']) ?>
                                </div>

                                <br>
                                <div class="form-group">
                                    <?= $this->Form->input('precio_ton', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Precio ($/t): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('ton', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Toneladas (t): ']) ?>
                                </div>

                            </div>

                            <?php if ($is_save): ?>

                                <div class="col-md-6" style="border-right: solid 1px #c1c1c1;">
                                    <p style="display: block; text-align: center; font-weight: bold;">Maquinaria utilizada en el Remito</p>
                                    <hr style="margin-top: 25px;">
                                    <table id="tabladata" class="table table-bordered table-hover dataTable">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?= $this->Paginator->sort('N°') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('ID') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('Máquina') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('Operario') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('AlquilerTon') ?></th>
                                            <th scope="col" class="actions"><?= __('Acciones') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-3" style="border-right: solid 1px #c1c1c1;">
                                    <p style="display: block; text-align: center; font-weight: bold;">Selección de Máquinas</p>

                                    <hr style="margin-top: 25px;">

                                    <?= $this->Form->button($this->Html->tag('span', ' Máquina', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                                        ['type' => 'button', 'class' => 'btn bg-navy btn-remito-styles', 'escape' => false, 'onclick' => 'showModalMaquinas()']) ?>
                                    <br>
                                    <hr style="width: 97%; margin-top: 25px;">

                                </div>

                             <?php else: ?>

                            <div class="col-md-9" style="border-right: solid 1px #c1c1c1;">
                                <div class="alert alert-default-danger" role="alert">
                                    <h5 class="alert-heading" style="text-justify: auto;">Observación:
                                        <small>Almacene el Remito para acceder a esta sección.</small></h5>
                                </div>
                            </div>


                              <?php endif;?>


                        </div>


                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>
                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Maquinas-->
    <div class="modal fade" id="modal_maquinas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header bg-navy">
                    <h4 class="modal-title" id="tittle_modal_propietarios">Selección de Máquinas</h4>
                    <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_maquinas"
                            onclick="closeModal(this)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="div_tabladata">
                        <table id="tabladata_maq_modal" name="tabla_persona" class="table table-bordered table-hover dataTable">
                            <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('Maquina') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Operario') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Alquiler Ton') ?></th>

                                <th scope="col" class="actions"><?= __('Acciones') ?></th>
                            </tr>
                            </thead>
                            <tbody>


                            <?php foreach ($oper_maq_data as $maq): ?>

                                <tr>
                                    <td class="dt-center"><?= h($maq->maquina->marca . ': ' . $maq->maquina->name) ?></td>
                                    <td class="dt-center"><?= h($maq->operario->firstname . ' ' . $maq->operario->lastname) ?></td>
                                    <td class="dt-center"><input type="number" value="0"
                                                                 id="<?= h('input_' . $maq->idoperarios_maquinas) ?>"></input></td>

                                    <td class="actions" style="text-align: center">
                                        <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                            ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                'maquina' => $maq->maquina->marca . ': ' . $maq->maquina->name,
                                                'operario' =>  $maq->operario->firstname . ' ' . $maq->operario->lastname,
                                                'id_maq_op' => $maq->idoperarios_maquinas,
                                                'onclick' => 'loadMaquinaSelectToTable(this)']) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_maquinas"
                                onclick="closeModal(this)">Salir</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- Modal lOTES-->
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
                                                'attr' => $lote->name, 'attr2' =>  $lote->idlotes,  'id' => 'remitos_add',
                                                'onclick' => 'selectLoteRemito(this)']) ?>
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


    <!-- Modal propietarios-->
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
                        <table id="tabladata_prop_1" name="tabla_persona" class="table table-bordered table-hover dataTable">
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
                                                    'onclick' => 'selectPropRemito(this)']) ?>
                                        </td>

                                    </tr>
                                <?php endif;?>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                    <div id="div_tabladata_prop_2" style="display: none;">
                        <table id="tabladata_prop_2" class="table table-bordered table-hover dataTable">
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
                                                    'attr' => $prop->name, 'attr2' =>  $prop->idpropietarios, 'onclick' => 'selectPropRemito(this)']) ?>

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


    <!-- Modal Destinos-->
    <div class="modal fade" id="modal_destinos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header bg-navy">
                    <h4 class="modal-title" id="tittle_modal_propietarios">Selección de Destinos</h4>
                    <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_destinos"
                            onclick="closeModal(this)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="div_tabladata_destinos">
                        <table id="tabladata_destinos" name="tabla_persona" class="table table-bordered table-hover dataTable">
                            <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Dirección') ?></th>
                                <th scope="col" class="actions"><?= __('Acciones') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($destinos as $dest): ?>

                                <tr>
                                    <td class="dt-center"><?= h($dest->name) ?></td>
                                    <td class="dt-center"><?= h($dest->address) ?></td>

                                    <td class="actions" style="text-align: center">
                                        <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                            ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                'attr' => $dest->name, 'attr2' =>  $dest->iddestinos,  'id' => 'remitos_add',
                                                'onclick' => 'selectDestinoRemito(this)']) ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_destinos"
                                onclick="closeModal(this)">Salir</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- Modal Productos-->
    <div class="modal fade" id="modal_productos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header bg-navy">
                    <h4 class="modal-title" id="tittle_modal_propietarios">Selección de Productos</h4>
                    <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_productos"
                            onclick="closeModal(this)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="div_tabladata_productos">
                        <table id="tabladata_productos" name="tabla_productos" class="table table-bordered table-hover dataTable">
                            <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('Nombre') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Dirección') ?></th>
                                <th scope="col" class="actions"><?= __('Acciones') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($productos as $prod): ?>

                                <tr>
                                    <td class="dt-center"><?= h($prod->name) ?></td>
                                    <td class="dt-center"><?= h($prod->description) ?></td>

                                    <td class="actions" style="text-align: center">
                                        <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                            ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                'attr' => $prod->name, 'attr2' =>  $prod->idproductos,  'id' => 'remitos_add',
                                                'onclick' => 'selectProductosRemito(this)']) ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_productos"
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
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        });

        $('#tabladata_maq_modal').DataTable({
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
            "responsive": false,
            "pageLength": 10
        });

        $('#tabladata_prop_1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        });

        $('#tabladata_prop_2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "pageLength": 10
        });

        $('#tabladata_destinos').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "pageLength": 10
        });

        $('#tabladata_productos').DataTable({
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


