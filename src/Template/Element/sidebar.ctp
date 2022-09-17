<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light elevation-4">
    <!-- TRaigo los datos de la sesion-->

    <?php
        $session = $this->request->getSession();
        $empresa = $session->read('Auth.User.Empresa');
    ?>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <?php if(!empty($empresa)) :  ?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
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
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info" style="white-space: normal;">
                <p class="d-block text-color-navy" style="margin-bottom: unset;"><small>Email: <?= h($empresa->email) ?></small></p>
                <p class="d-block text-color-navy" style="margin-bottom: unset;"><small>Dirección: <?= h($empresa->address) ?></small></p>
            </div>
        </div>

        <?php endif;?>

        <!-- Sidebar Menu -->



        <nav class="mt-2">

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

            <?php if(!empty($empresa)) :  ?>
                <ul class="nav nav-pills nav-sidebar flex-column" id="prueba_padre">
                    <li class="nav-item">
                        <?=  $this->Html->link(
                            '<i class="nav-icon fas fa-tachometer-alt"></i> Inicio Empresa',
                            ['controller' => 'Pages', 'action' => 'indexUser', $empresa->idempresas], ['class' => 'nav-link', 'escape' => false, 'id' => 'nav-icon-inicio']) ?>
                    </li>
                </ul>
            <?php endif;?>




            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

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
                                '<i class="far fa-circle nav-icon"></i> Inicio',
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
                                '<i class="far fa-circle nav-icon"></i> Inicio',
                                ['controller' => 'ArreglosMecanicos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-arreglos_mecanicos-Inicio']) ?>
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
                                '<i class="far fa-circle nav-icon"></i> Inicio',
                                ['controller' => 'SystemsConfigurations', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-inicio']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Centros de Costos',
                                ['controller' => 'CentrosCostos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Centros_costos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Constantes',
                                ['controller' => 'Constantes', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Constantes']) ?>
                        </li>


                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Destinos',
                                ['controller' => 'Destinos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Destinos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Grupos de Trabajo',
                                ['controller' => 'Worksgroups', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Worksgroups']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Lotes',
                                ['controller' => 'Lotes', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Lotes']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Maquinas',
                                ['controller' => 'Maquinas', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Maquinas']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Metodología de Costos',
                                ['controller' => 'MetodCostos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-MetodCostos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Operarios',
                                ['controller' => 'Operarios', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Operarios']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Parcelas',
                                ['controller' => 'Parcelas', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Parcelas']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Precios por Destinos',
                                ['controller' => 'DestinosProductos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-DestinosProductos']) ?>
                        </li>

                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Productos',
                                ['controller' => 'Productos', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Productos']) ?>
                        </li>


                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Propietarios',
                                ['controller' => 'Propietarios', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false,
                                'id' => 'nav-icon-system-Propietarios']) ?>
                        </li>


                        <li class="nav-item">
                            <?=  $this->Html->link(
                                '<i class="far fa-circle nav-icon"></i> Salarios',
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
