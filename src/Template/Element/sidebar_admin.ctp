<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light elevation-4" style="position: fixed !important; background-color: white;">
    <!-- TRaigo los datos de la sesion-->

    <?php
    $session = $this->request->getSession();
    $empresa = $session->read('Auth.User.Empresa');
    $user_role = $session->read('Auth.User.role');
    ?>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->


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


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<?= $this->Html->script('simm.js') ?>
