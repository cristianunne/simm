<?= $this->Html->css('jquery-filestyle.css') ?>

<?= $this->element('header')?>


<div class="content-wrapper">
    <div class="container">

            <!-- Main content -->
            <div class="card color-palette-box">

                <?= $this->Flash->render() ?>

                <div class="card-header bg-navy">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i>
                            Editar Mi Perfil
                        </h3>
                    </div>

                <div class="card-body">
                        <div class="register-box edit-box">
                            <div class="card">
                                <div class="widget-user-imag">
                                    <?php echo $this->Html->image('new-user.png', ["alt" => 'User Image' , "class" => 'img-size-90']) ?>
                                </div>

                                <div class="card-body register-card-body">
                                    <p class="login-box-msg text-color-navy">Editar</p>

                                    <?= $this->Form->create($user, ['enctype' => 'multipart/form-data']) ?>

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

                                    <!-- MUESTRO LA IMAGEN DE PERFIL AQUÍ -->

                                    <div class="form-group login-form-gruop">
                                        <?php if($user['photo'] == ''):  ?>
                                            <?php echo $this->Html->image('user.png', ["alt" => 'User Image' ,
                                                "class" => 'img-circle user-profile-preview']) ?>
                                        <?php else: ?>
                                            <?php echo $this->Html->image($user['photo'] , ["alt" => 'User Image' ,
                                                "class" => 'img-circle user-profile-preview', 'pathPrefix' => $user['folder']]) ?>
                                        <?php endif;?>
                                    </div>


                                    <div class="input-group mb-3">
                                        <label for="title" class="cols-sm-2 control-label fw-bold">Seleccione una imágen: </label>
                                        <?= $this->Form->control('file', ['type' => 'file', 'class' => 'jfilestyle', 'label' => false,
                                            'data-btnClass'=> 'btn-success', 'id' => 'btn_file',
                                            'accept' => ['image/*']]) ?>
                                    </div>




                                    <div class="form-group m-0">
                                        <div class="pull-right">
                                            <?= $this->Form->button("Editar",
                                                ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                                        </div>
                                        <div class="pull-left">
                                            <?= $this->Html->link("Salir", ['controller' => 'Pages', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                                        </div>

                                    </div>

                                    <?= $this->Form->end() ?>


                                </div>
                                <!-- /.form-box -->
                            </div><!-- /.card -->

                        </div>
                    </div>

            </div>
    </div>
</div>

<?= $this->Html->script('jquery-filestyle.js') ?>
