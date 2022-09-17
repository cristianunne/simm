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
                                <!-- /.widget-user-image -->

                                <div class="widget-user-image">

                                    <?php echo $this->Html->image('lote_navy.png', ["alt" => 'User Image' , "class" => 'img-circle elevation-2',
                                            'pathPrefix' => '/webroot/img/icons/']) ?>
                                </div>

                                <h6 class="widget-user-desc"><?= h($lotes->name) ?></h6>
                                <h6 class="widget-user-desc"><?= h('Tipo: '. $lotes->provincia) ?></h6>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" style="color: navy;">
                                            Parcelas <span class="float-right badge bg-primary">31</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="pull-left" style="margin-top: 35px; margin-bottom: 20px; margin-left: 10px;">
                                <?= $this->Html->link("Volver", ['controller' => 'Lotes', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
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

