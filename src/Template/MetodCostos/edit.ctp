<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('costos_navy.png' , ["alt" => 'User Image' ,
                        "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Nueva Metodología de Costos
                </h3>
            </div>

            <div class="card-body">
                <div class="form-group" style="margin-top: 5px;">
                    <div class="alert alert-default-warning" role="alert">
                        <h5 class="alert-heading" style="text-justify: auto;">Observación:
                            <small>las constantes deben definirse anteponiendo el simbolo '$' tal como figura en la lista.</small></h5>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-4 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem; max-height: 60vh; overflow: auto;">
                        <div class="form-group" style="margin-top: 5px;">
                            <div class="alert alert-default-info" role="alert">
                                <h4 class="alert-heading" style="text-align:center;">Lista de Constantes</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php foreach ($lista_constantes as $lista): ?>
                                <p style="display: initial;"> <?= h('$' . $lista) ?> </p>
                                <hr class="hr-none-margin">
                            <?php endforeach; ?>

                        </div>
                    </div>

                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <?= $this->Form->create($metodologia, ['']) ?>

                        <div class="form-group">
                            <?=  $this->Form->label('Nombre: ') ?>
                            <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'Nombre', 'readOnly' => true]) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Interés: ') ?>
                            <?= $this->Form->text('interes', ['class' => 'form-control', 'placeholder' => 'Interés']) ?>
                        </div>


                        <div class="form-group">
                            <?=  $this->Form->label('Seguro: ') ?>
                            <?= $this->Form->text('seguro', ['class' => 'form-control', 'placeholder' => 'Seguro']) ?>
                        </div>


                        <div class="form-group">
                            <?=  $this->Form->label('Depreciación Máquina: ') ?>
                            <?= $this->Form->text('dep_maq', ['class' => 'form-control', 'placeholder' => 'Depreciación Máquina']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Depreciación Neumático: ') ?>
                            <?= $this->Form->text('dep_neum', ['class' => 'form-control', 'placeholder' => 'Depreciación Neumático']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Arreglos Mecánicos: ') ?>
                            <?= $this->Form->text('arreglos_maq', ['class' => 'form-control', 'placeholder' => 'Arreglos Mecánicos']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Consumo de Combustible: ') ?>
                            <?= $this->Form->text('cons_comb', ['class' => 'form-control', 'placeholder' => 'Consumo de Combustible']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Consumo de Lubricantes: ') ?>
                            <?= $this->Form->text('cons_lub', ['class' => 'form-control', 'placeholder' => 'Consumo de Lubricantes']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Operador: ') ?>
                            <?= $this->Form->text('operador', ['class' => 'form-control', 'placeholder' => 'Operador']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Mantenimiento: ') ?>
                            <?= $this->Form->text('mantenimiento', ['class' => 'form-control', 'placeholder' => 'Mantenimiento']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Administración: ') ?>
                            <?= $this->Form->text('administracion', ['class' => 'form-control', 'placeholder' => 'Administración']) ?>
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


<?= $this->Html->script('simm.js') ?>
