<?= $this->element('header')?>

<?= $this->element('sidebar')?>

<div class="content-wrapper content-wrapper-user">

    <div class="container">
        <?= $this->Flash->render() ?>


        <div class="card card-default color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-truck"></i>
                    Administración de Constantes
                </h3>
            </div>
            <div class="card-body">

                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'ListaConstantes', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-lista-const btn btn-default',
                                        'escape' => false, 'onmouseover' => 'showPopover()']) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Lista de Constantes</p>
                            </div>
                        </div>
                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Constantes', 'action' => 'view'],
                                    ['class' => 'btn-simm btn-constantes btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Constantes</p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>

</div>
