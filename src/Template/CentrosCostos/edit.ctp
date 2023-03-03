
<?= $this->element('header')?>
<?= $this->element('sidebar')?>

    <div class="content-wrapper">
        <div class="container">
            <?= $this->Flash->render() ?>

            <!-- Main content -->
            <div class="card color-palette-box">

                <div class="card-header bg-navy">
                    <h3 class="card-title">
                        <?php echo $this->Html->image('centro_costos_white.png' , ["alt" => 'User Image' ,
                            "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                        Nuevo Centro de Costos
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                            <?= $this->Form->create($centros_costos, ['']) ?>

                            <div class="form-group">
                                <?=  $this->Form->label('Nombre: ') ?>
                                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'Nombre', 'required']) ?>
                            </div>

                            <div class="form-group">
                                <?=  $this->Form->label('Orden: ') ?>
                                <?= $this->Form->text('orden', ['class' => 'form-control', 'placeholder' => 'Orden', 'required']) ?>
                            </div>

                            <div class="form-group">
                                <?= $this->Form->control('categoria', ['options' => $categorias,
                                    'empty' => '(Elija una Categoria)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Categoria:']) ?>
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


