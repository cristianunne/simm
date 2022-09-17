


<div class="content-wrapper content-wrapper-other">
    <!-- Main content -->
    <div class="content login-page bg-navy">
        <div class="register-box">
            <div class="card">

                <div class="widget-user-imag">
                    <?php echo $this->Html->image('new-user.png', ["alt" => 'User Image' , "class" => 'img-size-90']) ?>
                </div>

                <div class="card-body register-card-body">
                    <p class="login-box-msg text-color-navy">Registro de Nuevo Usuario</p>

                    <?= $this->Form->create($user, []) ?>

                    <div class="input-group mb-3">
                        <?= $this->Form->text('firstname', ['class' => 'form-control', 'placeholder' => 'Nombre/s',
                            'label' => false, 'required']) ?>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <?= $this->Form->text('lastname', ['class' => 'form-control', 'placeholder' => 'Apellido/s',
                            'label' => false, 'required']) ?>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>


                    <div class="input-group mb-3">
                        <?= $this->Form->text('email', ['type' => 'email', 'class' => 'form-control', 'placeholder' => 'Email',
                            'label' => false, 'required']) ?>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

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

                    <div class="input-group mb-3">
                        <?= $this->Form->password('password_repeat', ['class' => 'form-control', 'placeholder' => 'Repita Contraseña',
                            'label' => false, 'required', 'oninput' => 'passwordValidate()', 'id' => 'password_repeat']) ?>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-0">
                        <?= $this->Form->button('Registrarse', ['class' => 'btn btn-large bg-navy btn-block', 'disabled' => 'disabled',
                            'id' => 'boton_submit']) ?>
                    </div>

                    <?= $this->Form->end() ?>



                    <div class="footer-login">
                        <p class="text-color-navy-light">Copyright &copy; 2022 &mdash; Sistema Integrado de Manejo de Maquinarias (SIMM)</p>
                    </div>

                </div>
                <!-- /.form-box -->
            </div><!-- /.card -->

        </div>

    </div>
</div>

<?= $this->Html->script('login/my-login.js') ?>





