<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light elevation-4" style="position: fixed !important; background-color: white;">
    <!-- TRaigo los datos de la sesion-->

    <?php
        $session = $this->request->getSession();
        $empresa = $session->read('Auth.User.Empresa');
        $user_role = $session->read('Auth.User.role');

    ?>


    <!-- Sidebar -->
    <div class="sidebar" style="position: fixed;">
        <!-- Sidebar user panel (optional) -->
        <?php if(!empty($empresa)) :  ?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="width: 227px !important;">
            <div class="image">

                    <?php if($empresa->logo == '') :  ?>
                        <?php echo $this->Html->image('logos/edificio.png', ["alt" => 'Market Image' ,
                                "class" => 'img-circle img-empresa']) ?>
                    <?php else: ?>
                       <?php echo $this->Html->image($empresa->logo , ["alt" => 'Market Image' ,
                                "class" => 'img-circle img-empresa', 'pathPrefix' => $empresa->folder]) ?>
                    <?php endif;?>

            </div>
            <div class="info" style="white-space: normal;">
                <a href="#" class="d-block"><?= h($empresa->name) ?></a>
            </div>

        </div>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="width: 227px !important;">
            <div class="info" style="white-space: normal;">
                <p class="d-block text-color-navy" style="margin-bottom: unset;"><small>Email: <?= h($empresa->email) ?></small></p>
                <p class="d-block text-color-navy" style="margin-bottom: unset;"><small>Dirección: <?= h($empresa->address) ?></small></p>
            </div>
        </div>

        <?php endif;?>

        <!-- Sidebar Menu -->



        <nav class="mt-2" style="font-family: none !important; width: 227px !important;">

            <label type="text" style="display: none !important;" id="seccion" attr=" <?=$seccion ?>"> </label>
            <label type="text" style="display: none !important;" id="subseccion" attr=" <?=$sub_seccion ?>"></label>
            <ul class="nav nav-pills nav-sidebar flex-column" id="prueba_padre">
                <li class="nav-item">
                    <?=  $this->Html->link(
                        '<i class="nav-icon fas fa-tachometer-alt"></i> Inicio',
                        ['controller' => 'Pages', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false, 'id' => 'nav-icon-inicio']) ?>
                </li>
            </ul>

            <!-- INICIO DE EMPRESA-->
            <?php if($user_role == 'admin') :  ?>

                <?php if(!empty($empresa)) :  ?>
                    <ul class="nav nav-pills nav-sidebar flex-column" id="prueba_padre">
                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="nav-icon fas fa-building"></i> Inicio Empresa',
                                ['controller' => 'Pages', 'action' => 'indexUser', $empresa->idempresas],
                                ['class' => 'nav-link', 'escape' => false, 'id' => 'nav-icon-inicio_emp']) ?>
                        </li>
                    </ul>
                <?php endif;?>
            <?php endif;?>




            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


                <li class="nav-item menu-close" id="remitos">

                    <a href="#" class="nav-link" id="title-remitos">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Remitos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Inicio',
                                ['controller' => 'Remitos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-remitos-Inicio']) ?>
                        </li>

                    </ul>
                </li>



                <li class="nav-item menu-close" id="uso_maquinaria">

                    <a href="#" class="nav-link" id="title-uso_maquinaria">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>
                            Uso de Maquinaria
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Inicio',
                                ['controller' => 'UsoMaquinaria', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-uso_maquinaria-Inicio']) ?>
                        </li>

                    </ul>
                </li>


                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item menu-close" id="arreglos_mecanicos">

                    <a href="#" class="nav-link" id="title-arreglos_mecanicos">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Arreglos Mecánicos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Inicio',
                                ['controller' => 'ArreglosMecanicos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-arreglos_mecanicos-Inicio']) ?>

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Informe por Maquina',
                                ['controller' => 'ArreglosMecanicos', 'action' => 'resumenArreglosByMaquina'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-arreglos_mecanicos-Resumen']) ?>
                        </li>

                    </ul>
                </li>


                <li class="nav-item menu-close" id="analisis_costos">

                    <a href="#" class="nav-link" id="title-analisis_costos">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            Análisis de Costos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Inicio',
                                ['controller' => 'AnalisisCostos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-analisis_costos-Inicio']) ?>

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Análisis Grupos',
                                ['controller' => 'AnalisisCostos', 'action' => 'groupsCostosAnalysis'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-analisis_costos-Grupos_costos']) ?>

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Análisis Máquinas',
                                ['controller' => 'AnalisisCostosMaquinas', 'action' => 'calculateCostosMaquina'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-analisis_costos-Analisis_maquinas']) ?>

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Variaciones',
                                ['controller' => 'Variaciones', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-analisis_costos-Variaciones']) ?>
                        </li>

                    </ul>
                </li>


                <li class="nav-item menu-close" id="informes">

                    <a href="#" class="nav-link" id="title-Informes">
                        <i class="nav-icon fas fa-file-archive"></i>
                        <p>
                            Informes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Inicio',
                                ['controller' => 'Informes', 'action' => 'view'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-Informes-Inicio']) ?>

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Destinos y Propietarios',
                                ['controller' => 'InformesResumen', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-Informes-Destino']) ?>

                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Camiones Rentados',
                                ['controller' => 'InformesResumen', 'action' => 'camionesReport'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-Informes-Camiones']) ?>

                        </li>

                    </ul>
                </li>





                <li class="nav-item menu-close" id="configuracion">

                    <a href="#" class="nav-link" id="title-system">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Configuración
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Inicio',
                                ['controller' => 'SystemsConfigurations', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-inicio']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Centros de Costos',
                                ['controller' => 'CentrosCostos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Centros_costos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Constantes',
                                ['controller' => 'Constantes', 'action' => 'view'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Constantes']) ?>
                        </li>


                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Destinos',
                                ['controller' => 'Destinos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Destinos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Grupos de Trabajo',
                                ['controller' => 'Worksgroups', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Worksgroups']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Lotes',
                                ['controller' => 'Lotes', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Lotes']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Maquinas',
                                ['controller' => 'Maquinas', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Maquinas']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Metodología de Costos',
                                ['controller' => 'MetodCostos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-MetodCostos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Operarios',
                                ['controller' => 'Operarios', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Operarios']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Parcelas',
                                ['controller' => 'Parcelas', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Parcelas']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Precios por Destinos',
                                ['controller' => 'DestinosProductos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-DestinosProductos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Productos',
                                ['controller' => 'Productos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Productos']) ?>
                        </li>


                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Propietarios',
                                ['controller' => 'Propietarios', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Propietarios']) ?>
                        </li>


                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="fas fa-greater-than nav-icon"></i> Salarios',
                                ['controller' => 'OperariosMaquinas', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-OperariosMaquinas']) ?>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<?= $this->Html->script('simm.js') ?>
