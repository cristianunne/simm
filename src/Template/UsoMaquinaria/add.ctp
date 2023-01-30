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
                    <?php echo $this->Html->image('tractor_white.png' , ["alt" => 'User Image' ,
                        "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Nuevo Registro de Uso de Maquinaria
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <?= $this->Form->create($uso_maquina, ['']) ?>

                        <?= $this->Form->control('maquinas_idmaquinas', ['options' => $maquinas_data,
                            'empty' => '(Elija una opción)', 'type' => 'select',
                            'class' => 'form-control', 'placeholder' => '',
                            'label' => 'Máquina:']) ?>
                        <br>

                        <?=  $this->Form->label('Lotes: ') ?>
                        <div style="display: flex;">
                            <?= $this->Form->text('lotes_name', ['class' => 'form-control', 'placeholder' => 'Lotes',
                                'disabled', 'id' => 'input_lotes', 'style'=> 'margin-right: 10px;',]) ?>

                            <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-search', 'aria-hidden' => 'true']),
                                ['type' => 'button', 'class' => 'btn bg-navy', 'escape' => false, 'onclick' => 'showModalLotes()']) ?>

                        </div>

                        <div class="form-group" id="group-firstname" style="display: none;">
                            <?= $this->Form->text('lotes_idlotes', ['class' => 'form-control', 'id' => 'lotes_idlotes']) ?>
                        </div>

                        <br>

                        <div class="form-group">
                            <?= $this->Form->control('parcelas_idparcelas', ['options' => null,
                                'empty' => '(Elija una Parcela)', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Parcela',
                                'label' => 'Parcela:', 'disabled', 'id' => 'parcela',]) ?>
                        </div>

                        <br>

                        <div class="form-group" id="sandbox-container">
                            <?=  $this->Form->label('fecha', 'Fecha: ') ?>
                            <div class="input-append date">
                                <input id="fecha" name="fecha" type="date" class="span2">
                            </div>
                        </div>

                        <br>

                        <div class="form-group">
                            <?= $this->Form->control('horas_trabajo', ['class' => 'form-control', 'type' => 'number', 'label' => 'Horas de Trabajo (h): ']) ?>
                        </div>
                        <br>

                        <div class="content-usomaq">
                            <label for="option_comb">Combustible:</label>
                            <?= $this->Form->control('option_comb', ['options' => $combustibles,
                                'empty' => '(Elija una opción)', 'type' => 'select', 'id' => 'category_comb',
                                'class' => 'form-control select-uso', 'placeholder' => '',
                                'label' => false]) ?>


                            <div class="form-group">
                                <?= $this->Form->control('litros', ['class' => 'form-control', 'type' => 'number',
                                    'label' => false, 'placeholder' => 'Litros (l)', 'id' => 'litros_comb']) ?>
                            </div>

                            <div class="form-group">
                                <?= $this->Form->control('precio', ['class' => 'form-control', 'type' => 'number',
                                    'label' => false, 'placeholder' => '($)/l', 'id' => 'precio_comb']) ?>
                            </div>

                            <div class="form-group" id="div_btn_check_comb">
                                <button type="button" class="btn btn-success" aria-label="Left Align" onclick="addCombustibleToTable()">
                                    <span class="fas fa-check" aria-hidden="true"></span>
                                </button>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <hr style="margin-top: 25px;">
                            <table id="tabladata" class="table table-bordered table-hover dataTable">
                                <thead>
                                <tr>

                                    <th scope="col"><?= $this->Paginator->sort('N°') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Categoria') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Producto') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Litros') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Precio ($/l)') ?></th>
                                    <th scope="col" class="actions"><?= __('Acciones') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="combustible_hidden" style="display: none;">

                        </div>
                        <br>
                        <hr>
                        <br>

                        <!-- AHora proceso Lubricantes-->
                        <div class="content-usomaq">
                            <label for="option_comb">Lubricantes:</label>
                            <?= $this->Form->control('option_comb', ['options' => $lubricantes,
                                'empty' => '(Elija una opción)', 'type' => 'select', 'id' => 'category_lub',
                                'class' => 'form-control select-uso', 'placeholder' => '',
                                'label' => false]) ?>

                            <div class="form-group">
                                <?= $this->Form->control('litros', ['class' => 'form-control', 'type' => 'number',
                                    'label' => false, 'placeholder' => 'Litros (l)', 'id' => 'litros_lub']) ?>
                            </div>

                            <div class="form-group">
                                <?= $this->Form->control('precio', ['class' => 'form-control', 'type' => 'number',
                                    'label' => false, 'placeholder' => '($)/l', 'id' => 'precio_lub']) ?>
                            </div>

                            <div class="form-group" id="div_btn_check_lub">
                                <button type="button" class="btn btn-success" aria-label="Left Align" onclick="addLubricanteToTable()">
                                    <span class="fas fa-check" aria-hidden="true"></span>
                                </button>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <hr style="margin-top: 25px;">
                            <table id="tabladata_2" class="table table-bordered table-hover dataTable">
                                <thead>
                                <tr>

                                    <th scope="col"><?= $this->Paginator->sort('N°') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Categoria') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Producto') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Litros') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Precio ($/l)') ?></th>
                                    <th scope="col" class="actions"><?= __('Acciones') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div id="lubricante_hidden" style="display: none;">

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

    <!-- Modal Lotes-->
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
                                                'attr' => $lote->name, 'attr2' =>  $lote->idlotes, 'id' => 'usomaquinaria_add',
                                                'onclick' => 'selectLoteUsoMaquinaria(this)']) ?>
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
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "pageLength": 10
        });

        $('#tabladata_2').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
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
