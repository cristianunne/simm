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
                    <?php echo $this->Html->image('group_work_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Análisis de Costos - Grupos
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2" style="border: solid 1px #c1c1c1;">
                        <p class="title-box-ac">Grupo de Trabajo</p>
                        <hr>
                        <div class="form-group">
                            <?= $this->Form->control('worksgroups_idworksgroups', ['options' => $grupos_data,
                                'empty' => '(Elija un Grupo)', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Grupo:',
                                'label' => 'Grupo de Trabajo:', 'id' => 'worksgroups_idworksgroups']) ?>
                        </div>

                    </div>


                    <div class="col-md-3" style="border: solid 1px #c1c1c1; margin-left: 5px;">
                        <p class="title-box-ac">Período</p>
                        <hr>
                        <div class="form-group sandbox-container" id="sandbox-container">
                            <?=  $this->Form->label('fecha_inicio', 'Inicio: ', ['class' => 'label-m10']) ?>
                            <div class="input-append date" style="margin-left: 1px;">
                                <input id="fecha_inicio" name="fecha_inicio" type="date" class="span2" required>
                            </div>
                        </div>

                        <div class="form-group sandbox-container" id="sandbox-container">
                            <?=  $this->Form->label('fecha_final', 'Final: ', ['class' => 'label-m10 width-45px']) ?>
                            <div class="input-append date">
                                <input id="fecha_final" name="fecha_final" type="date" class="span2" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4" style="border: solid 1px #c1c1c1; margin-left: 5px; padding-bottom: 10px;">
                        <p class="title-box-ac align-middle">Lote, Parcela, Propietario, Destinos</p>
                        <hr>



                        <div style="display: flex; align-items: baseline; column-gap: 5px">

                            <?=  $this->Form->label('Lote: ') ?>
                            <?= $this->Form->control('lotes_idlotes', ['options' => [0 => 'Todos'],
                                'empty' => 'Elija un Lote', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Parcela',
                                'label' => false, 'id' => 'lotes_idlotes', 'required']) ?>


                            <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalLotes()',
                                    'style'=> 'margin-left: 10px;']) ?>

                        </div>

                        <br>
                        <div style="display: flex; align-items: baseline; column-gap: 5px">
                            <?=  $this->Form->label('Parcela: ') ?>
                            <?= $this->Form->control('parcelas_idparcelas', ['options' => [0 => 'Todos'],
                                'empty' => '(Elija una Parcela)', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Parcela',
                                'label' => false, 'required', 'id' => 'parcela']) ?>
                        </div>


                        <br>
                        <div style="display: flex; align-items: baseline; column-gap: 5px">
                            <?=  $this->Form->label('Propietario: ') ?>
                            <?= $this->Form->control('propietarios_idpropietarios', ['options' => [0 => 'Todos'],
                                'empty' => 'Elija un Propietario', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Propietarios',
                                'label' => false, 'id' => 'propietarios_idpropietarios', 'required']) ?>


                            <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalAll(this)',
                                    'attr' => 'modal_propietarios',  'style'=> 'margin-left: 10px;']) ?>

                        </div>

                        <br>

                        <div style="display: flex; align-items: baseline; column-gap: 5px">
                            <?=  $this->Form->label('Destino: ') ?>
                            <?= $this->Form->control('destinos_iddestinos', ['options' => [0 => 'Todos'],
                                'empty' => 'Elija un Destino', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Destinos',
                                'label' => false, 'id' => 'destinos_iddestinos', 'required']) ?>

                            <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalAll(this)',
                                    'attr' => 'modal_destinos', 'style'=> 'margin-left: 10px;']) ?>
                        </div>



                    </div>

                    <div class="col-md-2 float-right" style="border: solid 1px #c1c1c1; margin-left: 55px;">
                        <div class="form-group">
                            <br>
                            <br>
                            <button type="button" class="btn btn-block bg-gradient-primary">Calcular</button>
                            <?= $this->Html->link('Prueba',
                                ['controller' => 'AnalisisCostos', 'action' => 'calculateCostosGrupos'],
                                ['class' => 'btn btn-success', 'escape' => false]) ?>
                            <br>
                            <button type="button" class="btn btn-block bg-gradient-warning">Configurar</button>
                            <button type="button" class="btn btn-block bg-gradient-success">Generar Informe</button>
                        </div>

                    </div>

                </div>

                <div class="row" style="margin-top: 10px;">
                    <!-- Necesito 3 ventanas -->
                    <div class="col-md-4" style="border: solid 1px #c1c1c1; ">

                        <div class="row">
                            <div class="div-costos-header">
                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_inicio', 'Costo/tonelada: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" value="Otro texto"/>

                                </div>

                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_final', 'Toneladas: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" value="Otro texto"/>
                                </div>

                            </div>

                            <div>
                                <ul id="treeview">
                                    <li data-icon-cls="fa fa-inbox" data-expanded="true">Inbox
                                        <ul>
                                            <li><b>Today (2)</b></li>
                                            <li>Monday</li>
                                            <li>Last Week</li>
                                        </ul>
                                    </li>
                                    <li data-icon-cls="fa fa-trash">Trash
                                    </li>
                                    <li data-icon-cls="fa fa-calendar">Calendar
                                        <ul>
                                            <li>Day</li>
                                            <li>Week</li>
                                            <li>Month</li>
                                        </ul>
                                    </li>
                                    <li data-icon-cls="fa fa-user">Contacts
                                        <ul>
                                            <li>Alexander Stein</li>
                                            <li>John Doe</li>
                                            <li>Paul Smith</li>
                                            <li>Steward Lynn</li>
                                        </ul>
                                    </li>
                                    <li data-icon-cls="fa fa-folder">Folders
                                        <ul>
                                            <li>Backup</li>
                                            <li>Deleted</li>
                                            <li>Projects</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4" style="border: solid 1px #c1c1c1; ">
                        <div class="row">
                            <div class="div-costos-header">
                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_inicio', 'Costo/tonelada: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" value="Otro texto"/>

                                </div>

                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_final', 'Toneladas: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" disabled value="Otro texto"/>
                                </div>

                            </div>

                            <div>
                                <ul id="treeview_transporte">
                                    <li data-icon-cls="fa fa-inbox" data-expanded="true">Inbox
                                        <ul>
                                            <li><b>Today (2)</b></li>
                                            <li>Monday</li>
                                            <li>Last Week</li>
                                        </ul>
                                    </li>
                                    <li data-icon-cls="fa fa-trash">Trash
                                    </li>
                                    <li data-icon-cls="fa fa-calendar">Calendar
                                        <ul>
                                            <li>Day</li>
                                            <li>Week</li>
                                            <li>Month</li>
                                        </ul>
                                    </li>
                                    <li data-icon-cls="fa fa-user">Contacts
                                        <ul>
                                            <li>Alexander Stein</li>
                                            <li>John Doe</li>
                                            <li>Paul Smith</li>
                                            <li>Steward Lynn</li>
                                        </ul>
                                    </li>
                                    <li data-icon-cls="fa fa-folder">Folders
                                        <ul>
                                            <li>Backup</li>
                                            <li>Deleted</li>
                                            <li>Projects</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-4" style="border: solid 1px #c1c1c1; ">

                        <div class="row">
                            <div class="div-costos-header">
                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_inicio', 'Detalles: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" value="Otro texto"/>

                                </div>

                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_final', 'Costo/hora: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" disabled value="Otro texto"/>
                                </div>

                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_inicio', 'Prod./hora: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" value="Otro texto"/>

                                </div>

                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_final', 'Costo/t: ', ['class' => 'label-m10']) ?>
                                    <input type="text" class="form-control" disabled value="Otro texto"/>
                                </div>

                            </div>


                        </div>

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

                    <button type="button" class="btn bg-gradient-success" onclick="selectLotesAll()">Todos</button>

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
                    <button type="button" class="btn bg-gradient-success" onclick="selectPropAll()">Todos</button>
                    <br>
                    <div class="form-group" style="margin-top: 15px;">
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


</div>


<!-- you need to include the ShieldUI CSS and JS assets in order for the TreeView widget to work -->
<?= $this->Html->css('shieldui/all.min.css') ?>
<?= $this->Html->script('shieldui/shieldui-all.min.js') ?>


<script type="text/javascript">
    jQuery(function ($) {
        $("#treeview").shieldTreeView();
    });

    jQuery(function ($) {
        $("#treeview_transporte").shieldTreeView();
    });
</script>





