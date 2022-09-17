<?= $this->element('header')?>

<?= $this->element('sidebar')?>

<div class="content-wrapper content-wrapper-user">

    <div class="container">
        <?= $this->Flash->render() ?>

        <?php if($user_role == 'supervisor'):  ?>
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
        <?php endif;?>


        <div class="card card-default color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <i class="fas fa-truck"></i>
                    Funciones del Simulador Integrado de Manejo de Maquinarias (SIMM)
                </h3>
            </div>
            <div class="card-body">

                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'LegislacionRegister', 'action' => 'verLegislaciones', '?' =>
                                        ['Accion' => 'Editar Museos', 'Categoria' => 'Museos', 'Tabla_name' => 'Museos',
                                            'id' => 'btn_remito']], ['class' => 'btn-simm btn-remito btn btn-default',
                                        'escape' => false, 'onmouseover' => 'showPopover()']) ?>
                            </div>
                            <div>
                                <p class="center text-color-navy">Remitos</p>
                            </div>
                        </div>
                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>

                                <?= $this->Html->link('',
                                    ['controller' => 'UsoMaquinaria', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-maquina btn btn-default', 'escape' => false]) ?>

                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Uso de Maquinarias</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'ArreglosMecanicos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-arreglos btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Arreglos Mecánicos</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">


                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">

                        <?php if($user_role == 'supervisor' or $user_role == 'admin'):  ?>
                            <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                                <div>
                                    <?= $this->Html->link('',
                                        ['controller' => 'LegislacionRegister', 'action' => 'verLegislaciones', '?' =>
                                            ['Accion' => 'Editar Museos', 'Categoria' => 'Museos', 'Tabla_name' => 'Museos',
                                                'id' => 'btn_remito']], ['class' => 'btn-simm btn-costos btn btn-default',
                                            'escape' => false, 'onmouseover' => 'showPopover()']) ?>
                                </div>
                                <div class="div_content">
                                    <p class="center text-color-navy">Análisis de Costos</p>
                                </div>
                            </div>

                        <?php endif;?>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'LegislacionRegister', 'action' => 'verLegislaciones', '?' =>
                                        ['Accion' => 'Editar Museos', 'Categoria' => 'Museos', 'Tabla_name' => 'Museos',
                                            'id' => 1]], ['class' => 'btn-simm btn-informes btn btn-default', 'escape' => false, 'target' => '_blank']) ?>
                            </div>
                            <div>
                                <p class="center text-color-navy">Informes</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'SystemsConfigurations', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-conf btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Configuración del Sistema</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


