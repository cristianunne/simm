


<?= $this->element('header')?>

<div class="content-wrapper content-wrapper-admin">

    <div class="container">

        <div class="card color-palette-box">

            <?= $this->Flash->render() ?>

            <div class="card-header bg-indigo">
                <h3 class="card-title">
                    <i class="fas fa-user-shield"></i>
                    Funciones Generales de Administración
                </h3>
            </div>
            <div class="card-body">
                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Empresas', 'action' => 'index'], ['class' => 'btn-simm btn-empresa btn btn-default',
                                        'escape' => false]) ?>
                            </div>
                            <div>
                                <p class="center text-color-navy">Empresas</p>
                            </div>
                        </div>
                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'UsersAdministration', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-users btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div>
                                <p class="center text-color-navy">Usuarios</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>




    </div>

</div>

<?= $this->Html->script('../plugins/popper/umd/popper.min.js') ?>



