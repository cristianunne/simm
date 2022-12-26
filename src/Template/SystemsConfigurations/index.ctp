<?= $this->element('header')?>

<?= $this->element('sidebar')?>

<div class="content-wrapper content-wrapper-user">

    <div class="container">

        <div class="card card-default color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-cog"></i>
                    Configuración del Sistema
                </h3>
            </div>
            <div class="card-body">

                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Destinos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-destinos btn btn-default',
                                        'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Destinos</p>
                            </div>
                        </div>
                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Worksgroups', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-groupswork btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Grupos de Trabajos</p>
                            </div>
                        </div>
                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'CentrosCostos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-centro_costos btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Centro de Costos</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Lotes', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-lotes btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Lotes</p>
                            </div>
                        </div>


                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Parcelas', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-parcela btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Parcelas</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Productos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-productos btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Productos</p>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">


                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Maquinas', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-maquina btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Maquinarias</p>
                            </div>
                        </div>


                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Operarios', 'action' => 'index']
                                    ,['class' => 'btn-simm btn-operarios btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Operarios</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'Propietarios', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-propietarios btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Propietarios</p>
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


                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'MetodCostos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-met-costos btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Metodología de Costos</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'OperariosMaquinas', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-salarios btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Salarios</p>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'DestinosProductos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-precio-destino btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Precios por Destinos</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'SystemsConfigurations', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-precio-contratista btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Precios por Contratistas</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

