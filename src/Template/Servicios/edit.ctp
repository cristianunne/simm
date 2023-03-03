<?= $this->Html->css('jquery-filestyle.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('destino_white.png' , ["alt" => 'User Image' ,
                        "class" => 'img img-header-panel', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Nuevo Servicio
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">
                        <?= $this->Form->create($servicios, ['enctype' => 'multipart/form-data']) ?>

                        <div class="form-group" id="sandbox-container">
                            <?=  $this->Form->label('fecha', 'Fecha: ') ?>
                            <div class="input-append date">
                                <input id="fecha" name="fecha" type="date" class="span2" required value="<?= h($servicios->fecha->format('Y-m-d')) ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <?= $this->Form->input('precio', ['class' => 'form-control', 'type' => 'number', 'label' => 'Precio ($): ', 'required']) ?>
                        </div>

                        <div class="form-group">
                            <?= $this->Form->control('categoria', ['options' => $categorias,
                                'empty' => '(Elija una Categoria)', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Categoria',
                                'label' => 'Categoria:']) ?>
                        </div>


                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['controller' => 'Servicios', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>

                        <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->Html->script('jquery-filestyle.js') ?>
