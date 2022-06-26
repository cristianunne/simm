<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('lote_navy.png' , ["alt" => 'User Image' ,
                        "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Nuevo Lote
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <?= $this->Form->create($lotes, ['']) ?>

                        <div class="form-group">
                            <?=  $this->Form->label('Nombre: ') ?>
                            <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'Nombre', 'required']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Descripción: ') ?>
                            <?= $this->Form->text('description', ['class' => 'form-control', 'placeholder' => 'Descripción', 'required']) ?>
                        </div>

                        <?= $this->Form->control('provincia', ['options' => $provincias,
                            'empty' => '(Elija una Provincia)', 'type' => 'select',
                            'class' => 'form-control', 'placeholder' => 'Provincia', 'id' => 'provincia',
                            'label' => 'Provincia:', 'required', 'onChange' => 'loadDptos(this)']) ?>
                        <br>

                        <?= $this->Form->control('departamento', ['options' => null,
                            'empty' => '(Elija un Departamento)', 'type' => 'select',
                            'class' => 'form-control', 'placeholder' => 'Departamento',
                            'label' => 'Departamentos:', 'required', 'id' => 'dptos', 'disabled']) ?>

                        <br>

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
