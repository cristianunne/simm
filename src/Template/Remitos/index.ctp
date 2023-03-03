<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container" style="max-width: 1500px;">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('remitos_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Remitos
                </h3>
            </div>

            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Nuevo Remito', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'Remitos', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
                </div>
                <hr>
                <br>


                <div class="row" style="margin-bottom: 1.2rem">
                    <div class="col-md-12">
                        <?= $this->Html->link($this->Html->tag('span', ' Remover Filtro', ['class' => 'fas fa-trash', 'aria-hidden' => 'true']),
                            ['action' => 'index'], ['class' => 'btn bg-red float-right', 'escape' => false]) ?>

                        <?= $this->Form->button($this->Html->tag('span', ' Filtrar', ['class' => 'fas fa-filter', 'aria-hidden' => 'true']),
                            ['type' => 'button', 'class' => 'btn bg-yellow float-right btn-filter', 'escape' => false, 'onclick' => 'showModalFilter()',
                                'id' => 'btn-filter']) ?>
                    </div>
                </div>

                <table id="tabladata" class="table table-bordered table-hover dataTable">
                    <thead>
                    <tr>
                        <th scope="col" class="actions"></th>
                        <th scope="col"><?= $this->Paginator->sort('N°') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Grupo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Lote') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Parcela') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Propietario') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Producto') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Toneladas') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Precio') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Destino') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Maquinas') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <!-- Cargo los ultimos 10.000 registros no mas -->

                    <?php foreach ($remitos as $rem): ?>
                        <tr>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['action' => 'view', $rem->idremitos], ['class' => 'btn bg-navy', 'escape' => false, 'target' => '_blank']) ?>
                            </td>

                            <td class="dt-center"><?= h($rem->remito_number) ?></td>
                            <td class="dt-center"><?= h($rem->fecha->format('d-m-Y')) ?></td>
                            <td class="dt-center"><?= h($rem->worksgroup->name) ?></td>

                            <!-- NO deben estar vacios -->

                            <?php if (!is_null($rem->lote)): ?>
                                <td class="dt-center"><?= h($rem->lote->name) ?></td>
                            <?php else: ?>
                                <td class="dt-center"></td>
                            <?php endif; ?>

                            <?php if (!is_null($rem->parcela)): ?>
                                <td class="dt-center"><?= h($rem->parcela->name) ?></td>
                            <?php else: ?>
                                <td class="dt-center"></td>
                            <?php endif; ?>



                            <!-- COnsulto por el tipo de propietario -->
                            <?php if ($rem->propietario->tipo == 'Empresa'): ?>
                                <td class="dt-center"><?= h($rem->propietario->name) ?></td>
                            <?php else: ?>
                                <td class="dt-center"><?= h($rem->propietario->firstname . ' ' . $rem->propietario->lastname) ?></td>
                            <?php endif; ?>


                            <td class="dt-center"><?= h($rem->producto->name) ?></td>
                            <td class="dt-center"><?= h($rem->ton) ?></td>
                            <td class="dt-center"><?= h($rem->precio_ton) ?></td>
                            <td class="dt-center"><?= h($rem->destino->name) ?></td>

                            <td class="dt-center"><?= h($rem->user->lastname . ' ' . $rem->user->firstname) ?></td>

                            <td class="dt-center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-truck', 'aria-hidden' => 'true']),
                                    ['action' => 'AddMaquinas', $rem->idremitos], ['class' => 'btn bg-green', 'escape' => false]) ?>
                            </td>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $rem->idremitos], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'remove', $rem->idremitos],
                                        ['confirm' => __('Eliminar {0}?', $rem->remito_number), 'class' => 'btn btn-danger','escape' => false]) ?>
                                <?php endif;?>

                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>   <!-- MAIN CONTENT -->


        <!-- Modal -->
        <div class="modal fade" id="modal_filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h4 class="modal-title" id="tittle_modal_propietarios">Filtro de Remitos</h4>

                        <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_filter"
                                onclick="closeModal(this)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                            <!-- Opciones de Filtro -->

                            <?= $this->Form->control('tipo', ['options' =>
                                ['Fecha' => 'Fecha', 'Grupo' => 'Grupo', 'Maquina' => 'Maquina', 'Lote' => 'Lote',
                                    'Parcela' => 'Parcela', 'Propietario' => 'Propietario', 'Producto' => 'Producto',
                                    'Destino' => 'Destino',
                                    'Usuario' => 'Usuario'],
                                'empty' => '(Elija una opción)', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Jurisdicción',
                                'label' => 'Tipo:', 'onChange' => 'selectTypeFilterRemitos(this)', 'attr_emp' => $id_empresa]) ?>
                        </div>
                        <hr>
                        <!-- DIV para cargar las opciones -->
                        <div id="div-filter">

                            <div id="div-filter-fecha" style="display: none;">
                                <div class="form-controls-modal" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_desde', 'Desde: ') ?>

                                    <div class="input-append date">
                                        <input id="fecha_desde" name="fecha_desde" type="date" class="span2">
                                    </div>

                                </div>

                                <div class="form-controls-modal" id="sandbox-container">
                                    <?=  $this->Form->label('fecha_hasta', 'Hasta: ') ?>

                                    <div class="input-append date">
                                        <input id="fecha_hasta" name="fecha_hasta" type="date" class="span2">
                                    </div>

                                </div>
                            </div>

                            <div id="div-filter-grupo" style="display: none;">

                                <?= $this->Form->control('grupos', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Grupos:', 'id' => 'groups_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'groups_alldata_modal']) ?>


                            </div>

                            <div id="div-filter-maquinas" style="display: none;">

                                <?= $this->Form->control('maquinas', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Máquinas:', 'id' => 'maquinas_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'maquinas_alldata_modal']) ?>


                            </div>

                            <div id="div-filter-lote" style="display: none;">


                                <?= $this->Form->control('lotes', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Lotes:', 'id' => 'lotes_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'lotes_alldata_modal']) ?>

                            </div>

                            <div id="div-filter-parcelas" style="display: none;">

                                <?= $this->Form->control('parcelas', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Parcelas:', 'id' => 'parcelas_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'parcelas_alldata_modal']) ?>

                            </div>


                            <div id="div-filter-propietarios" style="display: none;">

                                <?= $this->Form->control('propietarios_', ['options' => ['Empresa' => 'Empresa', 'Persona' => 'Persona'],
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Tipo de Propietario:', 'id' => 'prop_tipo_filter', 'attr_emp' => $id_empresa,
                                    'onChange' => 'getPropietarios(this)']) ?>

                                <br>

                                <?= $this->Form->control('propietarios', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Propietario:', 'id' => 'prop_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'propietarios_alldata_modal']) ?>

                            </div>

                            <div id="div-filter-productos" style="display: none;">

                                <?= $this->Form->control('productos', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Producto:', 'id' => 'productos_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'productos_alldata_modal']) ?>

                            </div>


                            <div id="div-filter-destinos" style="display: none;">

                                <?= $this->Form->control('destinos', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Destino:', 'id' => 'destinos_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'destinos_alldata_modal']) ?>

                            </div>





                            <div id="div-filter-usuarios" style="display: none;">

                                <?= $this->Form->control('usuarios', ['options' => null,
                                    'empty' => '(Elija una opción)', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => 'Usuarios:', 'id' => 'usuarios_modal']) ?>

                                <br>

                                <?= $this->Form->control('all_data', ['options' => ['NO' => 'NO', 'SI' => 'SI'],
                                    'empty' => '', 'type' => 'select',
                                    'class' => 'form-control', 'placeholder' => '',
                                    'label' => '¿Desea obtener todos los registros históricos?:', 'id' => 'usuarios_alldata_modal']) ?>
                            </div>

                        </div>
                        <br>
                        <br>
                        <div id="content_loading" style="margin-left: 41%; display: none;">
                            <?php echo $this->Html->image('loading.svg' , ["alt" => 'loading' ,
                                "class" => 'rounded float-left', 'id' => 'img_loading']) ?>
                        </div>

                        <br>
                        <br>
                        <div class="modal-footer">
                            <button id="button_upload" type="button" class="btn-default btn bg-gray-light float-left"
                                    attr="modal_filter" onclick="closeModal(this)">Cancelar</button>

                            <button id="button_upload" type="button" class="btn-navy btn bg-navy float-right"
                                    attr="modal_filter" onclick="filterRemitos()">Aplicar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?= $this->Html->script('../plugins/datatables/jquery.dataTables.min.js') ?>
<?= $this->Html->script('../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.html5.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.print.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.colVis.min.js') ?>

<script>
    $(function () {
        $('#tabladata').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        });

        $('#tabladata_2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        })
    })
</script>
