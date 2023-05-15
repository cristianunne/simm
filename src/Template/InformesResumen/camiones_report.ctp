<?= $this->Html->css('jquery-confirm.min.css') ?>

<?= $this->element('header')?>
<?= $this->element('sidebar')?>


<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('group_work_navy.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                   Resumen de Camiones Rentados
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <?= $this->Form->create(null) ?>
                        <p class="title-box-ac">Período</p>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_inicio', 'Inicio: ', ['class' => 'label-m10']) ?>
                                    <div class="input-append date" style="margin-left: 1px;">
                                        <input id="fecha_inicio" name="fecha_inicio" type="date" class="span2" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group sandbox-container" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_final', 'Final: ', ['class' => 'label-m10 width-45px']) ?>
                                    <div class="input-append date">
                                        <input id="fecha_final" name="fecha_final" type="date" class="span2" required>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <br>
                        <div class="form-group" style="margin-top: 40px;">

                            <div class="pull-right" id="div_accept_btn"">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['controller' => 'Informes', 'action' => 'view'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                    </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
