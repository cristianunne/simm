<?= $this->element('header')?>
<?= $this->element('sidebar')?>
<div class="content-wrapper">
    <div class="container">

        <!-- Main content -->
        <div class="card color-palette-box">

            <?= $this->Flash->render() ?>

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('precio_destino_navy.png' , ["alt" => 'User Image' ,
                        "class" => 'img img-header-panel', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Carga de Precio por Destino
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                        <?= $this->Form->create($destino_producto, ['']) ?>

                        <div class="form-group" id="group-firstname">
                            <?=  $this->Form->label('Destino: ') ?>
                            <?= $this->Form->text('name_destino', ['class' => 'form-control', 'value' => $destino->name, 'readOnly' => true]) ?>
                        </div>

                        <?= $this->Form->control('productos_idproductos', ['options' => $lista_productos,
                            'empty' => '(Elija una opción)', 'type' => 'select',
                            'class' => 'form-control', 'placeholder' => '',
                            'label' => 'Producto:']) ?>
                        <br>

                        <div class="form-group" id="group-dni" >

                            <?= $this->Form->input('precio', ['class' => 'form-control', 'type' => 'number',
                                'label' => 'Precio: ', 'required']) ?>

                        </div>

                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false]) ?>

                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['controller' => 'DestinosProductos', 'action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>

                        </div>


                        <?= $this->Form->end() ?>
                    </div>

                </div>
            </div>

        </div> <!-- End Main content -->
    </div>
</div>

<?= $this->Html->script('simm.js') ?>
