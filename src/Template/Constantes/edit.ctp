<?= $this->element('header')?>
<?= $this->element('sidebar')?>

    <div class="content-wrapper">
        <div class="container">
            <?= $this->Flash->render() ?>

            <!-- Main content -->
            <div class="card color-palette-box">

                <div class="card-header bg-navy">
                    <h3 class="card-title">
                        <?php echo $this->Html->image('constantes_navy.png' , ["alt" => 'User Image' ,
                            "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                        Nueva Constante
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                            <?= $this->Form->create($constantes, []) ?>
                            <?=  $this->Form->label('Nombre: ') ?>
                            <?= $this->Form->text('name', [
                                'class' => 'form-control',
                                'label' => 'Nombre:', 'readonly' => true]) ?>
                            <br>
                            <div class="form-group">
                                <?=  $this->Form->label('Descripción: ') ?>
                                <?= $this->Form->text('description', ['class' => 'form-control', 'placeholder' => 'Descripción', 'required']) ?>
                            </div>

                            <div class="form-group">

                                <?= $this->Form->input('value', ['class' => 'form-control', 'type' => 'number',
                                    'label' => 'Valor: ']) ?>
                            </div>

                            <br>

                            <div class="form-group" style="margin-top: 40px;">
                                <div class="pull-right">
                                    <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>
                                </div>
                                <div class="pull-left">
                                    <?= $this->Html->link("Volver", ['action' => 'view'], ['class' => 'btn btn-danger btn-flat']) ?>
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
