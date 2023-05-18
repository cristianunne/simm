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
                    <?php echo $this->Html->image('remitos_white.png' , ["alt" => 'User Image' ,
                        "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Nuevo Remito
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">


                        <div class="row">

                            <div class="col-md-3">

                                <div class="form-group">
                                    <?=  $this->Form->label('N° de Remito: ') ?>
                                    <!-- Necesito guardar el ID no el remito number -->
                                    <?= $this->Form->text('remito_number', ['value' => $remitos->remito_number,
                                        'class' => 'form-control', 'placeholder' => '', 'disabled', 'style' => ['text-align: right;'],
                                        'id' => 'remito_number', 'attr' => $remitos->idremitos]) ?>
                                </div>
                            </div>

                            <div class="col-md-6" style="margin-left: 50px;">

                                <div class="form-group">
                                    <?=  $this->Form->label('Hash de Remito: ') ?>
                                    <p>  <?=  h($remitos->hash_id) ?></p>

                                </div>
                            </div>

                            <hr style="width: 97%; margin-top: 25px;">

                        </div>      <!-- ROW MAIN -->


                        <div class="row">

                            <div class="col-md-3" style="border-right: solid 1px #c1c1c1;">
                                <br>

                                <hr style="margin-top: 25px;">

                                <div class="form-group" id="sandbox-container">
                                    <?=  $this->Form->label('fecha', 'Fecha: ') ?>
                                    <div class="input-append date">
                                        <input id="fecha" name="fecha" type="date"
                                               class="span2" value="<?= h($remitos->fecha->format('Y-m-d')) ?>" disabled>
                                    </div>

                                </div>



                                <div class="form-group">
                                    <?=  $this->Form->label('worksgroups_idworksgroups', 'Grupo de Trabajo: ') ?>
                                    <?= $this->Form->text('worksgroups_idworksgroups', ['value' => $remitos->worksgroup->name,
                                        'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => 'Grupo de Trabajo']) ?>
                                </div>

                                <div class="form-group">
                                    <?=  $this->Form->label('lotes_idlotes', 'Lote: ') ?>
                                    <?= $this->Form->text('lotes_idlotes', ['value' => $remitos->lote->name,
                                        'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => false]) ?>
                                </div>


                                <div class="form-group">
                                    <?=  $this->Form->label('parcelas_idparcelas', 'Parcelas: ') ?>

                                    <?php if (!is_null($remitos->parcela)): ?>
                                        <?= $this->Form->text('parcelas_idparcelas', ['value' => $remitos->parcela->name,
                                            'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => false]) ?>
                                    <?php else: ?>
                                        <?= $this->Form->text('parcelas_idparcelas', ['value' => '',
                                            'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => false]) ?>
                                    <?php endif; ?>


                                </div>

                                <?php  if($remitos->propietario->tipo == 'Empresa'): ?>

                                    <?=  $this->Form->label('propietarios_idpropietarios', 'Propietarios: ') ?>
                                    <?= $this->Form->text('propietario',
                                        ['value' => ($remitos->propietario->name),
                                            'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => false]) ?>

                                <?php  else: ?>

                                    <div class="form-group">
                                        <?=  $this->Form->label('propietarios_idpropietarios', 'Propietarios: ') ?>
                                        <?= $this->Form->text('propietario',
                                            ['value' => ($remitos->propietario->firstname . ' ' . $remitos->propietario->lastname),
                                                'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => false]) ?>
                                    </div>

                                 <?php  endif; ?>

                                <br>
                                <div class="form-group">
                                    <?=  $this->Form->label('destinos_iddestinos', 'Destinos: ') ?>
                                    <?= $this->Form->text('destinos_iddestinos',
                                        ['value' => $remitos->destino->name,
                                            'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => false]) ?>
                                </div>


                                <div class="form-group">
                                    <?=  $this->Form->label('productos_idproductos', 'Producto: ') ?>
                                    <?= $this->Form->text('productos_idproductos',
                                        ['value' => $remitos->producto->name,
                                            'class' => 'form-control', 'placeholder' => '', 'disabled', 'label' => false]) ?>
                                </div>

                                <div class="form-group">
                                    <?=  $this->Form->label('precio_ton', 'Precio ($/ton): ') ?>
                                    <?= $this->Form->number('precio_ton', ['class' => 'form-control', 'value' => $remitos->precio_ton,
                                        'label' => 'Precio ($/ton): ',  'disabled']) ?>
                                </div>

                            </div>


                            <?php $i = 1; ?>
                                <div class="col-md-6" style="border-right: solid 1px #c1c1c1;">
                                    <p style="display: block; text-align: center; font-weight: bold;">Maquinaria utilizada en el Remito</p>
                                    <hr style="margin-top: 25px;">
                                    <table id="tabladata" class="table table-bordered table-hover dataTable">
                                        <thead>
                                        <tr>

                                            <th scope="col"><?= $this->Paginator->sort('ID') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('Máquina') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('Operario') ?></th>
                                            <th scope="col"><?= $this->Paginator->sort('Grupo') ?></th>
                                            <th scope="col" class="actions"><?= __('Acciones') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($remitos_maq_data as $maq): ?>

                                            <tr>
                                                <td class="dt-center"><?= h($i) ?></td>
                                                <td class="dt-center"><?= h($maq->maquina->marca . ': ' . $maq->maquina->name) ?></td>
                                                <td class="dt-center"><?= h($maq->operario->firstname . ' ' . $maq->operario->lastname) ?></td>
                                                <td class="dt-center"><?= h($maq->maquina->costos_maquinas[0]->worksgroup->name) ?></td>
                                                <td class="actions" style="text-align: center">
                                                    <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'
                                                        or $current_user['idusers'] == $remitos->users_idusers):  ?>

                                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                                            ['action' => 'removeRemitoMaquina', $maq->idremitos_maquinas, $maq->remitos_idremitos],
                                                            ['confirm' => __('Eliminar {0}?', $maq->idremitos_maquinas),
                                                                'class' => 'btn btn-danger','escape' => false,
                                                                'id' => $maq->idremitos_maquinas]) ?>

                                                    <?php endif;?>
                                                </td>

                                            </tr>
                                            <?php $i = $i + 1; ?>
                                        <?php endforeach; ?>



                                        <?php foreach ($remitos_maq_data_alquilada as $maq): ?>

                                            <tr>
                                                <td class="dt-center"><?= h($i) ?></td>
                                                <td class="dt-center"><?= h($maq->maquina->marca . ': ' . $maq->maquina->name) ?></td>
                                                <td class="dt-center"></td>
                                                <td class="dt-center"><?= h($maq->maquina->costos_maquinas[0]->worksgroup->name) ?></td>
                                                <td class="actions" style="text-align: center">

                                                    <?php if($current_user['role'] == 'supervisor' or $current_user['role'] == 'admin'
                                                        or $current_user['idusers'] == $remitos->users_idusers):  ?>

                                                        <?= $this->Form->postLink(__($this->Html->tag('span', '', ['class' => 'fas fa-trash-alt', 'aria-hidden' => 'true'])),
                                                            ['action' => 'removeRemitoMaquina', $maq->idremitos_maquinas, $maq->remitos_idremitos],
                                                            ['confirm' => __('Eliminar {0}?', $maq->idremitos_maquinas),
                                                                'class' => 'btn btn-danger','escape' => false,
                                                                'id' => $maq->idremitos_maquinas]) ?>

                                                    <?php endif;?>
                                                </td>

                                            </tr>
                                            <?php $i = $i + 1; ?>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-3" style="border-right: solid 1px #c1c1c1;">
                                    <p style="display: block; text-align: center; font-weight: bold;">Selección de Máquinas</p>

                                    <hr style="margin-top: 25px;">

                                    <?= $this->Form->button($this->Html->tag('span', ' Máquina', ['class' => 'fas fa-plus', 'aria-hidden' => 'true']),
                                        ['type' => 'button', 'class' => 'btn bg-navy btn-remito-styles', 'escape' => false,
                                            'onclick' => 'showModalMaquinas()']) ?>
                                    <br>
                                    <hr style="width: 97%; margin-top: 25px;">
                                </div>
                        </div>

                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Maquinas-->
    <div class="modal fade" id="modal_maquinas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header bg-navy">
                    <h4 class="modal-title" id="tittle_modal_propietarios">Selección de Máquinas</h4>
                    <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_maquinas"
                            onclick="closeModal(this)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div id="div_tabladata">
                        <table id="tabladata_maq_modal" name="tabla_persona" class="table table-bordered table-hover dataTable">
                            <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('Maquina') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Operario') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Grupo') ?></th>
                                <th scope="col" class="actions"><?= __('Acciones') ?></th>
                            </tr>
                            </thead>
                            <tbody>


                            <?php foreach ($oper_maq_data as $maq): ?>

                                <!-- Hago la comprobacion de los datos teoricos cargados-->
                                <?php if (!empty($maq->maquina->costos_maquinas)): ?>
                                        <tr>
                                            <td class="dt-center"><?= h($maq->maquina->marca . ': ' . $maq->maquina->name) ?></td>
                                            <td class="dt-center"><?= h($maq->operario->firstname . ' ' . $maq->operario->lastname) ?></td>
                                            <td class="dt-center"><?=h($maq->maquina->costos_maquinas[0]->worksgroup->name) ?></td>
                                            <td class="actions" style="text-align: center">
                                                <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                                    ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                        'maquina' => $maq->maquina->marca . ': ' . $maq->maquina->name,
                                                        'operario' =>  $maq->operario->firstname . ' ' . $maq->operario->lastname,
                                                        'id_maq_op' => $maq->idoperarios_maquinas, 'id_maquina' => $maq->maquina->idmaquinas,
                                                        'id_operario' => $maq->operario->idoperarios ,
                                                        'onclick' => 'addMaquinaToRemito(this)']) ?>
                                            </td>

                                        </tr>
                                <?php endif;?>
                            <?php endforeach; ?>

                            <?php foreach ($maquinas_alquiladas as $maq): ?>

                                <tr>
                                    <td class="dt-center"><?= h($maq->marca . ': ' . $maq->name) ?></td>
                                    <td class="dt-center">Alquilada</td>
                                    <td class="dt-center"><?=h($maq->costos_maquinas[0]->worksgroup->name) ?></td>
                                    <td class="actions" style="text-align: center">
                                        <?= $this->Form->button($this->Html->tag('span', '', ['class' => 'fas fa-check', 'aria-hidden' => 'true']),
                                            ['type' => 'button', 'class' => 'btn btn-success', 'escape' => false,
                                                'maquina' => $maq->marca . ': ' . $maq->name,
                                                'id_maquina' => $maq->idmaquinas,
                                                'onclick' => 'addMaquinaAlquiladaToRemito(this)']) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_maquinas"
                                onclick="closeModal(this)">Salir</button>
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
    });

    $(function () {
        $('#tabladata_maq_modal').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        });
    })
</script>

