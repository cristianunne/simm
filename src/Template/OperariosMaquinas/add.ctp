
<?= $this->element('header')?>
<?= $this->element('sidebar')?>
    <div class="content-wrapper">
        <div class="container">

            <!-- Main content -->
            <div class="card color-palette-box">

                <?= $this->Flash->render() ?>

                <div class="card-header bg-navy">
                    <h3 class="card-title">
                        <?php echo $this->Html->image('salario_navy.png' , ["alt" => 'User Image' ,
                            "class" => 'img img-header-panel', 'pathPrefix' => '/webroot/img/icons/']) ?>
                        Carga de Salario
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                            <?= $this->Form->create($operarios_maq, ['']) ?>

                            <div class="form-group" id="group-firstname">
                                <?=  $this->Form->label('Nombre/s: ') ?>
                                <?= $this->Form->text('nombre', ['class' => 'form-control', 'value' => $operario, 'readOnly' => true]) ?>
                            </div>

                            <div class="form-group" id="sandbox-container">
                                <?=  $this->Form->label('fecha', 'Fecha: ') ?>
                                <div class="input-append date">
                                    <input id="fecha" name="fecha" type="date" class="span2" required>
                                </div>
                            </div>

                            <?= $this->Form->control('maquinas_idmaquinas', ['options' => $lista_maquinas,
                                'empty' => '(Elija una opción)', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => '',
                                'label' => 'Máquina:']) ?>
                            <br>

                            <div class="form-group" id="group-dni" >
                                <div class="form-group">
                                    <?= $this->Form->input('sueldo', ['class' => 'form-control', 'type' => 'number', 'label' => 'Salario: ']) ?>
                                </div>

                            </div>

                            <div class="form-group" style="margin-top: 40px;">
                                <div class="pull-right">
                                    <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                                </div>
                                <div class="pull-left">
                                    <?= $this->Html->link("Volver", ['controller' => 'Operarios', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                                </div>

                            </div>


                            <?= $this->Form->end() ?>
                        </div>

                    </div>
                </div>

            </div> <!-- End Main content -->
        </div>
    </div>

<?= $this->Html->script('simm.js') ?>
