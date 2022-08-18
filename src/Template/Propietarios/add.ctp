<?= $this->Html->css('jquery-filestyle.css') ?>

<?= $this->element('header')?>
<?= $this->element('sidebar')?>
    <div class="content-wrapper">
        <div class="container">

            <!-- Main content -->
            <div class="card color-palette-box">

                <?= $this->Flash->render() ?>

                <div class="card-header bg-navy">
                    <h3 class="card-title">
                        <?php echo $this->Html->image('propietario_navy.png' , ["alt" => 'User Image' ,
                            "class" => 'img img-header-panel', 'pathPrefix' => '/webroot/img/icons/']) ?>
                        Nuevo Propietario
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                            <?= $this->Form->create($propietarios, ['enctype' => 'multipart/form-data']) ?>

                                <div class="form-group">
                                    <?= $this->Form->control('tipo', ['options' => ['Persona' => 'Persona', 'Empresa' => 'Empresa'],
                                        'empty' => '(Elija una opción)', 'type' => 'select',
                                        'class' => 'form-control', 'placeholder' => 'Jurisdicción',
                                        'label' => 'Tipo:', 'required', 'onChange' => 'selectTypePropietario(this)']) ?>
                                </div>

                                <div class="form-group" id="group-firstname" style="display: none;">
                                    <?=  $this->Form->label('Nombre/s: ') ?>
                                    <?= $this->Form->text('firstname', ['class' => 'form-control', 'placeholder' => 'Nombre']) ?>
                                </div>

                                <div class="form-group" id="group-lastname" style="display: none;">
                                    <?=  $this->Form->label('Apellido: ') ?>
                                    <?= $this->Form->text('lastname', ['class' => 'form-control', 'placeholder' => 'Nombre']) ?>
                                </div>

                                <div class="form-group" id="group-name" style="display: none;">
                                    <?=  $this->Form->label('Nombre: ') ?>
                                    <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'Nombre']) ?>
                                </div>
                                <div class="form-group" id="group-dni" style="display: none;">
                                    <?= $this->Form->label('DNI: ') ?>
                                    <?= $this->Form->text('dni', ['class' => 'form-control', 'placeholder' => 'DNI']) ?>
                                </div>

                                <div class="form-group">
                                    <?=  $this->Form->label('Dirección: ') ?>
                                    <?= $this->Form->text('address', ['class' => 'form-control', 'placeholder' => 'Dirección', 'required']) ?>
                                </div>

                                <?= $this->Form->label('Email: ') ?>
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
                                    <?= $this->Form->label('Teléfono: ') ?>
                                    <?= $this->Form->text('phone', ['class' => 'form-control', 'placeholder' => 'Teléfono', 'required']) ?>
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
                                        <?= $this->Html->link("Volver", ['controller' => 'Propietarios', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                                    </div>

                                </div>


                            <?= $this->Form->end() ?>
                        </div>

                    </div>
                </div>

            </div> <!-- End Main content -->
        </div>
    </div>

<?= $this->Html->script('jquery-filestyle.js') ?>
<?= $this->Html->script('simm.js') ?>
