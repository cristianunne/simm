
<?= $this->element('header')?>
<?= $this->element('sidebar')?>


<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('arreglos_mecanicos_white.png' , ["alt" => 'User Image' ,
                        "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Editar Arreglo Mecánico
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <?= $this->Form->create($arreglos, ['']) ?>

                        <div class="form-group">
                            <?= $this->Form->control('worksgroups_idworksgroups', ['options' => $worksgroup_data,
                                'empty' => '(Elija una opción)', 'type' => 'select',
                                'class' => 'form-control', 'label' => 'Grupo de Trabajo:', 'required']) ?>
                        </div>

                        <div class="form-group">
                            <?= $this->Form->control('maquinas_idmaquinas', ['options' => $maquinas_data,
                                'empty' => '(Elija una opción)', 'type' => 'select',
                                'class' => 'form-control', 'label' => 'Máquina:', 'required']) ?>
                        </div>


                        <div class="form-group" id="sandbox-container">
                            <?=  $this->Form->label('fecha', 'Fecha: ') ?>

                            <div class="input-append date">
                                <input id="fecha" name="fecha" type="date" class="span2" value="<?= h($arreglos->fecha->format('Y-m-d')) ?>">
                            </div>

                        </div>

                        <br>
                        <br>
                        <div class="card color-palette-box">
                            <div class="card-header bg-gray-light">
                                <h3 class="card-title">
                                    Datos del Comprobante
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="col-md-12" >

                                    <!-- Consulto si el lote o la parcela estan vacaias -->

                                    <?php if($arreglos->parcelas_idparcelas != ''):  ?>

                                        <div class="form-group">
                                            <?= $this->Form->control('parcela.lote.idlotes', ['options' => $lotes_data,
                                                'empty' => '(Elija una opción)', 'type' => 'select',
                                                'class' => 'form-control', 'label' => 'Lotes:',
                                                'id' => 'lotes', 'onChange' => 'getParcelaByLoteEdit(this)']) ?>
                                        </div>

                                        <div class="form-group">
                                            <?= $this->Form->control('parcelas_idparcelas', ['options' => $parcela_data,
                                                'empty' => '(Elija una Parcela)', 'type' => 'select',
                                                'class' => 'form-control', 'placeholder' => 'Parcela',
                                                'label' => 'Parcela:', 'id' => 'parcela',]) ?>
                                        </div>

                                    <?php endif;  ?>

                                    <div class="form-group">
                                        <?=  $this->Form->label('N° de Comprobante: ') ?>
                                        <?= $this->Form->text('num_comprobante', ['class' => 'form-control']) ?>
                                    </div>

                                    <div class="form-group">
                                        <?=  $this->Form->label('Concepto (Max - 255): ') ?>
                                        <?= $this->Form->textarea('concepto', ['class' => 'form-control', 'maxLength'=>'255']) ?>
                                    </div>

                                    <div class="form-group">
                                        <?= $this->Form->input('mano_obra', ['class' => 'form-control', 'type' => 'number', 'label' => 'Mano de Obra ($): ']) ?>
                                    </div>

                                    <div class="form-group">
                                        <?= $this->Form->input('repuestos', ['class' => 'form-control', 'type' => 'number', 'label' => 'Repuestos ($): ']) ?>
                                    </div>

                                </div>
                            </div>
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
</div>
