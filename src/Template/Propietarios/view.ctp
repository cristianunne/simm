
<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">


            <div class="card-body">

                <div class="row">
                    <div class="col-md-4" style="display: block; margin: 0 auto;">
                        <!-- Widget: user widget style 2 -->
                        <div class="card card-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-gradient-navy">
                                <div class="widget-user-image">

                                    <?php if($propietarios->logo == ''):  ?>

                                        <?php echo $this->Html->image('propietario_navy.png', ["alt" => 'User Image' , "class" => 'img-circle elevation-2',
                                            'pathPrefix' => '/webroot/img/icons/']) ?>

                                    <?php else: ?>
                                        <?php echo $this->Html->image($propietarios->logo, ["alt" => 'User Image' , "class" => 'img-circle elevation-2',
                                            'pathPrefix' => $propietarios->folder]) ?>
                                    <?php endif;?>

                                </div>
                                <!-- /.widget-user-image -->
                                <?php if($propietarios->tipo == 'Persona'):  ?>
                                    <h3 class="widget-user-username"><?= h($propietarios->firstname . ' ' . $propietarios->lastname) ?></h3>
                                <?php else: ?>
                                    <h3 class="widget-user-username"><?= h($propietarios->name) ?></h3>
                                <?php endif;?>
                                <h6 class="widget-user-desc"><?= h($propietarios->email) ?></h6>
                                <h6 class="widget-user-desc"><?= h('Tipo: '. $propietarios->tipo) ?></h6>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" style="color: navy;">
                                            Parcelas <span class="float-right badge bg-primary">31</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" style="color: navy;">
                                            Lotes <span class="float-right badge bg-info">5</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="pull-left" style="margin-top: 35px; margin-bottom: 20px; margin-left: 10px;">
                                <?= $this->Html->link("Volver", ['controller' => 'Propietarios', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>

            </div>
        </div>
    </div>
</div>

