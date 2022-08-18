<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('arreglos_mecanicos_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Arreglos Mecánicos
                </h3>
            </div>

            <div class="card-body table-responsive">
                <div>
                    <?= $this->Html->link($this->Html->tag('span', ' Nuevo Arreglo Mecánico', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                        ['controller' => 'ArreglosMecanicos', 'action' => 'add'], ['class' => 'btn bg-navy', 'escape' => false]) ?>
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
                        <th scope="col"><?= $this->Paginator->sort('ID') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Fecha') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('N° de Comprobante') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Máquina') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Mano de Obra') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Repuestos') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Total') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Grupo') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('Usuario') ?></th>
                        <th scope="col" class="actions"><?= __('Acciones') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <!-- Cargo los ultimos 10.000 registros no mas -->

                    <?php foreach ($arreglos as $arreglo): ?>
                        <tr>

                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-eye', 'aria-hidden' => 'true']),
                                    ['action' => 'view', $arreglo->idarreglos_mecanicos], ['class' => 'btn bg-navy', 'escape' => false, 'target' => '_blank']) ?>
                            </td>

                            <td class="dt-center"><?= h($arreglo->idarreglos_mecanicos) ?></td>
                            <td class="dt-center"><?= h($arreglo->fecha->format('d-m-Y')) ?></td>
                            <td class="dt-center"><?= h($arreglo->num_comprobante) ?></td>
                            <td class="dt-center"><?= h($arreglo->maquina->name) ?></td>
                            <td class="dt-center"><?= h($arreglo->mano_obra) ?></td>
                            <td class="dt-center"><?= h($arreglo->repuestos) ?></td>
                            <td class="dt-center"><?= h($arreglo->total) ?></td>
                            <td class="dt-center"><?= h($arreglo->worksgroup->name) ?></td>
                            <td class="dt-center"><?= h($arreglo->user->lastname . ' ' . $arreglo->user->firstname) ?></td>


                            <td class="actions" style="text-align: center">
                                <?= $this->Html->link($this->Html->tag('span', '', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                    ['action' => 'edit', $arreglo->idarreglos_mecanicos], ['class' => 'btn bg-purple', 'escape' => false]) ?>

                                <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'):  ?>
                                    <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                        ['action' => 'delete', $arreglo->idarreglos_mecanicos],
                                        ['confirm' => __('Eliminar {0}?', $arreglo->name), 'class' => 'btn btn-danger','escape' => false]) ?>
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
                        <h4 class="modal-title" id="tittle_modal_propietarios">Filtro de Arreglos Mecánicos</h4>

                        <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_filter"
                                onclick="closeModal(this)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                            <!-- Opciones de Filtro -->

                            <?= $this->Form->control('tipo', ['options' =>
                                ['Fecha' => 'Fecha', 'Grupo' => 'Grupo', 'Maquina' => 'Maquina',
                                    'Parcela' => 'Parcela', 'Usuario' => 'Usuario'],
                                'empty' => '(Elija una opción)', 'type' => 'select',
                                'class' => 'form-control', 'placeholder' => 'Jurisdicción',
                                'label' => 'Tipo:', 'onChange' => 'selectTypeFilter(this)', 'attr_emp' => $id_empresa]) ?>
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
                        <div class="modal-footer">
                            <button id="button_upload" type="button" class="btn-default btn bg-gray-light float-left"
                                    attr="modal_filter" onclick="closeModal(this)">Cancelar</button>
                            <button id="button_upload" type="button" class="btn-navy btn bg-navy float-right"
                                    attr="modal_filter" onclick="filterArreglos()">Aplicar</button>
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

