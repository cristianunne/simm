<?= $this->element('header')?>


<?= $this->element('sidebar')?>

<div class="content-wrapper">


    <div class="container">

        <!-- Main content -->
        <div class="card color-palette-box">

            <?= $this->Flash->render() ?>

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-user-alt"></i>
                    Nuevo Usuario
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                        <?= $this->Form->create($user, []) ?>

                        <div class="form-group">
                            <?=  $this->Form->label('Nombre: ') ?>
                            <?= $this->Form->text('firstname', ['class' => 'form-control', 'placeholder' => 'Nombre', 'required']) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Apellido: ') ?>
                            <?= $this->Form->text('lastname', ['class' => 'form-control', 'placeholder' => 'Dirección', 'required']) ?>
                        </div>
                        <?=  $this->Form->label('Email: ') ?>
                        <div class="input-group">

                            <?= $this->Form->text('email', ['type' => 'email', 'class' => 'form-control', 'placeholder' => 'Email',
                                'label' => false, 'required', 'errors' => true]) ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <?= $this->Form->error('email')?>

                        <br>
                        <?=  $this->Form->label('Contraseña: ') ?>
                        <div class="input-group mb-3">
                            <?= $this->Form->password('password', ['class' => 'form-control', 'placeholder' => 'Contraseña',
                                'label' => false, 'required', 'id' => 'password',
                                'oninput' => 'passwordValidate()']) ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <?=  $this->Form->label('Repita Contraseña: ') ?>
                        <div class="input-group mb-3">
                            <?= $this->Form->password('password_repeat', ['class' => 'form-control', 'placeholder' => 'Repita Contraseña',
                                'label' => false, 'required', 'oninput' => 'passwordValidate()', 'id' => 'password_repeat']) ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('Rol: ') ?>
                            <?= $this->Form->input('role', ['options' => $type_users, 'empty' => '(Sin rol)', 'type' => 'select',
                                'class' => 'form-control', 'label' => false]) ?>
                        </div>

                        <div class="form-group">
                            <?=  $this->Form->label('¿Activo?: ') ?>
                            <?= $this->Form->input('active', ['options' => ['1' => 'SI', '0' => 'NO'], 'empty' => '(¿Activo?)', 'type' => 'select',
                                'class' => 'form-control', 'label' => false, 'selected' => false]) ?>
                        </div>

                        <?php if($user_role == 'admin'):  ?>

                            <div class="form-group">
                                <?=  $this->Form->label('Empresa: ') ?>
                                <?= $this->Form->input('empresas_idempresas', ['options' => $empresas, 'empty' => '(Sin Empresa)', 'type' => 'select',
                                    'class' => 'form-control', 'label' => false]) ?>
                            </div>

                        <?php endif;?>

                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false,
                                    'disabled' => 'disabled',
                                    'id' => 'boton_submit']) ?>

                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['controller' => 'UsersAdministration', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>

                        </div>


                        <?= $this->Form->end() ?>
                    </br>

                </div>
            </div>

        </div> <!-- End Main content -->
    </div>
</div>

<?= $this->Html->script('login/my-login.js') ?>
