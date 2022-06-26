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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index2.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index3.html" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
