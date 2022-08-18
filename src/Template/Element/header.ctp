
<?php
$session = $this->request->getSession();
$id = $session->read('Auth.User.idusers');
$firstname = $session->read('Auth.User.firstname');
$lastname = $session->read('Auth.User.lastname');
$photo = $session->read('Auth.User.photo');
$folder = $session->read('Auth.User.folder');
$role = $session->read('Auth.User.role');
$email = $session->read('Auth.User.email');


?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand bg-navy">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/pages/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <?= $this->Html->image('icons/tractor_white.png', ['class' => 'img-logo']); ?>
                <span class="text-logo"><b>SIMM</b></span>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item" style="margin-right: 10px;">
        <small><?= $firstname; ?></small>
        </li>
        <div class="dropdown">

            <a href="#" class="d-block text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="true">
                <?php if($photo == ''):  ?>
                    <?php echo $this->Html->image('user.png', ["alt" => 'User Image' , "class" => 'img-circle user-profile']) ?>
                <?php else: ?>
                    <?php echo $this->Html->image($photo , ["alt" => 'User Image' ,
                        "class" => 'img-circle user-profile', 'pathPrefix' => $folder]) ?>
                <?php endif;?>
            </a>

            <ul class="dropdown-menu text-small shadow ul-user-log" aria-labelledby="dropdownUser2" data-popper-placement="bottom-end"
                style="position: absolute; inset: auto 0px 0px auto; margin: 0px; transform: translate(8px, 198px);">

                <li class="user-header">
                    <div>

                        <?php if($photo == ''):  ?>
                            <?php echo $this->Html->image('user.png', ["alt" => 'User Image' , "class" => 'img-circle user-image']) ?>
                        <?php else: ?>
                            <?php echo $this->Html->image($photo , ["alt" => 'User Image' ,
                                "class" => 'img-circle user-image', 'pathPrefix' => $folder]) ?>
                        <?php endif;?>

                    </div>
                    <p class="text-color-navy">
                        <small><b>Rol:</b> <?= $role; ?></small>
                    </b>
                    <p class="text-color-navy">
                        <small><b>Email:</b>  <?= $email; ?></small>
                    </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="pull-left">

                        <?= $this->Html->link("Perfil",
                            ['controller' => 'Users', 'action' => 'editProfile', $id],
                            ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                    </div>
                    <div class="pull-right">
                        <?= $this->Html->link("Salir", ['controller' => 'Users', 'action' => 'logout'], ['class' => 'btn btn-danger btn-flat']) ?>
                    </div>
                </li>


            </ul>
        </div>

    </ul>
</nav>
<!-- /.navbar -->
