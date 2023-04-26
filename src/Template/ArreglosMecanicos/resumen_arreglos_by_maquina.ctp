


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
                    Nuevo Arreglo Mecánico
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                        <div class="form-group">
                            <?= $this->Form->control('maquinas_idmaquinas', ['options' => $maquinas_data,
                                'empty' => '(Elija una opción)', 'type' => 'select',
                                'class' => 'form-control', 'label' => 'Máquina:', 'required']) ?>
                        </div>

                        <div class="form-group" id="sandbox-container">
                            <?=  $this->Form->label('fecha', 'Desde: ') ?>

                            <div class="input-append date">
                                <input id="fecha" name="desde" type="month" class="span2">
                            </div>

                        </div>

                        <div class="form-group" id="sandbox-container">
                            <?=  $this->Form->label('fecha', 'Hasta: ') ?>

                            <div class="input-append date">
                                <input id="fecha" name="hasta" type="month" class="span2">
                            </div>

                        </div>

                        <br>
                        <br>


                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>
                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
