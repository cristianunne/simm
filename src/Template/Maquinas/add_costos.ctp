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
                    Nuevos Datos Teóricos
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <?= $this->Form->create($maquinas_costos, ['']) ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <?=  $this->Form->label('Máquina: ') ?>
                                    <?= $this->Form->text('nombre_maquina', ['value' => ($maquinas->marca . ': ' .$maquinas->name) ,
                                        'class' => 'form-control', 'placeholder' => '', 'disabled', 'id' => 'name_maquina',
                                        'attr' => $maquinas->idmaquinas]) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->control('worksgroups_idworksgroups', ['options' => $grupos_data,
                                        'empty' => '(Elija una opción)', 'type' => 'select',
                                        'class' => 'form-control',
                                        'label' => 'Grupo:', 'required',
                                        'onChange' => 'selectGroups(this)']) ?>
                                </div>

                            </div>

                            <div class="col-md-5"  style="margin-left: auto;">
                                <div class="form-group">
                                    <?= $this->Form->control('centros_costos_idcentros_costos', ['options' => null,
                                        'empty' => '(Elija una opción)', 'type' => 'select',
                                        'class' => 'form-control',
                                        'label' => 'Centro de Costos:', 'required', 'id' => 'select_centro_costos']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->control('metod_costos_hashmetod_costos', ['options' => $metod_costos_data,
                                        'empty' => '(Elija una opción)', 'type' => 'select',
                                        'class' => 'form-control',
                                        'label' => 'Metodología de Costos:', 'required']) ?>
                                </div>

                            </div>

                            <hr style="width: 97%; margin-top: 25px;">

                            <div class="col-md-12">

                                <div class="col-md-5" style="margin: auto;">
                                    <?= $this->Form->control('alquilada', ['options' => [1 => 'SI', 0 => 'NO'],
                                        'empty' => '(Elija una opción)', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => '', 'id' => 'select_conditions',
                                        'label' => '¿La Máquina es Alquilada?', 'onchange' => 'selectConditionsMaquina(this)']) ?>
                                    <br>
                                </div>

                            </div>
                            <div class="col-md-5">
                                <br>
                                <br>

                                <div class="form-group">
                                    <?= $this->Form->input('val_adq', ['class' => 'form-control', 'type' => 'number', 'label' => 'Valor de Adquisición ($): ', 'required']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('vida_util', ['class' => 'form-control', 'type' => 'number', 'label' => 'Vida útil (Años): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('horas_total_uso', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Horas totales de uso (horas): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('horas_mens_uso', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Horas mensuales de uso (horas): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('tasa_int_simple', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Tasa de interés simple: ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('consumo', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Consumo (litros/hora): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('costo_alquiler', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Alquiler ($/ton): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->control('credito', ['options' => [1 => 'SI', 0 => 'NO'],
                                        'empty' => '(Elija una opción)', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => '',
                                        'label' => '¿Comprada con crédito?']) ?>
                                </div>


                            </div>

                            <div class="col-md-5" style="margin-left: auto;">
                                <br>
                                <br>
                                <div class="form-group">
                                    <?= $this->Form->input('val_neum', ['class' => 'form-control', 'type' => 'number', 'label' => 'Valor de Neum./Piezas de Reposición ($): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('vida_util_neum', ['class' => 'form-control', 'type' => 'number', 'label' => 'Vida útil Neumáticos (horas): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('horas_efec_uso', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Horas efectivas de uso anual (horas): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('horas_dia_uso', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Horas diarias de uso (horas): ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('factor_cor', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Factor de correción: ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('coef_err_mec', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Coeficiente de arreglos mecánicos: ']) ?>
                                </div>

                                <div class="form-group">
                                    <?= $this->Form->input('lubricante', ['class' => 'form-control', 'type' => 'number',
                                        'label' => 'Lubricante (litros/hora): ']) ?>
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>
                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['action' => 'indexCostos', $id], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

