<?= $this->Html->css('jquery-filestyle.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

    <div class="content-wrapper">
        <div class="container">
            <?= $this->Flash->render() ?>

            <!-- Main content -->
            <div class="card color-palette-box">

                <div class="card-header bg-navy">
                    <h3 class="card-title">
                        <?php echo $this->Html->image('destino_white.png' , ["alt" => 'User Image' ,
                            "class" => 'img img-header-panel', 'pathPrefix' => '/webroot/img/icons/']) ?>
                        Editar Destino
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                            <?= $this->Form->create($destinos, ['enctype' => 'multipart/form-data']) ?>

                            <div class="form-group">
                                <?=  $this->Form->label('Nombre: ') ?>
                                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'Nombre', 'required']) ?>
                            </div>

                            <div class="form-group">
                                <?=  $this->Form->label('Dirección: ') ?>
                                <?= $this->Form->text('address', ['class' => 'form-control', 'placeholder' => 'Dirección', 'required']) ?>
                            </div>
                            <?=  $this->Form->label('Email: ') ?>
                            <div class="input-group mb-3">

                                <?= $this->Form->text('email', ['type' => 'email', 'class' => 'form-control', 'placeholder' => 'Email',
                                    'label' => false, 'required']) ?>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <?=  $this->Form->label('Teléfono: ') ?>
                                <?= $this->Form->text('phone', ['class' => 'form-control', 'placeholder' => 'Teléfono', 'required']) ?>
                            </div>

                            <div class="form-group login-form-gruop">
                                <?= $this->Html->image($destinos->logo, ['pathPrefix' => '/webroot/img/otros/', 'class' => 'img-thumbnail',
                                    'style' => ['width: 48px; height: 48px; display: block; margin: 0 auto;']]); ?>
                            </div>


                            <label for="title" class="cols-sm-2 control-label fw-bold">Seleccione una imágen: </label>
                            <div class="">

                                <input type="file" name="file" class="jfilestyle" data-inputSize="403px !important" accept="image/*">
                            </div>


                            <div class="form-group" style="margin-top: 40px;">
                                <div class="pull-right">
                                    <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                                </div>
                                <div class="pull-left">
                                    <?= $this->Html->link("Volver", ['controller' => 'Destinos', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                                </div>

                            </div>


                            <?= $this->Form->end() ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?= $this->Html->script('jquery-filestyle.js') ?>
