


<?= $this->element('header')?>
<?= $this->element('sidebar')?>



<div class="content-wrapper content-wrapper-user">

    <div class="container">

        <div class="card color-palette-box">

            <?= $this->Flash->render() ?>

            <div class="card-header bg-indigo">
                <h3 class="card-title">
                    <i class="fas fa-user-shield"></i>
                    Informes
                </h3>
            </div>
            <div class="card-body">
                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'InformesResumen', 'action' => 'destinosReport'],
                                    ['class' => 'btn-simm btn-destinos btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Informes por Destinos</p>
                            </div>
                        </div>
                        <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'InformesResumen', 'action' => 'destinosReportIndex'], ['class' => 'btn-simm btn-remito btn btn-default',
                                        'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Ver Informes</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">

                    <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                        <div>
                            <?= $this->Html->link('',
                                ['controller' => 'InformesResumen', 'action' => 'propietariosReport'],
                                ['class' => 'btn-simm btn-propietarios btn btn-default', 'escape' => false]) ?>
                        </div>
                        <div class="div_content">
                            <p class="center text-color-navy">Informes por Propietarios</p>
                        </div>
                    </div>

                    <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                        <div>
                            <?= $this->Html->link('',
                                ['controller' => 'InformesMaquinas', 'action' => 'index'], ['class' => 'btn-simm btn-remito btn btn-default',
                                    'escape' => false]) ?>
                        </div>
                        <div class="div_content">
                            <p class="center text-color-navy">Ver Informes</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>



    </div>

</div>
