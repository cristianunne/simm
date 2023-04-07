

<div class="content-wrapper content-wrapper-other">

    <!-- Main content -->
    <div class="content login-page">
        <?= $this->Flash->render() ?>
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card">
                <div class="widget-user-imag">
                    <?php echo $this->Html->image('user.png', ["alt" => 'User Image' ,
                        "class" => 'img-circle img-size-90']) ?>

                </div>

                <div class="card-body login-card-body">
                    <p class="login-box-msg text-color-navy">Iniciar sesión</p>

                    <?= $this->Form->create(null, []) ?>
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
                            'label' => false, 'required']) ?>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-0">
                        <?= $this->Form->button('Iniciar sesión', ['class' => 'btn btn-large bg-navy btn-block']) ?>
                    </div>

                    <?= $this->Form->end() ?>


                </div>
                <!-- /.login-card-body -->
                <div class="footer-login">
                    <p class="text-color-navy-light">Copyright &copy; 2022 &mdash; Sistema Integrado de Manejo de Maquinarias (SIMM)</p>
                </div>

            </div>
        </div>
        <!-- /.login-box -->

    </div>

</div>

