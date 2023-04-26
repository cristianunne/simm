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
                    <?php echo $this->Html->image('group_work_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Análisis de Costos - Maquinas - Resultados
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                        <div class="row">
                            <div class="col-md-3" style="border: solid 1px #c1c1c1; ">
                                <p class="title-box-ac">Máquina</p>
                                <hr>
                                <?= $this->Form->control('maquina', ['options' => $maquinas, 'value' => $maq,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '', 'disabled',
                                    'label' => '']) ?>

                                <br>
                            </div>

                            <div class="col-md-4" style="border: solid 1px #c1c1c1; margin-left: 5px;">

                                <p class="title-box-ac">Período</p>
                                <hr>
                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_inicio', 'Inicio: ', ['class' => 'label-m10']) ?>

                                        <input id="fecha_inicio" name="fecha" type="text"
                                               class="span2" value="<?= h($fecha_inicio) ?>" disabled>

                                </div>


                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_final', 'Final: ', ['class' => 'label-m10 width-45px']) ?>

                                        <input id="fecha_final" name="fecha" type="text"
                                               class="span2" value="<?= h($fecha_fin) ?>" disabled>

                                </div>

                                <br>
                            </div>

                            <div class="col-md-4" style="border: solid 1px #c1c1c1; margin-left: 5px;">

                                <p class="title-box-ac">Lote, Parcela, Propietario, Destinos</p>
                                <hr>

                                <div style="display: flex; align-items: baseline; column-gap: 5px">

                                    <?=  $this->Form->label('Lote: ') ?>
                                    <?= $this->Form->control('lotes_idlotes', ['options' => $lotes, 'value' => $lote_value,
                                        'empty' => 'Elija un Lote', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Parcela',
                                        'label' => false, 'id' => 'lotes_idlotes', 'required']) ?>


                                </div>

                                <br>
                                <div style="display: flex; align-items: baseline; column-gap: 5px">
                                    <?=  $this->Form->label('Parcela: ') ?>
                                    <?= $this->Form->control('parcelas_idparcelas', ['options' => $parcelas, 'value' => $parcela_value,
                                        'empty' => '(Elija una Parcela)', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Parcela',
                                        'label' => false, 'required', 'id' => 'parcela']) ?>
                                </div>

                                <br>
                                <div style="display: flex; align-items: baseline; column-gap: 5px">
                                    <?=  $this->Form->label('Propietario: ') ?>
                                    <?= $this->Form->control('propietarios_idpropietarios', ['options' => $propietarios, 'value' => $propietarios_value,
                                        'empty' => 'Elija un Propietario', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Propietarios',
                                        'label' => false, 'id' => 'propietarios_idpropietarios', 'required']) ?>

                                </div>
                                <br>

                                <div style="display: flex; align-items: baseline; column-gap: 5px">
                                    <?=  $this->Form->label('Destino: ') ?>
                                    <?= $this->Form->control('destinos_iddestinos',  ['options' => $destinos, 'value' => $destinos_value,
                                        'empty' => 'Elija un Destino', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Destinos',
                                        'label' => false, 'id' => 'destinos_iddestinos', 'required']) ?>
                                </div>
                                <br>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <div class="row">
                            <div class="col-md-5">
                                <div id="div_treeview_left">

                                    <div id="info-header-left" class="alert alert-default-info" role="alert">
                                        Resumen:
                                    </div>
                                    <table class="table table-bordered table-hover dataTable no-footer">
                                        <tr>
                                            <td><strong>Toneladas extraídas: </strong></td>
                                            <td><?= h($data_result['costos']['toneladas']) ?></td>
                                        </tr>

                                        <tr>
                                            <td><strong>% sobre el total extraído en el periodo: </strong></td>
                                            <td><?= h('') ?></td>
                                        </tr>
                                        <tr></tr>
                                        <tr>
                                            <td><strong>Horas trabajadas: </strong></td>
                                            <td><?= h($data_result['costos']['horas']) ?></td>
                                        </tr>

                                        <tr>
                                            <td><strong>% sobre el total extraído en el periodo: </strong></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td><strong>Rendimiento: </strong></td>
                                            <td><?= h(number_format($data_result['costos']['prod_rend_h'], 2)) ?></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>

                            <div class="col-md-6" style="padding-left: 40px;">
                                <div id="div_treeview_left">

                                    <div id="info-header-left" class="alert alert-default-info" role="alert">
                                        Costo Tolal: <?= h(number_format($data_result['costos']['costo_h'], 2, ",",".") . " $/h") ?>
                                    </div>

                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Costo de la Máquina: <?= h(number_format($data_result['costos_groups']['costo_maquina'], 2, ",",".") . " $/h") ?>
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li style="font-size: 15px;"><strong>Costos Fijos: </strong><?= h(number_format($data_result['costos_groups']['costos_fijos'], 2, ",",".") . " $/h") ?></li>

                                                        <ul>
                                                            <li>Ínteres: <?= h(number_format($data_result['result_metod']['interes'], 2, ",",".") . " $/h") ?></li>
                                                            <li>Seguro: <?= h(number_format($data_result['result_metod']['seguro'], 2, ",",".") . " $/h") ?></li>
                                                        </ul>
                                                    </ul>
                                                    <ul>
                                                        <li style="font-size: 15px;"><strong>Costos Semifijos: </strong><?= h(number_format($data_result['costos_groups']['costos_semifijos'], 2, ",",".") . " $/h") ?></li>
                                                        <ul>
                                                            <li>Depraciación de la máquina: <?= h(number_format($data_result['result_metod']['dep_maq'], 2, ",",".") . " $/h") ?></li>
                                                            <li>Depraciación de los neumáticos: <?= h(number_format($data_result['result_metod']['dep_neum'], 2, ",",".") . " $/h") ?></li>
                                                            <li>Arreglos en la máquina: <?= h(number_format($data_result['result_metod']['arreglos_maq'], 2, ",",".") . " $/h") ?></li>

                                                        </ul>
                                                    </ul>
                                                    <ul>
                                                        <li style="font-size: 15px;"><strong>Costos Variables: </strong><?= h(number_format($data_result['costos_groups']['costos_variables'], 2, ",",".") . " $/h") ?></li>
                                                        <ul>
                                                            <li>Consumo de Combustible: <?= h(number_format($data_result['result_metod']['cons_comb'], 2, ",",".") . " $/h") ?></li>
                                                            <li>Consumo de Lubricantes: <?= h(number_format($data_result['result_metod']['cons_lub'], 2, ",",".") . " $/h") ?></li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    Costo horario de personal: <?= h(number_format($data_result['costos_groups']['costo_horario_personal'], 2, ",",".") . " $/h") ?>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li style="font-size: 15px;"><strong>Operador: </strong><?= h(number_format($data_result['result_metod']['operador'], 2, ",",".") . " $/h") ?></li>
                                                        <li style="font-size: 15px;"><strong>Mantenimiento: </strong><?= h(number_format($data_result['result_metod']['mantenimiento'], 2, ",",".") . " $/h") ?></li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    Costo horario de administración: <?= h(number_format($data_result['result_metod']['administracion'], 2, ",",".") . " $/h") ?>
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                 </div>
                                            </div>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>

                        <div class="form-group" style="margin-top: 40px;">



                            <div class="pull-right">

                                <?php if($path_excel != '' and $path_excel != null):  ?>
                                    <button type="button" class="btn btn-block bg-gradient-success" id="down_informe"
                                            attr="<?= h($path_excel) ?>" onclick="downloadInforme(this)">
                                        <span class="glyphicon far fa-file-excel"></span> Descargar Informe</button>
                                <?php endif;?>

                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['action' => 'calculateCostosMaquina'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>
                </div>


            </div>
    </div>
</div>




<?= $this->Html->script('jquery-confirm.min.js') ?>

<?= $this->Html->script('../plugins/datatables/jquery.dataTables.min.js') ?>
<?= $this->Html->script('../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.html5.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.print.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.colVis.min.js') ?>
