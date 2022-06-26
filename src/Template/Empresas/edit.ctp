<?= $this->Html->css('jquery-filestyle.css') ?>

<?= $this->element('header')?>



<div class="content-wrapper">
    <div class="container">

        <!-- Main content -->
        <div class="card color-palette-box">

            <?= $this->Flash->render() ?>

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-building"></i>
                    Editar Empresa
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <!-- MUESTRO LA IMAGEN DE PERFIL AQUÍ -->

                        <?php if($empresa->active == 0):  ?>
                            <div class="form-group" style="margin-top: 5px;">
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">¿Desea dar de Alta una Empresa?</h4>
                                    <p>Al dar de baja una empresa habilitará todas las funciones definidas a
                                        los Usuarios Registrados.</p>
                                    <hr>
                                </div>

                            </div>

                            <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true'])),
                                ['action' => 'activated', $empresa->idempresas],
                                ['confirm' => __('Dar de Alta la Empresa: {0}?', $empresa->name), 'class' => 'btn btn-success','escape' => false]) ?>
                        <?php else: ?>
                            <div class="form-group" style="margin-top: 5px;">
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">¿Desea dar Baja una Empresa?</h4>
                                    <p>Al dar de baja una empresa inhabilitará todas las funciones definidas a
                                        los Usuarios Registrados.</p>
                                    <hr>
                                </div>

                            </div>

                            <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-times', 'aria-hidden' => 'true'])),
                                ['action' => 'dactivated', $empresa->idempresas],
                                ['confirm' => __('Dar de Baja la Empresa: {0}?', $empresa->name), 'class' => 'btn btn-danger','escape' => false]) ?>
                        <?php endif;?>





                    </div>
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <!-- MUESTRO LA IMAGEN DE PERFIL AQUÍ -->

                        <div class="form-group login-form-gruop">
                            <?php if($empresa->logo == ''):  ?>
                                <?php echo $this->Html->image('logos/edificio.png', ["alt" => 'User Image' ,
                                    "class" => 'img-circle user-profile-preview']) ?>
                            <?php else: ?>
                                <?php echo $this->Html->image($empresa->logo , ["alt" => 'User Image' ,
                                    "class" => 'img-circle user-profile-preview', 'pathPrefix' => $empresa->folder]) ?>
                            <?php endif;?>
                        </div>

                        <?= $this->Form->create($empresa, ['enctype' => 'multipart/form-data']) ?>

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

                        <label for="title" class="cols-sm-2 control-label fw-bold">Seleccione una imágen: </label>
                        <div class="">

                            <input type="file" name="file" class="jfilestyle" data-inputSize="403px !important" accept="image/*">
                        </div>


                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['controller' => 'Empresas', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
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
