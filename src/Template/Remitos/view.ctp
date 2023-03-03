
<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('remitos_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Ver Remito
                </h3>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-lg-10 col-md-10" style="margin: 0 auto;">

                        <?php if(!empty($remitos->created)): ?>
                            <p class="lead"><strong>Fecha de Carga del Remito: </strong> <?= h($remitos->created->format('d-m-Y')) ?></p>
                        <?php else: ?>
                            <p class="lead"><strong>Fecha de Carga del Comprobante: </strong>Sin datos</p>
                        <?php endif;?>

                        <?php if(!empty($remitos->modified)): ?>
                            <p class="lead"><strong>Fecha de última modificación: </strong> <?= h($remitos->modified->format('d-m-Y')) ?></p>
                        <?php else: ?>
                            <p class="lead"><strong>Fecha de última modificación: </strong>Sin datos</p>
                        <?php endif;?>

                        <p class="lead"><strong>Operador: </strong><?= h($remitos->user->firstname . ' '. $remitos->user->lastname) ?></p>
                        <br>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th style="width:30%">N° de Remito:</th>
                                    <td><?= h($remitos->remito_number)?> </td>
                                </tr>

                                <tr>
                                    <th style="width:30%">Hash ID del Remito:</th>
                                    <td><?= h($remitos->hash_id)?> </td>
                                </tr>
                                <tr>
                                    <th>Fecha:</th>

                                    <?php if(!empty($remitos->fecha)): ?>
                                        <td><?= h($remitos->fecha->format('d-m-Y')) ?></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif;?>

                                </tr>
                                <tr>
                                    <th style="vertical-align: middle;">Grupo:</th>
                                    <td><?= h($remitos->worksgroup->name)?></td>
                                </tr>

                                <!-- Consulto si el lote o la parcela estan vacaias -->

                                <?php if($remitos->parcelas_idparcelas != ''):  ?>

                                    <tr>
                                        <th>Lote:</th>
                                        <td><?= h($remitos->lote->name)?></td>
                                    </tr>

                                    <tr>
                                        <th>Parcela:</th>
                                        <td><?= h($remitos->parcela->name)?></td>
                                    </tr>

                                <?php endif;  ?>

                                <tr>
                                    <th>Destino:</th>
                                    <td><?= h($remitos->destino->name)?></td>
                                </tr>


                                <tr>
                                    <th>Propietario:</th>

                                    <?php if(!empty($remitos->propietario->tipo == 'Empresa')): ?>
                                        <td><?= h($remitos->propietario->name)?></td>
                                    <?php else: ?>
                                        <td><?= h($remitos->propietario->firstname . ' ' . $remitos->propietario->lastname)?></td>
                                    <?php endif;?>

                                </tr>


                                <tr>
                                    <th style="vertical-align: middle;">Producto:</th>
                                    <td><?= h($remitos->producto->name)?></td>
                                </tr>

                                <tr>
                                    <th>Toneladas:</th>
                                    <td><?= h($remitos->ton)?></td>
                                </tr>

                                <tr>
                                    <th>Precio ($/t):</th>
                                    <td><?= h($remitos->precio_ton)?></td>
                                </tr>


                                <?php $count = count($remitos->remitos_maquinas); $i = 0;?>
                                <?php foreach ($remitos->remitos_maquinas as $maq) :?>

                                    <?php if($i == 0): ?>
                                        <tr>
                                            <td rowspan=" <?= h($count)?>" style="vertical-align: middle"><strong>Máquina:</strong> </td>
                                            <td>
                                                <p><small><strong>Máquina: </strong> <?= h($maq->maquina->name)?></small></p>
                                                <p><small><strong>Operador:</strong>
                                                    <?= h($maq->operario->firstname . ' ' . $maq->operario->lastname)?></small></p>
                                                <p><small><strong>Alquiler t: </strong> <?= h($maq->alquiler_ton)?></small></p>

                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td>
                                                <p><small><strong>Máquina: </strong> <?= h($maq->maquina->name)?></small></p>
                                                <p><small><strong>Operador:</strong>
                                                        <?= h($maq->operario->firstname . ' ' . $maq->operario->lastname)?></small></p>
                                                <p><small><strong>Alquiler t: </strong> <?= h($maq->alquiler_ton)?></small></p>
                                            </td>
                                        </tr>
                                    <?php endif;?>

                                    <?php $i = $i + 1;?>
                                <?php endforeach;?>

                                </tbody>
                            </table>
                            <hr>
                            <?= $this->Html->link($this->Html->tag('span', ' Editar', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                ['action' => 'edit', $remitos->idremitos], ['class' => 'btn bg-purple', 'escape' => false]) ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
