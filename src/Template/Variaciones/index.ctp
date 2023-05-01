<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>

<?= $this->Html->css('jquery-confirm.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-calculator"></i>
                    Variaciones (Grupos y Máquinas)
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4" style="border: solid 1px #c1c1c1; margin-left: 5px;">
                        <div class="box-variaciones-header">
                            <p class="title-box-ac">Selección</p>
                        </div>

                        <hr>
                        <?=  $this->Form->label('Grupos: ') ?>
                        <?= $this->Form->control('grupo', ['options' => $grupos_data,
                            'empty' => 'Elija un Grupo', 'type' => 'select',
                            'class' => 'form-control', 'placeholder' => 'Grupo',
                            'label' => false, 'id' => 'grupos', 'required']) ?>
                        <br>
                        <?=  $this->Form->label('Máquinas: ') ?>
                        <?= $this->Form->control('maquinas', ['options' => $maquinas,
                            'empty' => 'Elija una Maquina', 'type' => 'select',
                            'class' => 'form-control', 'placeholder' => 'Grupo',
                            'label' => false, 'id' => 'maquinas', 'required', 'disabled']) ?>

                        <br>
                        <hr>
                        <div class="box-variaciones-header">
                            <p class="title-box-ac">Intervalo</p>
                        </div>
                        <br>

                        <div class="form-group sandbox-container" id="sandbox-container">
                            <?=  $this->Form->label('fecha_inicio', 'Inicio: ', ['class' => 'label-m10']) ?>
                            <div class="input-append date" style="margin-left: 1px;">
                                <input id="fecha_inicio" name="fecha_inicio" type="month" class="span2" required>
                            </div>
                        </div>

                        <div class="form-group sandbox-container" id="sandbox-container">
                            <?=  $this->Form->label('fecha_final', 'Final: ', ['class' => 'label-m10 width-45px']) ?>
                            <div class="input-append date">
                                <input id="fecha_final" name="fecha_final" type="month" class="span2" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4" style="border: solid 1px #c1c1c1; margin-left: 5px; padding-bottom: 10px;">

                        <div class="box-variaciones-header">
                            <p class="title-box-ac align-middle">Lote, Parcela, Destinos</p>
                        </div>
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

                    <div class="col-md-3" style="border: solid 1px #c1c1c1; margin-left: 5px; padding-bottom: 10px;">
                        <div class="box-variaciones-header">
                            <p class="title-box-ac align-middle">Evolución</p>
                        </div>

                        <hr>
                        <div class="form-check" id="rb-evolucion">
                            <?= $this->Form->input('evolucion', ['options' => $rb_evolucion, 'type' => 'radio',
                                'class' => 'form-check-input', 'label' => false, 'default' => 1, 'id' => 'evolucion_form',
                                'onClick' => 'selectTypeOfVariacion(this)']) ?>
                        </div>

                        <hr>
                        <div style="margin-top: 25%;">
                            <button type="button" class="btn btn-block bg-gradient-primary"
                                    onclick="graficarVariacion()">Gráficar</button>

                            <button type="button" class="btn btn-block bg-gradient-success" id="down_informe"
                                    style="display: none;" onclick="downloadInformeVariacion(this)">
                                <span class="glyphicon far fa-file-excel"></span> Descargar Informe</button>
                        </div>



                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12" style="margin-left: 5px; padding-bottom: 10px;">
                        <h5 id="title_graphic" style="text-align: center;">Variación: </h5>

                        <div>
                            <canvas id="myChart"></canvas>
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
                                                'attr3' => 'variaciones',
                                                'onclick' => 'selectLoteCostos(this)']) ?>
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

                    <button type="button" class="btn bg-gradient-success" onclick="selectDestAll()">Todos</button>
                    <br>
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

<?= $this->Html->script('jquery-confirm.min.js') ?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?= $this->Html->script('variaciones.js') ?>

